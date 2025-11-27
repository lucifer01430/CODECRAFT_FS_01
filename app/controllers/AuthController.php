<?php

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
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        include __DIR__ . '/../views/auth/register.php';
    }

    public function registerSubmit()
    {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if ($name === '' || $email === '' || $password === '' || $confirm === '') {
            $_SESSION['error'] = "All fields are required.";
            header("Location: {$this->baseUrl}?page=register");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email address.";
            header("Location: {$this->baseUrl}?page=register");
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['error'] = "Passwords do not match.";
            header("Location: {$this->baseUrl}?page=register");
            exit;
        }

        // Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            $_SESSION['error'] = "Email already registered.";
            header("Location: {$this->baseUrl}?page=register");
            exit;
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
        $_SESSION['success'] = "Registration successful! An OTP has been sent to your email. Please verify.";

        header("Location: {$this->baseUrl}?page=verify_otp");
        exit;
    }

    public function login()
    {
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        include __DIR__ . '/../views/auth/login.php';
    }

    public function loginSubmit()
    {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['error'] = "Email and password are required.";
            header("Location: {$this->baseUrl}?page=login");
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Invalid email or password.";
            header("Location: {$this->baseUrl}?page=login");
            exit;
        }

        if ((int)$user['is_verified'] !== 1) {
            $_SESSION['error'] = "Your account is not verified. Please check your email for OTP.";
            $_SESSION['verify_email'] = $email;
            header("Location: {$this->baseUrl}?page=verify_otp");
            exit;
        }

        // Login success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        header("Location: {$this->baseUrl}?page=dashboard");
        exit;
    }

    public function verifyOtp()
    {
        $error = $_SESSION['error'] ?? null;
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['error'], $_SESSION['success']);

        $email = $_SESSION['verify_email'] ?? '';

        include __DIR__ . '/../views/auth/verify_otp.php';
    }

    public function verifyOtpSubmit()
    {
        $email = trim($_POST['email'] ?? '');
        $otp   = trim($_POST['otp'] ?? '');

        if ($email === '' || $otp === '') {
            $_SESSION['error'] = "Email and OTP are required.";
            header("Location: {$this->baseUrl}?page=verify_otp");
            exit;
        }

        $user = $this->userModel->verifyOtpForEmail($email, $otp);

        if (!$user) {
            $_SESSION['error'] = "Invalid or expired OTP.";
            header("Location: {$this->baseUrl}?page=verify_otp");
            exit;
        }

        $_SESSION['success'] = "Your account has been verified. You can now log in.";
        header("Location: {$this->baseUrl}?page=login");
        exit;
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: {$this->baseUrl}?page=login");
        exit;
    }

    private function sendOtpMail($email, $name, $otpCode)
    {
        // Simple mail() version (works only if server mail configured)
        $subject = "Your OTP Code for Account Verification";
        $message = "Hello $name,\n\nYour OTP code is: $otpCode\nThis code is valid for 10 minutes.\n\nRegards,\nCodeCraft Auth System";
        $headers = "From: no-reply@codecraft.local";

        @mail($email, $subject, $message, $headers);

        // NOTE: For real production, better use PHPMailer + SMTP
    }
}
