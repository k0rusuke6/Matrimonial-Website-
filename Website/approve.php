<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile Approval</title>
  <style>
 /* Profile Image Styling */
.profile-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    margin: 10px auto;
    display: block;
    border: 3px solid #ddd;
}

/* Modal Styling */
.modal-content {
    background: transparent; /* Removes white background */
    border: none; /* Removes border for a cleaner look */
    width: 90%;
    max-width: 450px; /* Keeps modal compact */
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5); /* Adds a subtle shadow */
    text-align: center;
    position: relative;
    max-height: 85vh;
    overflow-y: auto;
}

/* Close Button */
.close {
    position: absolute;
    top: 12px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
    color: #888;
    background: none; /* Removes any default background */
    border: none; /* Removes default border */
}

.close:hover {
    color: #FF5252;
}

/* Profile Title */
.modal-content h2 {
    font-size: 22px;
    color: #222; /* Darker text for better contrast */
    margin-bottom: 15px;
}

/* Profile Details */
.modal-content p {
    font-size: 14px;
    color: #333; /* Slightly darker gray for better readability */
    margin: 6px 0;
    padding: 4px 10px;
    background: rgba(0, 0, 0, 0.05); /* Transparent subtle background */
    border-radius: 6px;
    text-align: left;
}

/* Labels Styling */
.modal-content p::before {
    font-weight: bold;
    color: #111; /* Ensure labels stand out */
}

/* Adding Labels */
#modal-gender::before { content: "Gender: "; }
#modal-age::before { content: "Age: "; }
#modal-religion::before { content: "Religion: "; }
#modal-caste::before { content: "Caste: "; }
#modal-marital::before { content: "Marital Status: "; }
#modal-location::before { content: "Location: "; }
#modal-qualification::before { content: "Qualification: "; }
#modal-institution::before { content: "Institution: "; }
#modal-occupation::before { content: "Occupation: "; }
#modal-job::before { content: "Job Location: "; }
#modal-salary::before { content: "Salary Range: "; }
#modal-hobbies::before { content: "Hobbies: "; }
#modal-lifestyle::before { content: "Lifestyle: "; }

/* Button Container */
.modal-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

/* Buttons */
.approve, .reject {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    transition: 0.3s;
    margin: 0 5px;
    font-weight: bold;
}

