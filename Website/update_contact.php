<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $user_id = $_SESSION['user_id'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[0-9]{10,15}$/', $mobile)) {
        echo "Invalid input.";
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET email = ?, phone_number = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $email, $mobile, $user_id);

    if ($stmt->execute()) {
        // ✅ NO echo before this
        header("Location: settings.php?update=success");
        exit;
    } else {
        echo "Error updating contact info.";
    }

    $stmt->close();
    $conn->close();
}
?>
