<?php
// app/controllers/DashboardController.php

class DashboardController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function index()
    {
        // Protect route
        requireLogin();

        // User name show karne ke liye session me store karenge login ke time
        $userName = $_SESSION['user_name'] ?? 'User';

        include __DIR__ . '/../views/dashboard.php';
    }
}
