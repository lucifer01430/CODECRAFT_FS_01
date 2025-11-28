<?php
// app/views/auth/register.php

require_once __DIR__ . '/../../../error-config.php';
$title = "Register";

ob_start();
?>

<?php $bodyClass = 'login-page'; ?>
<div class="register-page d-flex justify-content-center align-items-center" style="min-height:70vh;">
    <div class="register-box" style="width:520px;">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="index.php" class="h1"><b>Code</b>Craft</a>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Register a new account</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= e($success) ?></div>
                <?php endif; ?>

                <form action="index.php?page=register_submit" method="POST" novalidate>
                    <input type="hidden" name="_csrf_token" value="<?= csrf_token() ?>">

                    <div class="input-group mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Full name" required value="<?= e($_POST['name'] ?? '') ?>">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required value="<?= e($_POST['email'] ?? '') ?>">
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Retype password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-check"></span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>

                <a href="index.php?page=login" class="text-center d-block mt-3">I already have an account</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
