<?php

require_once __DIR__ . '/../error-config.php';
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// Load helpers (flash, csrf, redirect, etc.)
require_once __DIR__ . '/../app/helpers.php';

// Load database
require_once __DIR__ . '/../config/database.php';

// Load models
require_once __DIR__ . '/../app/models/User.php';

// Load controllers
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

// Simple routing using "page" query param
$page = $_GET['page'] ?? 'login';

$authController = new AuthController($pdo);
$dashboardController = new DashboardController($pdo);

switch ($page) {
    case 'register':
        $authController->register();
        break;

    case 'register_submit':
        $authController->registerSubmit();
        break;

    case 'login':
        $authController->login();
        break;

    case 'login_submit':
        $authController->loginSubmit();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'dashboard':
        $dashboardController->index();
        break;

    case 'verify_otp':
    $authController->verifyOtp();
    break;

    case 'verify_otp_submit':
    $authController->verifyOtpSubmit();
    break;

    case 'resend_otp':
    $authController->resendOtp();
    break;

    case 'forgot_password':
    $authController->forgotPassword();
    break;

    case 'forgot_password_submit':
        $authController->forgotPasswordSubmit();
        break;

    case 'reset_password':
        $authController->resetPassword();
        break;

    case 'reset_password_submit':
        $authController->resetPasswordSubmit();
        break;

    
    default:
        // Unknown page → redirect to login
        header('Location: index.php?page=login');
        exit;
}
