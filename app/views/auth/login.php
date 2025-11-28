<?php

$title = "Login";
$bodyClass = 'login-page'; // important: tells layout this is an auth page

ob_start();
?>

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="index.php" class="h1"><b>Code</b>Craft</a>
        </div>

        <div class="card-body">

            <p class="login-box-msg">Sign in to start your session</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= e($success) ?></div>
            <?php endif; ?>

            <form action="index.php?page=login_submit" method="POST" novalidate>
                <input type="hidden" name="_csrf_token" value="<?= csrf_token() ?>">

                <div class="input-group mb-3">
                    <input type="email"
                           name="email"
                           class="form-control"
                           placeholder="Email"
                           required
                           value="<?= e($_POST['email'] ?? '') ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password"
                           name="password"
                           class="form-control"
                           placeholder="Password"
                           required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <button type="submit"
                                class="btn btn-primary btn-block">
                            Sign In
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-3 d-flex justify-content-between align-items-center">
                <a href="index.php?page=forgot_password">Forgot password?</a>
                <a href="index.php?page=verify_otp">Verify OTP</a>
            </div>

            <p class="mb-0 text-center mt-3">
                <a href="index.php?page=register">Register a new membership</a>
            </p>

        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
