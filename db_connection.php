<?php
$servername = "localhost";
$username = "root";  // This is typically the default username for XAMPP
$password = "";      // XAMPP usually has no password by default
$dbname = "questlife";  // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
