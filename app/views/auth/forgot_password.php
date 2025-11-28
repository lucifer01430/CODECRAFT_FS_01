<?php
require_once __DIR__ . '/../../../error-config.php';

$title = "Forgot Password";
ob_start();
?>

<?php $bodyClass = 'login-page'; ?>
<div class="forgot-page d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="login-box" style="width:420px;">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="index.php" class="h1"><b>Code</b>Craft</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Enter your registered email to receive a reset link</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= e($success) ?></div>
                <?php endif; ?>

                <form method="post" action="index.php?page=forgot_password_submit">
                    <input type="hidden" name="_csrf_token" value="<?= csrf_token() ?>">

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
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
