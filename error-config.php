<?php


// PHP errors ko log file me store karega
ini_set('log_errors', 1);
// debug.log file ka exact path
ini_set('error_log', __DIR__ . '/debug.log');

// Ensure startup/display errors are not printed to output in production,
// but everything is still logged into debug.log
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Sare errors ko capture karo
error_reporting(E_ALL);

// Optional: set a default timezone so timestamps in logs are consistent
if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// Initial log entry to mark startup (helps verify logging works)
error_log('error-config initialized: PHP error logging enabled');

?>