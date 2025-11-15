<?php


$DB_HOST = 'localhost';
$DB_NAME = 'seasideresort';
$DB_USER = 'root';
$DB_PASS = '';

$conn = mysqli_init();
mysqli_options($conn, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);

if (!mysqli_real_connect($conn, $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME)) {
    http_response_code(500);
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');

return $conn;
?>
