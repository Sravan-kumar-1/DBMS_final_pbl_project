<?php
$host   = '127.0.0.1';
$user   = 'root';
$pass   = '';            // put root password if you set one
$dbname = 'garage_db';
$port   = 3307;          // <-- set this to 3306 OR 3307 depending on XAMPP. Your error used 3307

$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("DB Connection Failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>
