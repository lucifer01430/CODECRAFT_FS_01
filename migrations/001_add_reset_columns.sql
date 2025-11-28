-- Migration: add reset_token and reset_expires_at to users table
ALTER TABLE `users`
  ADD COLUMN `reset_token` varchar(128) DEFAULT NULL AFTER `otp_code`,
  ADD COLUMN `reset_expires_at` datetime DEFAULT NULL AFTER `reset_token`;

-- Optional: verify
SELECT id, email, otp_code, reset_token, otp_expires_at, reset_expires_at FROM users LIMIT 10;
