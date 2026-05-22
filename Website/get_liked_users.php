<?php
session_start();
include 'db_connect.php';

$user_id = $_SESSION['user_id']; // Make sure this is set when user logs in

$sql = "SELECT DISTINCT u.user_id, u.full_name 
        FROM likes l
        JOIN users u ON l.liker_id = u.user_id
        WHERE l.liked_id = ?
        ORDER BY l.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $fullName = htmlspecialchars($row['full_name']);
        $likerId = (int)$row['user_id'];
        echo "<div style='margin-bottom: 10px;'>
                <strong>$fullName</strong> liked your profile.
                <a href='view_profile.php?user_id=$likerId' class='btn-view-profile'>View Profile</a>
              </div>";
    }
} else {
    echo "<p>No one has liked your profile yet.</p>";
}

$stmt->close();
$conn->close();
?>
