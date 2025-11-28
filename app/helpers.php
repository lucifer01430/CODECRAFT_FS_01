<?php
// app/helpers.php

function setFlash($key, $message)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION['flash'][$key] = $message;
}

function getFlash($key)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function requireLogin()
{
    if (empty($_SESSION['user_id'])) {
        setFlash('error', 'Please log in to access that page.');
        redirect('index.php?page=login');
    }
}

// CSRF helpers
function csrf_token()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['_csrf_token'];
}

function csrf_validate($token)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    if (empty($token) || empty($_SESSION['_csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['_csrf_token'], $token);
}

// Normalize email
function normalize_email($email)
{
    return strtolower(trim($email));
}

// Safe output
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
