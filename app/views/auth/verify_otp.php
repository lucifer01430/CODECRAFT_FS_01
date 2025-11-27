<?php
require_once __DIR__ . '/../error-config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="text-center mb-3">Verify Your Email</h3>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form method="post" action="index.php?page=verify_otp_submit">
                        <div class="mb-3">
                            <label for="email" class="form-label">Registered Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($email ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="otp" class="form-label">OTP Code</label>
                            <input type="text" class="form-control" id="otp" name="otp" maxlength="6" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="index.php?page=login">Back to Login</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
