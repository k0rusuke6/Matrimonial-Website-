<?php
include 'db_connect.php'; // Database connection

$sender_id = $_GET['sender_id'];
$receiver_id = $_GET['receiver_id'];

$sql = "SELECT * FROM messages WHERE 
        (sender_id = ? AND receiver_id = ?) 
        OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY timestamp ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$messages = [];
while ($row = mysqli_fetch_assoc($result)) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
