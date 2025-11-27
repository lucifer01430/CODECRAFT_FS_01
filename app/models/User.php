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
        $stmt = $this->pdo->prepare(
            "SELECT * FROM users 
             WHERE email = :email 
               AND otp_code = :otp_code 
               AND otp_expires_at >= NOW()
             LIMIT 1"
        );
        $stmt->execute([
            'email'   => $email,
            'otp_code'=> $otpCode
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        // Mark verified
        $update = $this->pdo->prepare(
            "UPDATE users 
             SET is_verified = 1, otp_code = NULL, otp_expires_at = NULL 
             WHERE id = :id"
        );
        $update->execute(['id' => $user['id']]);

        return $user;
    }
}
