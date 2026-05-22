s<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$current_user_id = $_SESSION['user_id'];


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

		/* Add these styles to your existing CSS */
.messages-container {
    display: flex;
    height: calc(100vh - 100px);
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.conversations-list {
    width: 350px;
    border-right: 1px solid #eee;
    padding: 20px;
}

.chat-window {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.conversation-item {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 10px;
    margin: 10px 0;
    cursor: pointer;
    transition: 0.3s;
}

.conversation-item:hover {
    background: #f5f5f5;
}

.conversation-item.active {
    background: var(--primary-color);
    color: white;
}

.conversation-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 15px;
}

.conversation-meta {
    margin-left: auto;
    text-align: right;
}

.unread-count {
    background: var(--primary-color);
    color: white;
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
}

.chat-messages {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
}

.message {
    max-width: 70%;
    margin-bottom: 15px;
}

.message.received {
    margin-right: auto;
}

.message.sent {
    margin-left: auto;
}

.message-content {
    background: #f0f0f0;
    padding: 12px 15px;
    border-radius: 15px;
    position: relative;
}

.message.sent .message-content {
    background: var(--primary-color);
    color: white;
}

.message-time {
    font-size: 12px;
    color: #666;
    margin-left: 10px;
}

.message-input {
    display: flex;
    padding: 20px;
    border-top: 1px solid #eee;
}

.message-input textarea {
    flex: 1;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 25px;
    resize: none;
    margin-right: 10px;
}

.send-btn {
    background: var(--primary-color);
    color: white;
    border: none;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    cursor: pointer;
}

.online-status {
    color: #4CAF50;
    font-size: 14px;
      
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


<div class="main-content">
    <div class="messages-container">
        <!-- Conversations List -->
        <div class="conversations-list">
            <div class="conversation-header">
                <h2>Messages</h2>
                <button class="new-chat-btn"><i class="fas fa-plus"></i> New Chat</button>
            </div>

            <!-- Fetch conversation list dynamically -->
            <?php
            include 'db_connect.php'; // Database connection
            $userId = $_SESSION['user_id'];
            $query = "SELECT u.user_id, u.full_name, u.profile_picture, 
                      (SELECT message FROM messages WHERE (sender_id = u.user_id OR receiver_id = u.user_id) 
                       AND (sender_id = $userId OR receiver_id = $userId) ORDER BY timestamp DESC LIMIT 1) AS last_message 
                      FROM users u 
                      WHERE u.user_id != $userId";
            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="conversation-item" onclick="loadChat('.$row['user_id'].')">
                        <img src="images/'.$row['profile_picture'].'" class="conversation-avatar" alt="'.$row['full_name'].'">
                        <div class="conversation-info">
                            <h4>'.$row['full_name'].'</h4>
                            <p class="last-message">'.($row['last_message'] ?: "No messages yet").'</p>
                        </div>
                    </div>';
            }
            ?>
        </div>

        <!-- Chat Window -->
        <div class="chat-window">
            <div class="chat-header">
                <div class="recipient-info">
                    <img id="chat-avatar" class="chat-avatar" src="images/default.jpg" alt="">
                    <div>
                        <h3 id="chat-name">Select a Chat</h3>
                        <p class="online-status">Online</p>
                    </div>
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="chat-messages"></div>

            <!-- Message Input -->
            <div class="message-input">
                <textarea id="message-text" placeholder="Type your message..." rows="1"></textarea>
                <button class="send-btn"><i class="fas fa-paper-plane"></i></button>
            </div>
        </div>
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
$(document).ready(function() {
    // Message input auto-resize
    $('.message-input textarea').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Send message on Enter
    $('.message-input textarea').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Send message on button click
    $('.send-btn').click(sendMessage);

    function sendMessage() {
        const message = $('.message-input textarea').val().trim();
        const senderId = <?php echo $_SESSION['user_id']; ?>; // Logged-in user
        const receiverId = <?php echo $_GET['receiver_id']; ?>; // Selected user

        if (message) {
            $.ajax({
                url: 'send_message.php',
                type: 'POST',
                data: { sender_id: senderId, receiver_id: receiverId, message: message },
                success: function(response) {
                    if (response === "success") {
                        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        const msgHtml = `
                            <div class="message sent">
                                <div class="message-content">
                                    <p>${message}</p>
                                    <span class="message-time">${time}</span>
                                </div>
                            </div>`;
                        $('.chat-messages').append(msgHtml);
                        $('.message-input textarea').val('');
                        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
                    } else {
                        alert("Message sending failed.");
                    }
                }
            });
        }
    }

    function loadMessages() {
        const senderId = <?php echo $_SESSION['user_id']; ?>;
        const receiverId = <?php echo $_GET['receiver_id']; ?>;

        $.ajax({
            url: 'fetch_messages.php',
            type: 'GET',
            data: { sender_id: senderId, receiver_id: receiverId },
            success: function(response) {
                $('.chat-messages').html(response);
                $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
            }
        });
    }

    setInterval(loadMessages, 2000); // Auto-refresh messages every 2 seconds
});

</script>
    
	
	
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