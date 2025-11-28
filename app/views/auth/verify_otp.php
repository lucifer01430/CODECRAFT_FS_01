<?php
require_once __DIR__ . '/../../../error-config.php';

$title = "Verify OTP";

ob_start();
?>

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="text-center mb-3">Verify Your Email</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= e($success) ?></div>
                <?php endif; ?>

                <form method="post" action="index.php?page=verify_otp_submit">
                    <input type="hidden" name="_csrf_token" value="<?= csrf_token() ?>">
                    <div class="mb-3">
                        <label for="email" class="form-label">Registered Email</label>
                           <input type="email" class="form-control" id="email" name="email"
                               value="<?= e($email ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="otp" class="form-label">OTP Code</label>
                        <input type="text" class="form-control" id="otp" name="otp" maxlength="6" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
                </form>
                <div class="mt-3 text-center">
                    <form method="post" action="index.php?page=resend_otp" style="display:inline">
                        <input type="hidden" name="_csrf_token" value="<?= csrf_token() ?>">
                        <button type="submit" class="btn btn-link p-0">Resend OTP</button>
                    </form>
                </div>

                <div class="mt-1 text-center">
                    <a href="index.php?page=login">Back to Login</a>
                </div>


            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
