<?php
$conn = new mysqli('localhost', 'root', '', 'matrimonial');

if ($conn->connect_error) {
    die(json_encode([]));
}

// Fetch messages where reply is NULL
$sql = "SELECT id, name, email, message, created_at FROM contact_messages WHERE reply IS NULL ORDER BY created_at DESC";
$result = $conn->query($sql);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
$conn->close();
?>
