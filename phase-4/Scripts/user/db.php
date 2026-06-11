<?php
// Shared MySQL connection for all user-side pages.
// Included at the top of every PHP file that talks to the bankmanagement database.

// PHP 8+ makes mysqli throw exceptions on error by default. We want the older
// "return false, then read $mysqli->error" behavior so we can catch trigger
// errors cleanly.
mysqli_report(MYSQLI_REPORT_OFF);

$mysqli = new mysqli('localhost', 'root', '', 'bankmanagement');

if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}

$mysqli->set_charset('utf8mb4');
?>
