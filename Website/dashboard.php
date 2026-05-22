<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Prevent caching after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include 'db_connect.php'; // Database connection

$user_id = $_SESSION['user_id']; // Get logged-in user ID

// Fetch user data
$sql = "SELECT full_name, gender, dob, profile_picture, occupation FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "User not found!";
    exit();
}

$logged_in_gender = $user['gender']; 
$opposite_gender = ($logged_in_gender === 'Male') ? 'Female' : 'Male';

// Fetch recommended profiles (only opposite gender)
$recStmt = $conn->prepare("SELECT user_id, full_name, gender, dob, marital_status, country, state, qualification, occupation, profile_picture 
                           FROM users WHERE gender = ? ORDER BY RAND() LIMIT 5");
$recStmt->bind_param("s", $opposite_gender);
$recStmt->execute();
$result = $recStmt->get_result();

$recommendations = [];
while ($row = $result->fetch_assoc()) {
    // Calculate age from date of birth (dob)
    $row['dob'] = date_diff(date_create($row['dob']), date_create('today'))->y;
    $recommendations[] = $row;
}
$recStmt->close();

// Fetch notifications for the logged-in user
$query = "SELECT message FROM notifications WHERE receiver_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<div class='notifications-container'>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<p class='notification-item'>{$row['message']}</p>";
    }
} else {
    echo "<p>No new notifications</p>";
}
echo "</div>";

$stmt->close();

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
  /* Main Content */
        .main-content {
			padding-top: 80px;
            margin-left: 250px;
            padding: 40px;
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

.user-info {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

.user-infos {
    font-size: 16px;
    font-weight: bold;
    color: #333;
	
	/* recommendations Styles */
}.profile-card {
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
    width: 200px;
}

.profile-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
}

.view-profile-btn {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 15px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.view-profile-btn:hover {
    background: #0056b3;
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
    grid-template-columns: repeat(2, 1fr);	
    gap: 25px;
}

	        /* recommendation */

.recommended-section {
    grid-column: span 2; /* Makes it span both columns */
}

.card {
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.profile-pic {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-bottom: 10px;
}

#account-activity {
    list-style: none;
    padding: 0;
}

#account-activity li {
    padding: 8px 0;
    border-bottom: 1px solid #ddd;
}

.card-header {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
}

      .recommendation-section {
    width: 100%;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-header {
    font-size: 20px;
    font-weight: bold;
    margin-bottom: 15px;
}

.recommendation-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 profiles per row */
    gap: 15px; /* Space between profiles */
}

.recommendation-card {
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    text-align: center;
}


.recommendation-pic {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #ddd;
    margin-bottom: 10px;
}

.recommendation-name {
    font-size: 18px;
    font-weight: bold;
    margin: 5px 0;
}

.recommendation-age {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
}

.recommendation-btn {
    display: inline-block;
    padding: 8px 15px;
    background: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-size: 14px;
    font-weight: bold;
    transition: 0.3s;
}

.recommendation-btn:hover {
    background: #0056b3;
}

/* Profile Modal CSS */

/* Modal Background */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    align-items: center;
    justify-content: center;
}

/* Modal Content */
.modal-content {
    background-color: white;
    width: 90%;
    max-width: 400px;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    position: relative; /* To position the close button inside */
    animation: fadeIn 0.3s ease-in-out;
}

/* Close Button */
.close {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #333;
}

/* Profile Picture */
#modalProfilePic {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}

/* Profile Header (Name & Age) */
.profile-header h2 {
    font-size: 22px;
    margin: 5px 0;
}

.profile-header p {
    font-size: 16px;
    color: gray;
}

/* Profile Details */
.profile-details p {
    font-size: 16px;
    margin: 5px 0;
}

/* Buttons Section */
.profile-actions {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
    padding-top: 20px; /* Adds spacing between content and buttons */
}

/* Buttons */
.like-btn, .message-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s;
}

.like-btn {
    background: #ff4d4d;
    color: white;
}

.message-btn {
    background: #4da6ff;
    color: white;
}

.like-btn:hover {
    background: #cc0000;
}

.message-btn:hover {
    background: #0059b3;
}



/* Fade In Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Adjusted Buttons Display (Below the Modal) */
.buttons-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}


/* Responsive Modal */
@media (max-width: 500px) {
    .modal-content {
        width: 95%;
        padding: 15px;
    }
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

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
          <div class="search-bar" >
    <input type="text" class="search-input" id="searchInput" placeholder="Search matches..." onkeyup="searchMatches()">
    <div id="searchOptions" class="search-dropdown"></div>
</div>

<style>
.search-dropdown {
    position: absolute;
    background: #fff;
    border: 1px solid #ccc;
    max-height: 200px;
    overflow-y: auto;
    width: 300px;
    z-index: 1000;
    display: none; /* hidden by default */
}

.search-option {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
}

.search-option:hover {
    background-color: #f0f0f0;
}
</style>

<script>
function searchMatches() {
    const query = document.getElementById("searchInput").value.trim();
    const dropdown = document.getElementById("searchOptions");

    if (query.length > 0) {
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "search_matches.php?query=" + encodeURIComponent(query), true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                dropdown.innerHTML = xhr.responseText;
                dropdown.style.display = "block"; // show dropdown
            }
        };
        xhr.send();
    } else {
        dropdown.innerHTML = "";
        dropdown.style.display = "none"; // hide dropdown
    }
}
</script>


            <div class="header-icons">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </div>
