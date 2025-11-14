<?php
// Database connection helper for SeaSide Resort
// Adjust credentials if you've set a password for root or created another MySQL user.
// Recommended for production: create a dedicated user with limited privileges.

$DB_HOST = 'localhost';
$DB_NAME = 'seasideresort';
$DB_USER = 'root';
$DB_PASS = ''; // <- XAMPP default is empty. Change if you've set a password.

$conn = mysqli_init();
mysqli_options($conn, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

if (!mysqli_real_connect($conn, $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME)) {
    http_response_code(500);
    die('Database connection failed: ' . mysqli_connect_error());
}

// Optional: set UTF-8
mysqli_set_charset($conn, 'utf8mb4');

return $conn;
?>
