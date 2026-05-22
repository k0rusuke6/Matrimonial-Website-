<?php
$host = "localhost";  // Change if needed
$username = "root";   // Default XAMPP username
$password = "";       // Default XAMPP password (empty)
$dbname = "matrimonial";  

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
