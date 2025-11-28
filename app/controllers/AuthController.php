<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    private $pdo;
    private $userModel;
    private $baseUrl;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->userModel = new User($pdo);
        $this->baseUrl = 'index.php';
    }

    public function register()
    {
        $error = getFlash('error');
        $success = getFlash('success');

        include __DIR__ . '/../views/auth/register.php';
    }

    public function registerSubmit()
    {

        // CSRF check
        if (!csrf_validate($_POST['_csrf_token'] ?? '')) {
            setFlash('error', 'Invalid CSRF token. Please try again.');
            redirect("{$this->baseUrl}?page=register");
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = normalize_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if ($name === '' || $email === '' || $password === '' || $confirm === '') {
            setFlash('error', "All fields are required.");
            redirect("{$this->baseUrl}?page=register");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlash('error', "Invalid email address.");
            redirect("{$this->baseUrl}?page=register");
        }

        if ($password !== $confirm) {
            setFlash('error', "Passwords do not match.");
            redirect("{$this->baseUrl}?page=register");
        }

        // Password strength: min 8 chars, at least one letter and one number
        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            setFlash('error', 'Password must be at least 8 characters long and contain at least one letter and one number.');
            redirect("{$this->baseUrl}?page=register");
        }

        // Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            setFlash('error', "Email already registered.");
            redirect("{$this->baseUrl}?page=register");
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Create user
        $userId = $this->userModel->create($name, $email, $passwordHash);

        // Generate OTP (6-digit)
        $otpCode = rand(100000, 999999);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $this->userModel->setOtp($userId, $otpCode, $expiresAt);

        // Send OTP mail
        $this->sendOtpMail($email, $name, $otpCode);

        // Save email in session for verification step
        $_SESSION['verify_email'] = $email;
        setFlash('success', "Registration successful! An OTP has been sent to your email. Please verify.");

        redirect("{$this->baseUrl}?page=verify_otp");
    }

    public function login()
    {
        $error = getFlash('error');
        $success = getFlash('success');
        include __DIR__ . '/../views/auth/login.php';
    }

    public function loginSubmit()
    {

        // CSRF check
        if (!csrf_validate($_POST['_csrf_token'] ?? '')) {
            setFlash('error', 'Invalid CSRF token.');
            redirect("{$this->baseUrl}?page=login");
        }

        $email    = normalize_email($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            setFlash('error', "Email and password are required.");
            redirect("{$this->baseUrl}?page=login");
        }

        // Rate limiting: per-IP and per-email
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $keyIp = "login_ip_{$ip}";
        $keyEmail = "login_email_{$email}";
        $maxAttempts = 5;
        $decaySeconds = 900; // 15 minutes

        // init
        if (empty($_SESSION[$keyIp])) {
            $_SESSION[$keyIp] = ['count' => 0, 'first' => time()];
        }
        if (empty($_SESSION[$keyEmail])) {
            $_SESSION[$keyEmail] = ['count' => 0, 'first' => time()];
        }

        // reset if decay passed
        if (time() - $_SESSION[$keyIp]['first'] > $decaySeconds) {
            $_SESSION[$keyIp] = ['count' => 0, 'first' => time()];
        }
        if (time() - $_SESSION[$keyEmail]['first'] > $decaySeconds) {
            $_SESSION[$keyEmail] = ['count' => 0, 'first' => time()];
        }

        if ($_SESSION[$keyIp]['count'] >= $maxAttempts || $_SESSION[$keyEmail]['count'] >= $maxAttempts) {
            setFlash('error', 'Too many failed login attempts. Please wait 15 minutes and try again.');
            redirect("{$this->baseUrl}?page=login");
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION[$keyIp]['count']++;
            $_SESSION[$keyEmail]['count']++;
            setFlash('error', "Invalid email or password.");
            redirect("{$this->baseUrl}?page=login");
        }


        if ((int)$user['is_verified'] !== 1) {
            setFlash('error', "Your account is not verified. Please check your email for OTP.");
            $_SESSION['verify_email'] = $email;
            redirect("{$this->baseUrl}?page=verify_otp");
        }

        // Login success
        // clear attempt counters on success
        unset($_SESSION[$keyIp], $_SESSION[$keyEmail]);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        redirect("{$this->baseUrl}?page=dashboard");
    }

    public function verifyOtp()
    {
        $error = getFlash('error');
        $success = getFlash('success');

        $email = $_SESSION['verify_email'] ?? '';

        include __DIR__ . '/../views/auth/verify_otp.php';
    }

    public function verifyOtpSubmit()
    {
        // CSRF check
        if (!csrf_validate($_POST['_csrf_token'] ?? '')) {
            setFlash('error', 'Invalid CSRF token.');
            redirect("{$this->baseUrl}?page=verify_otp");
        }

        $email = normalize_email($_POST['email'] ?? '');
        $otp   = trim($_POST['otp'] ?? '');

        if ($email === '' || $otp === '') {
            setFlash('error', "Email and OTP are required.");
            redirect("{$this->baseUrl}?page=verify_otp");
        }

        // Rate limiting for OTP verification per email
        $keyOtp = "otp_attempt_{$email}";
        $maxOtpAttempts = 5;
        $otpDecay = 900; // 15 minutes
        if (empty($_SESSION[$keyOtp])) {
            $_SESSION[$keyOtp] = ['count' => 0, 'first' => time()];
        }
        if (time() - $_SESSION[$keyOtp]['first'] > $otpDecay) {
            $_SESSION[$keyOtp] = ['count' => 0, 'first' => time()];
        }
        if ($_SESSION[$keyOtp]['count'] >= $maxOtpAttempts) {
            setFlash('error', 'Too many OTP attempts. Please wait and try again later.');
            redirect("{$this->baseUrl}?page=verify_otp");
        }

        // Fetch user to provide better messages
        $userRow = $this->userModel->findByEmail($email);
        if (!$userRow) {
            $_SESSION[$keyOtp]['count']++;
            setFlash('error', 'No account found for that email.');
            redirect("{$this->baseUrl}?page=verify_otp");
        }

        // Check expiry
        if (!empty($userRow['otp_expires_at']) && strtotime($userRow['otp_expires_at']) < time()) {
            setFlash('error', 'OTP expired. Please resend OTP.');
            redirect("{$this->baseUrl}?page=verify_otp");
        }

        $user = $this->userModel->verifyOtpForEmail($email, $otp);

        if (!$user) {
            $_SESSION[$keyOtp]['count']++;
            setFlash('error', 'Invalid OTP.');
            redirect("{$this->baseUrl}?page=verify_otp");
        }

        setFlash('success', 'Your account has been verified. You can now log in.');
        redirect("{$this->baseUrl}?page=login");
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        // Start session again for flash use
        session_start();
        setFlash('success', 'Logged out successfully.');
        redirect("{$this->baseUrl}?page=login");
    }

    private function sendOtpMail($email, $name, $otpCode)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'testing@pixelperfectstrategies.com';
            $mail->Password   = 'vQ?dcr8f1';
            // Use TLS on port 587 for STARTTLS
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 465;

            // In some local/dev environments certificate verification may fail
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            // Recipients
            // Use the configured username as the FROM address to avoid SMTP rejection
            $mail->setFrom($mail->Username, 'CodeCraft Auth System');
            $mail->addAddress($email, $name);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code for Account Verification';
            $mail->Body    = "Hello {$name},<br><br>Your OTP code is: <b>{$otpCode}</b><br>This code is valid for 10 minutes.<br><br>Regards,<br>CodeCraft Auth System";
            $mail->AltBody = "Hello {$name},\n\nYour OTP code is {$otpCode}. It is valid for 10 minutes.\n\nRegards,\nCodeCraft Auth System";

            $mail->send();
        } catch (Exception $e) {
            // Log error with exception message for debugging
            error_log('Mailer Exception: ' . $e->getMessage());
            error_log('Mailer ErrorInfo: ' . $mail->ErrorInfo);
        }
    }

    // Send password reset email with a reset link
    private function sendResetEmail($email, $name, $resetLink)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'testing@pixelperfectstrategies.com';
            $mail->Password   = 'vQ?dcr8f1';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ];

            $mail->setFrom($mail->Username, 'CodeCraft Auth System');
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hello {$name},<br><br>Click the link below to reset your password:<br><a href=\"{$resetLink}\">Reset Password</a><br><br>This link is valid for 15 minutes.<br><br>Regards,<br>CodeCraft Auth System";
            $mail->AltBody = "Hello {$name},\n\nVisit the following link to reset your password: {$resetLink}\n\nThis link is valid for 15 minutes.\n\nRegards,\nCodeCraft Auth System";

            $mail->send();
        } catch (Exception $e) {
            error_log('Reset mail failed: ' . $e->getMessage());
        }
    }

    public function resendOtp()
{
    // Only allow POST for resend to protect from CSRF
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        setFlash('error', 'Invalid request method.');
        redirect("{$this->baseUrl}?page=verify_otp");
    }

    if (!csrf_validate($_POST['_csrf_token'] ?? '')) {
        setFlash('error', 'Invalid CSRF token.');
        redirect("{$this->baseUrl}?page=verify_otp");
    }

    $email = normalize_email($_SESSION['verify_email'] ?? '');

    if (!$email) {
        setFlash('error', "Session expired. Please register or log in again.");
        redirect("{$this->baseUrl}?page=register");
    }

    $user = $this->userModel->findByEmail($email);
    if (!$user) {
        setFlash('error', "User not found.");
        redirect("{$this->baseUrl}?page=register");
    }

    // Resend cooldown per email (60 seconds)
    $key = "otp_resend_{$email}";
    $cooldown = 60;
    if (!empty($_SESSION[$key]) && time() - $_SESSION[$key] < $cooldown) {
        setFlash('error', 'Please wait before resending OTP.');
        redirect("{$this->baseUrl}?page=verify_otp");
    }

    $otpCode   = rand(100000, 999999);
    $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    $this->userModel->setOtp($user['id'], $otpCode, $expiresAt);
    $this->sendOtpMail($email, $user['name'], $otpCode);

    $_SESSION[$key] = time();
    setFlash('success', "A new OTP has been sent to your email.");
    redirect("{$this->baseUrl}?page=verify_otp");
}

    public function forgotPassword()
{
    $error = getFlash('error');
    $success = getFlash('success');

    include __DIR__ . '/../views/auth/forgot_password.php';
}

    public function forgotPasswordSubmit()
{
    $email = strtolower(trim($_POST['email'] ?? ''));

    if ($email === '') {
        setFlash('error', 'Email is required.');
        header("Location: index.php?page=forgot_password");
        exit;
    }

    $user = $this->userModel->findByEmail($email);

    if (!$user) {
        setFlash('success', 'If this email exists, a reset link has been sent.');
        header("Location: index.php?page=forgot_password");
        exit;
    }

    // Create token
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

    $this->userModel->storeResetToken($user['id'], $token, $expiresAt);

    $resetLink = "http://localhost/CODECRAFT_FS_01/public/index.php?page=reset_password&token=" . urlencode($token);

    // Send email
    $this->sendResetEmail($email, $user['name'], $resetLink);

    setFlash('success', 'Password reset link has been sent to your email.');
    header("Location: index.php?page=forgot_password");
    exit;
}

    public function resetPassword()
{
    $token = $_GET['token'] ?? null;

    if (!$token) {
        setFlash('error', 'Invalid reset link.');
        header("Location: index.php?page=forgot_password");
        exit;
    }

    include __DIR__ . '/../views/auth/reset_password.php';
}


        public function resetPasswordSubmit()
{
    $token = $_POST['token'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirm = $_POST['confirm_password'] ?? null;

    if (!$token || !$password || !$confirm) {
        setFlash('error', 'All fields are required.');
        header("Location: index.php?page=reset_password&token=" . urlencode($token));
        exit;
    }

    if ($password !== $confirm) {
        setFlash('error', 'Passwords do not match.');
        header("Location: index.php?page=reset_password&token=" . urlencode($token));
        exit;
    }

    $user = $this->userModel->findByResetToken($token);
    if (!$user) {
        setFlash('error', 'Invalid or expired token.');
        header("Location: index.php?page=forgot_password");
        exit;
    }

    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $this->userModel->updatePassword($user['id'], $passwordHash);

    setFlash('success', 'Password reset successful. You can now login.');
    header("Location: index.php?page=login");
    exit;
}


}