.approve {
    background: linear-gradient(45deg, #4CAF50, #66BB6A); /* Gradient for a modern touch */
    color: white;
}

.reject {
    background: linear-gradient(45deg, #FF3D3D, #FF6F61); /* Gradient for a modern touch */
    color: white;
}

.approve:hover {
    background: #388E3C; /* Slightly darker green on hover */
}

.reject:hover {
    background: #D32F2F; /* Slightly darker red on hover */
}


    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', Arial, sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      background: linear-gradient(120deg, #f0f0f0, #e8e8e8);
      color: #333;
    }

    /* Sidebar Styles */
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
      text-transform: uppercase;
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

    .menu-item i {
      font-size: 18px;
    }

    /* Main Content Styles */
    .main-content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
    }

    .welcome {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .profile-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: white;
      padding: 20px;
      margin-bottom: 15px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-card .info {
      display: flex;
      flex-direction: column;
    }

    .profile-card .info h3 {
      margin-bottom: 10px;
    }

    .profile-card .actions button {
      margin-left: 10px;
      padding: 10px 15px;
      border: none;
      border-radius: 5px;
      font-size: 14px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .actions .approve {
      background-color: #28a745;
      color: white;
    }

    .actions .approve:hover {
      background-color: #218838;
    }

    .actions .reject {
      background-color: #dc3545;
      color: white;
    }

    .actions .reject:hover {
      background-color: #c82333;
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
    <div class="welcome">Profile Approval</div>

  <?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'matrimonial');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch pending profiles
$sql = "SELECT user_id, full_name,profile_picture, gender, dob, religion, caste, marital_status, country, state, city, qualification, institution, occupation, job_location, salary_range, identity_proof, lifestyle FROM users WHERE status = 'pending'";
$result = $conn->query($sql);
?>

<!-- Profile List -->
<div id="profile-list">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="profile-card">
                <div class="info">
                    <h3><?php echo htmlspecialchars($row['full_name']); ?></h3>
                    <p>Gender: <?php echo ucfirst($row['gender']); ?> | Age: <?php echo date_diff(date_create($row['dob']), date_create('today'))->y; ?></p>
                    <p>Location: <?php echo htmlspecialchars($row['city'] . ', ' . $row['state'] . ', ' . $row['country']); ?></p>
                </div>
                <div class="actions">
                    <button class="view" onclick="viewProfile(<?php echo $row['user_id']; ?>)">View Profile</button>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No pending profiles.</p>
    <?php endif; ?>
</div>

<!-- Profile Modal -->
<div id="profile-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modal-name"></h2>
		<img id="modal-profile-pic" src="" alt="Profile Picture" class="profile-image">
        <p id="modal-gender"></p>
        <p id="modal-age"></p>
        <p id="modal-religion"></p>
        <p id="modal-caste"></p>
        <p id="modal-marital"></p>
        <p id="modal-location"></p>
        <p id="modal-qualification"></p>
        <p id="modal-institution"></p>
        <p id="modal-occupation"></p>
        <p id="modal-job"></p>
        <p id="modal-salary"></p>
        <p id="modal-lifestyle"></p>
         <p id="modal-identity-proof"></p>
        <div class="modal-actions">
            <button class="approve" onclick="updateStatus('approved')">Approve</button>
            <button class="reject" onclick="updateStatus('rejected')">Reject</button>
        </div>
    </div>
</div>

<script>
let currentUserId = null;
function capitalizeFirstLetter(string) {
    if (typeof string !== 'string' || string.trim() === '') {
        return ''; // Return an empty string if input is invalid
    }
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function viewProfile(userId) {
    console.log("Fetching profile for user ID:", userId);

    fetch(`get_profile.php?user_id=${userId}`)
    .then(response => response.json())
    .then(data => {
        console.log("Fetched Data:", data);

        if (!data || Object.keys(data).length === 0) {
            alert("No profile data found!");
            return;
        }

        currentUserId = userId;
        document.getElementById('modal-name').innerText = capitalizeFirstLetter(data.full_name);
        document.getElementById('modal-gender').innerText = capitalizeFirstLetter(data.gender);
		document.getElementById('modal-age').innerText = data.age; // No need for capitalizeFirstLetter()
        document.getElementById('modal-religion').innerText = capitalizeFirstLetter(data.religion);
        document.getElementById('modal-caste').innerText = capitalizeFirstLetter(data.caste);
        document.getElementById('modal-marital').innerText = capitalizeFirstLetter(data.marital_status);
        document.getElementById('modal-location').innerText = 
            capitalizeFirstLetter(data.city) + ', ' + 
            capitalizeFirstLetter(data.state) + ', ' + 
            capitalizeFirstLetter(data.country);
        document.getElementById('modal-qualification').innerText = capitalizeFirstLetter(data.qualification);
        document.getElementById('modal-institution').innerText = capitalizeFirstLetter(data.institution);
        document.getElementById('modal-occupation').innerText = capitalizeFirstLetter(data.occupation);
        document.getElementById('modal-job').innerText = capitalizeFirstLetter(data.job_location);
        document.getElementById('modal-salary').innerText = capitalizeFirstLetter(data.salary_range);
        document.getElementById('modal-lifestyle').innerText = capitalizeFirstLetter(data.lifestyle);

        // Profile Picture
        const profilePicElement = document.getElementById('modal-profile-pic');
        if (data.profile_picture) {
            profilePicElement.src = data.profile_picture;
            profilePicElement.style.display = "block";
        } else {
            profilePicElement.style.display = "none";
        }

    


        // Identity Proof
        const identityProofContainer = document.getElementById('modal-identity-proof');
        if (data.identity_proof) {
            identityProofContainer.innerHTML = `<a href="${data.identity_proof}" target="_blank">View Identity Proof</a>`;
        } else {
            identityProofContainer.innerHTML = "No identity proof uploaded.";
        }

        document.getElementById('profile-modal').style.display = "block";
    })
    .catch(error => console.error("Error fetching profile:", error));
}



function updateStatus(status) {
    if (confirm(`Are you sure you want to mark this profile as ${status}?`)) {
        fetch('update_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `user_id=${currentUserId}&status=${encodeURIComponent(status)}`
        })
        .then(response => response.json()) // Expecting a JSON response
        .then(data => {
            if (data.success) {
                alert(`Profile status updated to ${status}`);
                document.getElementById('profile-modal').style.display = "none";
                location.reload();
            } else {
                alert("Failed to update status. Please try again.");
            }
        })
        .catch(error => console.error("Error updating status:", error));
    }
}


function closeModal() {
    document.getElementById('profile-modal').style.display = "none";
}
</script>

<style>
/* Modal Styling */
.modal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 10px; width: 400px; box-shadow: 0 0 10px rgba(0,0,0,0.2); }
.close { float: right; cursor: pointer; font-size: 20px; }
.modal-actions { margin-top: 10px; }
.approve, .reject { padding: 10px; margin-right: 10px; cursor: pointer; }
</style>

</body>
</html>
