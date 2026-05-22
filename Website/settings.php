<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$mysqli = new mysqli("localhost", "root", "", "matrimonial");

if ($mysqli->connect_error) {
    die("<script>alert('Connection failed: " . $mysqli->connect_error . "');</script>");
}

// Handle Password Update
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

        if (!password_verify($current_password, $stored_password)) {
            echo "<script>alert('Error: Current password is incorrect.');</script>";
        } else {
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

// Handle Account Deletion
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_delete'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Error: User not logged in.'); window.location.href = 'login.php';</script>";
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $password = trim($_POST['password']);

    $stmt = $mysqli->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo "<script>alert('Error: User not found.');</script>";
        $stmt->close();
        exit;
    }

    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($password, $hashed_password)) {
    echo "<script>alert('Error: Incorrect password. Account not deleted.'); window.location.href = 'settings.php';</script>";
    exit;
}


    $stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        session_destroy();
        echo "<script>
            alert('Success: Account deleted successfully.');
            window.location.href = 'login.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Error: Failed to delete account. Please try again later.');</script>";
        $stmt->close();
        exit;
    }
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary-color: #e91e63;
            --secondary-color: #2d2d2d;
            --bg-color: #f5f5f5;
        }

        * {
            margin: 0;
            padding: 4px;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--bg-color);
        }

	

	/* Custom Confirmation Modal Styles */
        .logout-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            justify-content: center;
            align-items: center;
        }
	.logo {
	
    position: absolute; /* Fix the logo in place */
    top: 20px; /* Adjust vertical positioning */
    left: 60px; /* Adjust horizontal positioning */
    z-index: 1000; /* Make sure the logo stays on top */
}

