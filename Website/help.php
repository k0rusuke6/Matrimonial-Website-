<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrimony Homepage</title>
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

	.logo {
	
    position: absolute; /* Fix the logo in place */
    top: 20px; /* Adjust vertical positioning */
    left: 60px; /* Adjust horizontal positioning */
    z-index: 1000; /* Make sure the logo stays on top */
}

.logo img {
    height: 50px; /* Adjust the size of your logo */
}
 /* General Page Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}

/* Contact Container */
.contact-container {
    margin-left: 450px; /* Adjust according to navbar width */
	margin-top: 40px; /* Adds space from the top */
    padding: 40px;
    max-width: 800px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Headings */
.contact-container h1 {
    font-size: 28px;
    color: #333;
}

.contact-container p {
    font-size: 16px;
    color: #555;
    margin-bottom: 20px;
}

/* Contact Information Boxes */
.contact-box {
    background: #f1f1f1;
    padding: 15px;
    margin: 10px 0;
    border-radius: 8px;
    font-size: 18px;
}

.contact-box h3 {
    color: #e91e63;
    margin-bottom: 5px;
}

/* Form Styling */
form {
    margin-top: 20px;
    text-align: left;
}

label {
    display: block;
    font-weight: bold;
    margin: 10px 0 5px;
    color: #333;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

textarea {
    resize: none;
}

button {
    width: 100%;
    background: #e91e63;
    color: white;
    border: none;
    padding: 12px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: 0.3s;
}

button:hover {
    background: #d81b60;
}

/* Success Modal */
.modals {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    text-align: center;
    z-index: 1000;
}

.modal-contents h2 {
    color: #e91e63;
    margin-bottom: 10px;
}

.modals button {
    background: #e91e63;
    width: auto;
    padding: 10px 20px;
}

footer {
    margin-top: 30px;
    text-align: center;
    color: #666;
    font-size: 14px;
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

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .search-bar {
            flex: 1;
            max-width: 500px;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 10px 40px 10px 20px;
            border: 1px solid #ddd;
            border-radius: 25px;
            outline: none;
        }

        .header-icons {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-icon {
            position: relative;
            cursor: pointer;
        }

        .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--primary-color);
            color: white;
            padding: 2px 6px;
            border-radius: 50%;
            font-size: 12px;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        /* Content Sections */
        .content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-header {
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        .match-card {
            text-align: center;
        }

        .match-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 15px;
        }
		
		.message-box {
  width: 100%;
  max-width: 600px;
  background: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 15px;
  margin: 20px auto;
  font-family: sans-serif;
}

.chat-container {
  max-height: 400px;
  overflow-y: auto;
}

.chat-row {
  margin-bottom: 15px;
}

.user-message .bubble {
  background-color: #e0f7fa;
  padding: 10px;
  border-radius: 10px;
}

.admin-reply .bubble {
  background-color: #d1f5d3;
  padding: 10px;
  border-radius: 10px;
  margin-left: 30px;
}

.timestamp {
  font-size: 12px;
  color: gray;
  margin-top: 5px;
  display: block;
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
</li>
<li class="nav-item help" style="margin-top: auto;">
        <a href="help.php" class="nav-link">
            <i class="fas fa-question-circle"></i>Help / Contact Us
        </a>
    </li>
        </ul>
    </nav>

 <div class="contact-container">
        <h1>Contact Us</h1>
        <p>Feel free to reach out to us for any queries or support.</p>

        <div class="contact-box">
            <h3>Helpline Number</h3>
            <p>+91-123-456-7890</p>
        </div>

        <div class="contact-box">
            <h3>Email Support</h3>
            <p>support@matrimonialwebsite.com</p>
        </div>

        <form id="contactForm" action="database/contact_handler.php" method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <div class="modals" id="successModal">
        <div class="modal-contents">
            <h2>Thank You!</h2>
            <p>Your message has been received. We will get back to you shortly.</p>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>
	
	<?php

include 'db_connect.php'; // Replace with your DB connection file

$user_id = $_SESSION['user_id']; // Logged-in user

// Fetch all messages between user and admin
$sql = "SELECT message, reply, created_at FROM contact_messages WHERE user_id = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="message-box">
  <h3>Messages with Admin</h3>
  <div class="chat-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="chat-row user-message">
        <div class="bubble">User: <?= htmlspecialchars($row['message']) ?></div>
        <span class="timestamp"><?= date('Y-m-d h:i A', strtotime($row['created_at'])) ?></span>
      </div>

      <?php if (!empty($row['reply'])): ?>
        <div class="chat-row admin-reply">
          <div class="bubble">Admin: <?= htmlspecialchars($row['reply']) ?></div>
          <span class="timestamp"><?= date('Y-m-d h:i A', strtotime($row['created_at'])) ?></span>
        </div>
      <?php endif; ?>
    <?php endwhile; ?>
  </div>
</div>


    <footer>
        <p>&copy; 2024 Matrimonial Website. All Rights Reserved.</p>
    </footer>

  <script>
    const form = document.getElementById('contactForm');
    const modal = document.getElementById('successModal');

   form.addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    // Create FormData object to send the form data via AJAX
    const formData = new FormData(form);

    // Send the data to the server using fetch
    fetch('database/contact_handler.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log the response for debugging

            if (data.trim() === 'success') {
                // Show the modal if the submission is successful
                modal.style.display = 'flex';
            } else {
                alert('There was an error submitting the form. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error. Please try again.');
        });
});


    function closeModal() {
        modal.style.display = 'none';
        form.reset(); // Optional: Reset the form after closing the modal
    }
</script>





  <div class="logout-modal" id="logoutModal">
        <div class="modal-content">
            <p class="modal-text">Are you sure you want to log out?</p>
            <div class="modal-buttons">
                <button class="modal-btn confirm-btn" id="confirmLogout">Yes</button>
                <button class="modal-btn cancel-btn" id="cancelLogout">No</button>
            </div>
        </div>
	</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



 <script>
    document.addEventListener("DOMContentLoaded", function () {
        const logoutLink = document.querySelector('.logout-link');
        const logoutModal = document.getElementById('logoutModal');
        const cancelLogoutBtn = document.getElementById('cancelLogout');
        const confirmLogoutBtn = document.getElementById('confirmLogout');

        if (!logoutLink || !logoutModal || !cancelLogoutBtn || !confirmLogoutBtn) {
            console.error("Error: One or more logout modal elements are missing.");
            return;
        }

        logoutLink.addEventListener('click', function (e) {
            e.preventDefault();
            console.log("Logout link clicked"); // Debugging log
            logoutModal.style.display = 'flex';
        });

        cancelLogoutBtn.addEventListener('click', function () {
            console.log("Cancel logout clicked");
            logoutModal.style.display = 'none';
        });

        confirmLogoutBtn.addEventListener('click', function () {
            console.log("Confirm logout clicked");
            window.location.href = 'logout.php';
        });
    });
</script>
</body>
</html>