<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'realestate';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Connection Failed :" .mysqli_connect_error());
}

date_default_timezone_set('Asia/Manila');
mysqli_set_charset($conn, "utf8");
$date = date('Y-m-d H:i:s');