<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'matrimonial');

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Check if user_id and status are set
if (isset($_POST['user_id']) && isset($_POST['status'])) {
    $user_id = intval($_POST['user_id']);
    $status = $_POST['status']; // Expected values: "approved", "pending", "rejected"

    // Validate status to prevent SQL injection
    $allowed_statuses = ["approved", "pending", "rejected"];
    if (!in_array($status, $allowed_statuses)) {
        echo json_encode(["success" => false, "message" => "Invalid status"]);
        exit;
    }

    // Update user status in the database
    $stmt = $conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $status, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profile status updated"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update status"]);
    }
    
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>
