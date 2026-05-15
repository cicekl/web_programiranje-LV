<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "lv4_filmovi";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Greška pri spajanju na bazu: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>