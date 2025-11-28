<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? "Auth System" ?></title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 4.6 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

</head>

<body class="hold-transition layout-top-nav">

<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white border-bottom">
        <div class="container">
            <a href="index.php?page=dashboard" class="navbar-brand">
                <span class="brand-text font-weight-light">Auth System</span>
            </a>

            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                <ul class="navbar-nav ml-auto">
                    <?php if (!empty($_SESSION['user_id'] ?? null)): ?>
                        <li class="nav-item nav-link">
                            Welcome, <?= e($_SESSION['user_name'] ?? 'User') ?>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content pt-4">
            <div class="container">
                <?php $flashError = getFlash('error'); ?>
                <?php $flashSuccess = getFlash('success'); ?>
                <?php if (!empty($flashError)): ?>
                    <div class="alert alert-danger mt-3"><?= e($flashError) ?></div>
                <?php endif; ?>
                <?php if (!empty($flashSuccess)): ?>
                    <div class="alert alert-success mt-3"><?= e($flashSuccess) ?></div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer text-sm text-center">
        <strong>
            This project is designed and developed by <a href="https://lucifer01430.github.io/Portfolio/">Harsh Pandey</a>
        </strong>
        during the virtual internship at CodeCraft
        (Full Stack Web Development – Task 01: Secure User Authentication).
    </footer>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>
</html>
