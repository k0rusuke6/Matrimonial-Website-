<?php
session_start();
include 'db_connect.php';
$user_id = $_SESSION['user_id'];
$notifications = [];

// 1. Fetch notifications from "notifications" table
$stmt = $conn->prepare("SELECT message, created_at FROM notifications WHERE receiver_id = ? ORDER BY id DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $notifications[] = [
        'message' => $row['message'],
        'time' => strtotime($row['created_at'])
    ];
}
$stmt->close();

// 2. Fetch admin replies from "contact_messages" table (assuming created_at exists)
$reply_stmt = $conn->prepare("SELECT reply, created_at FROM contact_messages WHERE id = ? AND reply IS NOT NULL ORDER BY id DESC LIMIT 5");
$reply_stmt->bind_param("i", $id);
$reply_stmt->execute();
$reply_result = $reply_stmt->get_result();

while ($row = $reply_result->fetch_assoc()) {
    $notifications[] = [
        'message' => "Admin replied: " . $row['reply'],
        'time' => strtotime($row['created_at'])
    ];
}
$reply_stmt->close();

$conn->close();

// 3. Sort and display
usort($notifications, function($a, $b) {
    return $b['time'] - $a['time'];
});

foreach ($notifications as $note) {
    echo "<li><strong>" . date("M j, g:i a", $note['time']) . ":</strong> " . htmlspecialchars($note['message']) . "</li>";
}
?>
