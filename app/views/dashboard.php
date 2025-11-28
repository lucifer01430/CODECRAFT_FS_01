<?php

$title = "Dashboard";

ob_start();
?>

<div class="row justify-content-center mt-4">
    <div class="col-md-8">
        <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
                <h3 class="card-title mb-0">User Dashboard</h3>
            </div>

            <div class="card-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active"
                           id="welcome-tab"
                           data-toggle="tab"
                           href="#welcome"
                           role="tab"
                           aria-controls="welcome"
                           aria-selected="true">
                            Welcome
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           id="overview-tab"
                           data-toggle="tab"
                           href="#overview"
                           role="tab"
                           aria-controls="overview"
                           aria-selected="false">
                            Project Overview
                        </a>
                    </li>
                </ul>

                <div class="tab-content pt-3" id="dashboardTabsContent">
                    <!-- Welcome Tab -->
                    <div class="tab-pane fade show active"
                         id="welcome"
                         role="tabpanel"
                         aria-labelledby="welcome-tab">
                        <div class="text-center py-4">
                            <h2 class="mb-3">Welcome, <?= e($userName) ?> 👋</h2>
                            <p class="text-muted mb-4">
                                You are successfully logged in to the secure authentication system.
                            </p>
                            <a href="index.php?page=logout" class="btn btn-danger px-4">Logout</a>
                        </div>
                    </div>

                    <!-- Project Overview Tab -->
                    <div class="tab-pane fade"
                         id="overview"
                         role="tabpanel"
                         aria-labelledby="overview-tab">

                        <h4 class="mb-3">Project Overview</h4>

                        <p>
                            This application is a
                            <strong>Secure User Authentication System</strong>
                            developed as part of the
                            <strong>CodeCraft Full Stack Web Development</strong>
                            virtual internship.
                        </p>

                        <p>
                            The goal of this project is to implement a professional authentication flow
                            including user registration, email-based OTP verification, secure login,
                            session management, and protected routes for authorized users.
                        </p>

                        <h5 class="mt-3">Why this project was built?</h5>
                        <ul>
                            <li>To practice secure login and registration using industry best practices.</li>
                            <li>To implement email-based OTP verification using PHPMailer and SMTP.</li>
                            <li>To showcase a clean, maintainable MVC-style PHP project structure.</li>
                            <li>To complete <strong>Task 01 – Secure User Authentication</strong> in the CodeCraft internship track.</li>
                        </ul>

                        <h5 class="mt-3">Tech Stack Used</h5>
                        <ul>
                            <li><strong>Backend:</strong> PHP 8 (Core PHP, MVC pattern)</li>
                            <li><strong>Database:</strong> MySQL (PDO, prepared statements)</li>
                            <li><strong>Email:</strong> PHPMailer + SMTP (for OTP delivery)</li>
                            <li><strong>Frontend:</strong> HTML, CSS, Bootstrap 4, AdminLTE 3</li>
                            <li><strong>Server:</strong> Apache (XAMPP) with <code>.htaccess</code> routing</li>
                        </ul>

                        <h5 class="mt-3">Author &amp; Context</h5>
                        <p>
                            This assignment has been designed and implemented by
                            <strong><a href="https://lucifer01430.github.io/Portfolio/" target="_blank">Harsh Pandey</a></strong>
                            as part of
                            <strong>Task 01 – Secure User Authentication</strong>
                            in the
                            <strong>CodeCraft Full Stack Web Development Virtual Internship</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
