<?php
require_once __DIR__ . '/../../../error-config.php';

$title = "Reset Password";
ob_start();
?>

<?php $bodyClass = 'login-page'; ?>
<div class="reset-page d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="login-box" style="width:420px;">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="index.php" class="h1"><b>Code</b>Craft</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Set a new password for your account</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= e($success) ?></div>
                <?php endif; ?>

                <form action="index.php?page=reset_password_submit" method="post">
                    <input type="hidden" name="_csrf_token" value="<?= csrf_token() ?>">
                    <input type="hidden" name="email" value="<?= e($_GET['email'] ?? '') ?>">
                    <input type="hidden" name="token" value="<?= e($_GET['token'] ?? '') ?>">

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="New Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-check"></span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-primary btn-block">Reset Password</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-0 text-center">
                    <a href="index.php?page=login">Back to Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
