<?php
$conn = new mysqli('localhost', 'root', '', 'matrimonial');

if ($conn->connect_error) {
    die("Database connection failed");
}

if (isset($_POST['message_id'], $_POST['email'], $_POST['reply'])) {
    $message_id = intval($_POST['message_id']);
    $email = $_POST['email'];
    $reply = $_POST['reply'];

    $stmt = $conn->prepare("UPDATE contact_messages SET reply = ? WHERE id = ?");
	$stmt->bind_param("si", $reply, $message_id);

    if ($stmt->execute()) {
        echo "Reply sent successfully!";
    } else {
        echo "Failed to send reply!";
    }
    $stmt->close();
} else {
    echo "Invalid request!";
}

$conn->close();
?>
