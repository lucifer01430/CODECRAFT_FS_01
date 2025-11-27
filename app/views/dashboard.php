<?php

$title = "Dashboard";

ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">

                <h2>Welcome, <?= htmlspecialchars($userName) ?> 👋</h2>
                <p class="mt-3">You are successfully logged in!</p>

                <a href="index.php?page=logout" class="btn btn-danger mt-3">Logout</a>

            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
