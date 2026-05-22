<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $liker_id = $_POST['liker_id'];
    $liked_id = $_POST['liked_id'];

    if (!empty($liker_id) && !empty($liked_id)) {
        // Get liked user's name
        $stmt = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $liked_id);
        $stmt->execute();
        $stmt->bind_result($liked_name);
        $stmt->fetch();
        $stmt->close();

        // Insert like into database
        $stmt = $conn->prepare("INSERT INTO likes (liker_id, liked_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $liker_id, $liked_id);
        $stmt->execute();
        $stmt->close();

        // Send a notification
        $msg = "You liked $liked_name's profile.";
        $stmt2 = $conn->prepare("INSERT INTO notifications (receiver_id, message) VALUES (?, ?)");
        $stmt2->bind_param("is", $liker_id, $msg);
        $stmt2->execute();
        $stmt2->close();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Invalid data"]);
    }
}
?>
