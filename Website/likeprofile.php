<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in.";
    exit;
}

$liker_id = $_SESSION['user_id'];
$liked_id = $_POST['liked_id'] ?? 0;

// Prevent self-liking
if ($liker_id == $liked_id) {
    echo "You can't like your own profile.";
    exit;
}

// Check if already liked
$stmt = $conn->prepare("SELECT id FROM likes WHERE liker_id = ? AND liked_id = ?");
$stmt->bind_param("ii", $liker_id, $liked_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "You already liked this profile.";
} else {
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO likes (liker_id, liked_id, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $liker_id, $liked_id);
    if ($stmt->execute()) {
        echo "Profile liked successfully!";
    } else {
        echo "Failed to like profile.";
    }
}

$stmt->close();
$conn->close();
?>
