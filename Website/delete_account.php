<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "matrimonial");

if ($mysqli->connect_error) {
    die("<script>alert('Connection failed: " . $mysqli->connect_error . "');</script>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_password'])) {
    if (!isset($_SESSION['user_id'])) {
        die("<script>alert('Error: User not logged in.');</script>");
    }

    $user_id = $_SESSION['user_id'];
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('Error: All fields are required.');</script>";
    } elseif ($new_password !== $confirm_password) {
        echo "<script>alert('Error: New passwords do not match.');</script>";
    } else {
        // Fetch stored password from database
        $stmt = $mysqli->prepare("SELECT password FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            die("<script>alert('Error: User not found.');</script>");
        }

        $stmt->bind_result($stored_password);
        $stmt->fetch();
        $stmt->close();

        // Verify current password
        if (!password_verify($current_password, $stored_password)) {
            echo "<script>alert('Error: Current password is incorrect.');</script>";
        } else {
            // Hash new password and update database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Success: Password updated successfully!');</script>";
            } else {
                echo "<script>alert('Error: Failed to update password.');</script>";
            }
            $stmt->close();
        }
    }
}
$mysqli->close();
?>

<div id="deleteAccountModal" class="modal">
    <div class="modal-contents">
        <h2>Confirm Account Deletion</h2>
        <p>Enter your password to confirm.</p>
        <form id="deleteAccountForm" method="POST" action="delete_account.php">
            <label for="password">Password</label>
            <input type="password" id="deletePassword" name="password" class="form-input" required>
            
            <button type="submit" name="confirm_delete" class="btn confirm-delete-btn">Confirm Delete</button>
            <button type="button" id="cancelDeleteAccount" class="btn cancel-btn">Cancel</button>
        </form>
    </div>
</div>