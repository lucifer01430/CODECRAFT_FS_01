<?php


// PHP errors ko log file me store karega
ini_set('log_errors', 1);

// debug.log file ka exact path
ini_set('error_log', __DIR__ . '/debug.log');

// Sare errors ko capture karo
error_reporting(E_ALL);

?>