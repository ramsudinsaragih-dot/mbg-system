<?php
// Database Connection Configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mbg";

// Create connection
$koneksi = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$koneksi) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($koneksi, "utf8");
?>