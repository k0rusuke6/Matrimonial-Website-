<?php
include 'db_connect.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iis", $sender_id, $receiver_id, $message);

        if (mysqli_stmt_execute($stmt)) {
            echo "Message sent successfully";
        } else {
            echo "Error sending message.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
