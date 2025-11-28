<?php


// User Model
class User
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $passwordHash, $role = 'user')
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, password, role) 
             VALUES (:name, :email, :password, :role)"
        );
        $stmt->execute([
            'name'     => $name,
            'email'    => $email,
            'password' => $passwordHash,
            'role'     => $role
        ]);

        return $this->pdo->lastInsertId();
    }

    public function setOtp($userId, $otpCode, $expiresAt)
    {
        $stmt = $this->pdo->prepare(
            "UPDATE users 
             SET otp_code = :otp_code, otp_expires_at = :otp_expires_at 
             WHERE id = :id"
        );
        return $stmt->execute([
            'otp_code'       => $otpCode,
            'otp_expires_at' => $expiresAt,
            'id'             => $userId
        ]);
    }

    public function verifyOtpForEmail($email, $otpCode)
    {
        // Fetch user by email first to provide better logging and diagnostics
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            error_log("verifyOtpForEmail: user not found for email={$email}");
            return false;
        }

        // Log stored OTP and expiry for debugging (do not expose to users)
        error_log("verifyOtpForEmail: stored_otp={$user['otp_code']} expires_at={$user['otp_expires_at']} for email={$email}");

        // Check otp and expiry in PHP for clearer behavior
        if (empty($user['otp_code']) || (string)$user['otp_code'] !== (string)$otpCode) {
            error_log("verifyOtpForEmail: otp mismatch for email={$email} provided={$otpCode}");
            return false;
        }

        // Check expiry
        if (!empty($user['otp_expires_at'])) {
            $expires = strtotime($user['otp_expires_at']);
            if ($expires < time()) {
                error_log("verifyOtpForEmail: otp expired for email={$email} expired_at={$user['otp_expires_at']}");
                return false;
            }
        } else {
            error_log("verifyOtpForEmail: no otp_expires_at set for email={$email}");
            return false;
        }

        // Mark verified
        $update = $this->pdo->prepare(
            "UPDATE users 
             SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL 
             WHERE id = :id"
        );
        $update->execute(['id' => $user['id']]);

        error_log("verifyOtpForEmail: verification successful for email={$email}");
        return $user;
    }
}