.logo img {
    height: 50px; /* Adjust the size of your logo */
}

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
        }

        .modal-text {
            color: #2d2d2d;
            font-size: 18px;
            margin-bottom: 25px;
            font-family: 'Segoe UI', sans-serif;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .modal-btn {
            padding: 10px 30px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .confirm-btn {
            background-color: #e91e63;
            color: white;
        }

        .confirm-btn:hover {
            background-color: #c2185b;
        }

        .cancel-btn {
            background-color: #f0f0f0;
            color: #2d2d2d;
        }

        .cancel-btn:hover {
            background-color: #e0e0e0;
        }

	 /* Modal Background */
.model {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1000; /* On top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    display: flex;
    align-items: center; /* Center vertically */
    justify-content: center; /* Center horizontally */
}

/* Modal Content */
.modal-contents {
    background: white;
    padding: 20px;
    width: 400px;
    max-width: 90%;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    text-align: center;
}


    .btn {
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        margin-top: 10px;
    }

    .confirm-delete-btn {
        background-color: red;
        color: white;
    }

    .cancel-btn {
        background-color: gray;
        color: white;
    }

        /* Side Navigation */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 250px;
            background: var(--secondary-color);
            padding: 20px;
            z-index: 1000;
        }

        .sidebar-header {
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

         .nav-menu {
            list-style: none;
			position: relative;
    min-height: 100vh; /* Ensures full height */
        }
				.nav-item.help {
    position: absolute;
    bottom: 60px;
    width: 100%;
    text-align: center;
}
        .nav-item {
            margin: 15px 0;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }
	
	.delete-warning {
    margin-top: 10px;
    color: #f44336;
    font-size: 14px;
    font-weight: bold;
}

        .nav-link:hover {
            background: var(--primary-color);
        }

        .nav-link.active {
            background: var(--primary-color);
        }

        .nav-link i {
            margin-right: 15px;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 40px;
        }

	.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.settings-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.settings-card h3 {
    margin-bottom: 15px;
    font-size: 18px;
    color: var(--secondary-color);
}

.form-input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn {
    background-color: var(--primary-color);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #c2185b;
}

.delete-btn {
    background-color: #f44336;
}

.delete-btn:hover {
    background-color: #d32f2f;
}

.success-box {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0; top: 0;
  width: 100%; height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
}

.success-content {
  background-color: #ffffff;
  margin: 12% auto;
  padding: 20px 30px;
  border-radius: 12px;
  width: 320px;
  box-shadow: 0 6px 12px rgba(0,0,0,0.25);
  text-align: center;
}

.success-close {
  float: right;
  font-size: 22px;
  font-weight: bold;
  cursor: pointer;
}


      
    </style>
</head>
<body>
    <!-- Side Navigation -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
    <a href="index.php">
        <img src="images/logo.ico" alt="Website Logo">
    </a>
</div>
        </div>
        <ul class="nav-menu">
		<li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="fas fa-home"></i>Dashboard</a></li>
            <li class="nav-item"><a href="profile.php" class="nav-link"><i class="fas fa-user"></i>Profile</a></li>
            <li class="nav-item"><a href="matches.php" class="nav-link"><i class="fas fa-heart"></i>Matches</a></li>
            <li class="nav-item"><a href="message.php" class="nav-link"><i class="fas fa-comments"></i>Messages</a></li>
            <li class="nav-item"><a href="settings.php" class="nav-link"><i class="fas fa-cog"></i>Settings</a></li>
            <li class="nav-item">
    <a href="#" class="nav-link logout-link">
        <i class="fas fa-sign-out-alt"></i>Logout
    </a>
		<li class="nav-item help" style="margin-top: auto;">
        <a href="help.php" class="nav-link">
            <i class="fas fa-question-circle"></i>Help / Contact Us
        </a>
    </li>

</li>
        </ul>
    </nav>
<div class="main-content">
    <h2>Settings</h2>

    <div class="settings-grid">
        <!-- Update Password Section -->
        <div class="settings-card">
            <h3>Update Password</h3>
	
            <form method="POST">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-input" required>
                
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" class="form-input" required>
                
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-input" required>
                
                 <button type="submit" class="btn" name="update_password">Update Password</button>
            </form>
        </div>

        <!-- Update Contact Section -->
        <div class="settings-card">
            <h3>Update Contact Information</h3>
            <form action="update_contact.php" method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-input" required>
                
                <label for="mobile">Mobile Number</label>
                <input type="text" id="mobile" name="mobile" class="form-input" required>
                
                <button type="submit" class="btn">Update Contact</button>
            </form>
        </div>
		

<div id="successBox" class="success-box">
  <div class="success-content">
    <span class="success-close">&times;</span>
    <h2>Success</h2>
    <p>Contact information updated successfully!</p>
  </div>
</div>
<script>
document.querySelector("form").addEventListener("submit", function(e) {
    const email = document.getElementById("email").value.trim();
    const mobile = document.getElementById("mobile").value.trim();

    // Basic validation
    if (!email.includes("@") || mobile.length < 10 || isNaN(mobile)) {
        alert("Please enter a valid email and a valid 10-digit mobile number.");
        e.preventDefault(); // Prevent form submission
    }
});
</script>
<script>
  // Show the box if update=success in URL
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('update') === 'success') {
    document.getElementById("successBox").style.display = "block";
  }

  // Close when clicking the X
  document.querySelector(".success-close").onclick = function() {
    document.getElementById("successBox").style.display = "none";
    window.history.replaceState({}, document.title, window.location.pathname); // remove ?update=success
  };

  // Optional: click outside box to close
  window.onclick = function(event) {
    const box = document.getElementById("successBox");
    if (event.target === box) {
      box.style.display = "none";
      window.history.replaceState({}, document.title, window.location.pathname);
    }
  };
</script>


        <!-- Account Deletion Section -->
        <div class="settings-card">
            <h3>Account Management</h3>
           
   <!-- Delete Account Button -->
<form method="POST">
    <button type="button" name="delete_account" id="deleteAccountBtn" class="btn delete-btn">Delete Account</button>
    <p class="delete-warning">
        Warning: Deleting your account is a permanent action and cannot be undone.
    </p>
</form>

<!-- Modal -->
<div id="deleteAccountModal" class="model" style="display: none;">
    <div class="modal-contents">
        <h2>Confirm Account Deletion</h2>
        <p>Enter your password to confirm.</p>
        <form id="deleteAccountForm" method="POST" action="settings.php">
            <label for="password">Password</label>
            <input type="password" id="deletePassword" name="password" class="form-input" required>
            
            <button type="submit" name="confirm_delete" class="btn confirm-delete-btn">Confirm Delete</button>
            <button type="button" id="cancelDeleteAccount" class="btn cancel-btn">Cancel</button>
        </form>
    </div>
</div>




<div class="logout-modal" id="logoutModal">
        <div class="modal-content">
            <p class="modal-text">Are you sure you want to log out?</p>
            <div class="modal-buttons">
                <button class="modal-btn confirm-btn" id="confirmLogout">Yes</button>
                <button class="modal-btn cancel-btn" id="cancelLogout">No</button>
            </div>
        </div>
	</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    // Logout Modal
    const logoutLink = document.querySelector('.logout-link');
    const logoutModal = document.getElementById('logoutModal');
    const cancelLogoutBtn = document.getElementById('cancelLogout');
    const confirmLogoutBtn = document.getElementById('confirmLogout');

    if (logoutLink && logoutModal) {
        // Show Logout Modal
        logoutLink.addEventListener('click', function (e) {
            e.preventDefault();
            logoutModal.style.display = 'flex';
        });

        // Hide Logout Modal on Cancel
        cancelLogoutBtn.addEventListener('click', function () {
            logoutModal.style.display = 'none';
        });

        // Redirect on Confirm Logout
        confirmLogoutBtn.addEventListener('click', function () {
            window.location.href = 'logout.php';
        });
    }

    // Delete Account Modal
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    const deleteAccountModal = document.getElementById('deleteAccountModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteAccount');

    if (deleteAccountBtn && deleteAccountModal) {
        // Show Delete Account Modal
        deleteAccountBtn.addEventListener('click', function (e) {
            e.preventDefault();
            deleteAccountModal.style.display = 'flex';
        });

        // Hide Delete Account Modal on Cancel
        cancelDeleteBtn.addEventListener('click', function () {
            deleteAccountModal.style.display = 'none';
        });

        // Hide Modal on Click Outside
        window.addEventListener('click', function (event) {
            if (event.target === deleteAccountModal) {
                deleteAccountModal.style.display = 'none';
            }
        });
    }
});


</script>


</body>
</html>