<img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="User Profile" class="profile-icon">
            </div>
        </div>

        <!-- Content Sections -->
<div class="content-grid">
    <!-- User Profile Details -->
    <div class="card">
        <h3 class="card-header">User Profile</h3>
        <div class="profile-details">
            <img src="<?= htmlspecialchars($user['profile_picture']) ?>" alt="User Profile" class="profile-pic">
            <h4 class="user-info"><?= htmlspecialchars($user['full_name']) ?></h4>
            <h2 class="user-infos"><?= htmlspecialchars($user['occupation']) ?></h2>
        </div>
    </div>


<!-- Account Activity -->
<div class="card">
    <h3 class="card-header">Account Activity</h3>
    <ul id="account-activity">
        <li>Last Login:   
            <?php 
            if (isset($_SESSION['last_login']) && $_SESSION['last_login'] !== null) {
                echo date("F j, Y, g:i a", strtotime($_SESSION['last_login'])); 
            } else {
                echo "First-time login";
            }
            ?>
        </li>

        <?php
        include 'db_connect.php'; // Make sure DB connection is included
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("
            SELECT DISTINCT u.full_name 
            FROM likes l
            JOIN users u ON l.liker_id = u.user_id
            WHERE l.liked_id = ?
            ORDER BY l.created_at DESC
            LIMIT 5
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li><strong>" . htmlspecialchars($row['full_name']) . "</strong> liked your profile.</li>";
            }
        } else {
            echo "<li>No recent likes on your profile.</li>";
        }

        $conn->close();
        ?>
    </ul>
</div>


</div>
<!-- Recommended Profiles -->
<div class="card recommendation-section">
    <h3 class="card-header">Recommended Matches</h3>
    <div class="recommendation-grid">
        <?php foreach ($recommendations as $profile) { ?>
            <div class="recommendation-card">
                <img src="<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="Profile" class="recommendation-pic">
                <h4 class="recommendation-name"><?php echo htmlspecialchars($profile['full_name']); ?></h4>
                <p class="recommendation-age">Age: <?php echo htmlspecialchars($profile['dob']); ?></p>
                <a href="#" class="recommendation-btn" 
   onclick="showProfileModal(
        '<?php echo ucwords($profile['full_name']); ?>', 
       '<?php echo ucwords($profile['gender']); ?>', 
       '<?php echo htmlspecialchars($profile['dob']); ?>', 
       '<?php echo ucwords($profile['marital_status']); ?>', 
       '<?php echo ucwords($profile['country']); ?>', 
       '<?php echo ucwords($profile['state']); ?>', 
       '<?php echo ucwords($profile['qualification']); ?>', 
       '<?php echo ucwords($profile['occupation']); ?>', 
       '<?php echo $profile['profile_picture']; ?>'
   )">
   View Profile
</a>

            </div>
        <?php } ?>
    </div>
</div>



<!-- Profile Modal -->
<div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeProfileModal()">&times;</span>
        <div class="profile-header">
            <img id="modalProfilePic" src="" alt="Profile Picture">
            <h2 id="modalFullName"></h2>
            <p id="modalAge"></p>
        </div>
        <div class="profile-details">
            <p><strong>Gender:</strong> <span id="modalGender"></span></p>
            <p><strong>Marital Status:</strong> <span id="modalMaritalStatus"></span></p>
            <p><strong>Country:</strong> <span id="modalCountry"></span></p>
            <p><strong>State:</strong> <span id="modalState"></span></p>
            <p><strong>Qualification:</strong> <span id="modalQualification"></span></p>
            <p><strong>Occupation:</strong> <span id="modalOccupation"></span></p>
        </div>

        <!-- Action Buttons inside the modal content -->
        <div class="profile-actions">

  <button class="like-btn" 
onclick="alert('You liked this profile!')">    Like
</button>

    
    <button class="message-btn" onclick="messageUser()">Message</button>
</div>


<!-- logout Modal -->

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
function likeProfile(likerId, likedId) {
    if (!likerId || !likedId) {
        console.error("Invalid user IDs!");
        return;
    }

    fetch("like_profile.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `liker_id=${likerId}&liked_id=${likedId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("You liked this profile!");
        } else {
            alert(data.message || "Failed to like profile.");
        }
    })
    .catch(error => console.error("Error:", error));
}
</script>


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
	
	
	
	function showProfileModal(fullName, gender, dob, maritalStatus, country, state, qualification, occupation, profilePic) {
   
    // Set modal data
    document.getElementById("modalProfilePic").src = profilePic;
    document.getElementById("modalFullName").innerText = fullName;
    document.getElementById("modalAge").innerText = "Age: " + dob;
    document.getElementById("modalGender").innerText = gender;
    document.getElementById("modalMaritalStatus").innerText = maritalStatus;
    document.getElementById("modalCountry").innerText = country;
    document.getElementById("modalState").innerText = state;
    document.getElementById("modalQualification").innerText = qualification;
    document.getElementById("modalOccupation").innerText = occupation;

    // Show modal
    document.getElementById("profileModal").style.display = "flex";
}

function closeProfileModal() {
    document.getElementById("profileModal").style.display = "none";
}

</script>







</body>
</html>