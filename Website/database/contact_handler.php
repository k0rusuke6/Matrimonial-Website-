<?php
session_start(); // Add this to access the session
include 'db_config.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO contact_messages (user_id, name, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $name, $email, $message);

    if ($stmt->execute()) {
        echo 'success'; // Operation successful
    } else {
        echo 'error'; // Database query failed
    }

    $stmt->close();
} else {
    echo 'not_logged_in'; // User not logged in, can't track who submitted
}

$conn->close();
?>
