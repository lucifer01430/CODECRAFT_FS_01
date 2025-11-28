<?php
require_once __DIR__ . '/../../../error-config.php';

$title = "Verify OTP";

ob_start();
?>

<?php $bodyClass = 'login-page'; ?>
<div class="verify-page d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="login-box" style="width:420px;">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="index.php" class="h1"><b>Code</b>Craft</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Enter the verification code sent to your email</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= e($success) ?></div>
                <?php endif; ?>

                <form method="post" action="index.php?page=verify_otp_submit">
                    <input type="hidden" name="_csrf_token" value="<?= csrf_token() ?>">

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Registered Email"
                               value="<?= e($email ?? '') ?>" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="otp" name="otp" maxlength="6" placeholder="OTP Code" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-key"></span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Verify OTP</button>
                        </div>
                    </div>
                </form>

                <div class="row mt-3">
                    <div class="col-6 text-left">
                        <form method="post" action="index.php?page=resend_otp" style="display:inline">
                            <input type="hidden" name="_csrf_token" value="<?= csrf_token() ?>">
                            <button type="submit" class="btn btn-link p-0">Resend OTP</button>
                        </form>
                    </div>
                    <div class="col-6 text-right">
                        <a href="index.php?page=login">Back to Login</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
