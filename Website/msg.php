<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Message Reply System</title>
  <style>
    /* General Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', Arial, sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      background: linear-gradient(120deg, #f9f9f9, #e9e9e9);
      color: #333;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      padding: 20px 0;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .sidebar h1 {
      color: #fff;
      font-size: 20px;
      margin-bottom: 30px;
    }

    .menu-item {
      width: 100%;
      padding: 15px;
      color: #bdc3c7;
      text-decoration: none;
      font-size: 16px;
      display: flex;
      align-items: center;
      gap: 10px;
      transition: 0.3s;
    }

    .menu-item:hover {
      background-color: #34495e;
      color: #fff;
    }

    /* Main Content */
    .main-content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .section-title {
      font-size: 22px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid #f0f0f0;
    }

    th {
      background: #34495e;
      color: white;
    }

    button {
      padding: 8px 12px;
      border: none;
      background: #007bff;
      color: white;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #0056b3;
    }

    /* Reply Popup */
    .popup {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 400px;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .popup.active {
      display: block;
    }

    .popup textarea {
      width: 100%;
      height: 100px;
      margin-bottom: 15px;
      padding: 10px;
      font-size: 14px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .popup .close-btn {
      background: #dc3545;
    }

    .popup .close-btn:hover {
      background: #c82333;
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <!-- Sidebar -->
 <div class="sidebar">
    <h1>Admin Panel</h1>
    <a href="admin_dashboard.php" class="menu-item"><i>🏠</i> Dashboard</a>
    <a href="approve.php" class="menu-item"><i>✔️</i> Profile Approvals</a>
    <a href="msg.php" class="menu-item"><i>📨</i> Messages & Notifications</a>
    <a href="#" class="menu-item logout-btn" onclick="openLogoutModal()"><i>🚪</i> Logout</a>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h2>Logout Confirmation</h2>
        <p>Are you sure you want to log out?</p>
        <div class="modal-buttons">
            <button class="confirm-btn" onclick="logout()">Yes, Logout</button>
            <button class="cancel-btn" onclick="closeLogoutModal()">Cancel</button>
        </div>
    </div>
</div>

<style>
/* Modal Styling */
/* Modal Styling */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4); /* Dark overlay */
    align-items: center;
    justify-content: center;
}

/* Make sure the modal content is centered and styled properly */
.modal-content {
    background: white;
    width: 350px;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    text-align: center;
    animation: fadeIn 0.3s ease-in-out;
}

.modal h2 {
    margin-bottom: 10px;
    font-size: 22px;
    color: #333;
}

.modal p {
    font-size: 16px;
    color: #666;
    margin-bottom: 20px;
}

.modal-buttons {
    display: flex;
    justify-content: center;
    gap: 15px;
}

.confirm-btn {
    background: #dc3545; /* Red color for logout */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.confirm-btn:hover {
    background: #c82333;
}

.cancel-btn {
    background: #6c757d; /* Grey color for cancel */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: 0.3s;
}

.cancel-btn:hover {
    background: #5a6268;
}

/* Smooth Fade-in Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>

<script>
// Open Logout Modal
function openLogoutModal() {
    document.getElementById("logoutModal").style.display = "flex";
}

// Close Logout Modal
function closeLogoutModal() {
    document.getElementById("logoutModal").style.display = "none";
}

// Logout Function
function logout() {
    window.location.href = "admin_login.php"; // Redirect to login page
}
</script>

  <!-- Main Content -->
<div class="main-content">
    <div class="section-title">Messages</div>
    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Received At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="messageTable">
            <!-- Messages will be dynamically populated -->
        </tbody>
    </table>
</div>

<!-- Reply Popup -->
<div class="popup" id="replyPopup">
    <h3>Reply to User</h3>
    <textarea id="replyMessage" placeholder="Type your response here..."></textarea>
    <button id="sendReply">Send Reply</button>
    <button class="close-btn" onclick="closePopup()">Close</button>
</div>

<script>
let currentMessageId = null;
let currentUserEmail = null;

// Fetch messages from the database (Only Unanswered Messages)
function fetchMessages() {
    fetch("get_messages.php")
    .then(response => response.json())
    .then(messages => {
        const messageTable = document.getElementById("messageTable");
        messageTable.innerHTML = ""; // Clear existing data

        if (messages.length === 0) {
            messageTable.innerHTML = "<tr><td colspan='5'>No new messages.</td></tr>";
            return;
        }

        messages.forEach(msg => {
            const row = document.createElement("tr");
            row.id = `message-${msg.id}`; // Add unique ID for the row
            row.innerHTML = `
                <td>${msg.name}</td>
                <td>${msg.email}</td>
                <td>${msg.message}</td>
                <td>${msg.created_at}</td>
                <td><button onclick="openReply(${msg.id}, '${msg.email}')">Reply</button></td>
            `;
            messageTable.appendChild(row);
        });
    });
}

// Open Reply Popup
function openReply(id, email) {
    currentMessageId = id;
    currentUserEmail = email;
    document.getElementById("replyPopup").classList.add("active");
}

// Close Reply Popup
function closePopup() {
    document.getElementById("replyPopup").classList.remove("active");
    document.getElementById("replyMessage").value = "";
}

// Send Reply
document.getElementById("sendReply").addEventListener("click", function () {
    const reply = document.getElementById("replyMessage").value.trim();
    if (reply === "") {
        alert("Reply cannot be empty!");
        return;
    }

    // Send reply to the database
    fetch("send_reply.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `message_id=${currentMessageId}&email=${currentUserEmail}&reply=${encodeURIComponent(reply)}`
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closePopup();
        // Remove the replied message from the table
        document.getElementById(`message-${currentMessageId}`).remove();
    });
});

// Fetch messages on page load
fetchMessages();
</script>
</body>
</html>
