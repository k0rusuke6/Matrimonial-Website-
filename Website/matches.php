<?php

session_start(); 
$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($currentUserId === null) {
    echo "Error: User ID is not set in the session.";
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Prevent Back Button from accessing after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

include 'db_connect.php'; // Include database connection

$matches = []; // Array to store filtered matches

// Check if any filter is applied
$filter_applied = isset($_GET['age_range']) || isset($_GET['gender']) || isset($_GET['religion']) ||
                  isset($_GET['caste']) || isset($_GET['state']) || isset($_GET['marital_status']) ||
                  isset($_GET['prefer_lifestyle']) || isset($_GET['salary_range']);

if ($filter_applied) { // Execute query only if a filter is selected
    // Retrieve filter inputs
    $age_range = $_GET['age_range'] ?? null;
    $gender = $_GET['gender'] ?? null;
    $religion = $_GET['religion'] ?? null;
    $caste = $_GET['caste'] ?? null;
    $state = $_GET['state'] ?? null;
    $marital_status = $_GET['marital_status'] ?? null;
    $prefer_lifestyle = $_GET['prefer_lifestyle'] ?? null;
    $salary_range = $_GET['salary_range'] ?? null;

    // Base query with conditions: status = "approved" & exclude current user
    $query = "SELECT user_id, full_name, gender, dob, marital_status, country, state, qualification, occupation, salary_range, profile_picture 
              FROM users WHERE status = 'approved' AND user_id != ?";
    
    // Prepare an array for query parameters
    $params = [$currentUserId]; // Exclude the logged-in user

    // Apply filters dynamically
    if ($age_range) {
        $ages = explode('-', $age_range);
        $query .= " AND dob BETWEEN DATE_SUB(CURDATE(), INTERVAL ? YEAR) AND DATE_SUB(CURDATE(), INTERVAL ? YEAR)";
        array_push($params, $ages[1], $ages[0]);
    }
    if ($gender) {
        $query .= " AND gender = ?";
        $params[] = $gender;
    }
    if ($religion) {
        $query .= " AND religion = ?";
        $params[] = $religion;
    }
    if ($caste) {
        $query .= " AND caste = ?";
        $params[] = $caste;
    }
    if ($state) {
        $query .= " AND state = ?";
        $params[] = $state;
    }
    if ($marital_status) {
        $query .= " AND marital_status = ?";
        $params[] = $marital_status;
    }
    if ($prefer_lifestyle) {
        $query .= " AND prefer_lifestyle = ?";
        $params[] = $prefer_lifestyle;
    }
    if ($salary_range) {
        $query .= " AND salary_range = ?";
        $params[] = $salary_range;
    }

    // Prepare statement to prevent SQL injection
    $stmt = mysqli_prepare($conn, $query);
    
    if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Set parameter types
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $matches = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    mysqli_stmt_close($stmt);
}

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

	.logo {
	
    position: absolute; /* Fix the logo in place */
    top: 20px; /* Adjust vertical positioning */
    left: 60px; /* Adjust horizontal positioning */
    z-index: 1000; /* Make sure the logo stays on top */
}

.logo img {
    height: 50px; /* Adjust the size of your logo */
}
/* View Profile modal*/
/* Button Styling */
.view-profile-btn {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-weight: bold;
}

.view-profile-btn:hover {
    background-color: #0056b3;
}

/* Popup Background Overlay */
.popup-overlay {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6); /* Dimmed overlay */
    align-items: center;
    justify-content: center;
    flex-direction: column; /* Align modal and buttons vertically */
}

/* Popup Box */
.popup-box {
    background-color: white;
    width: 90%;
    max-width: 400px;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    position: relative; /* To position the close button inside */
}

/* Close Button */
.popup-close {
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #333;
}

/* Profile Picture */
#popupProfilePic {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}

/* Profile Header */
.popup-header h2 {
    font-size: 22px;
    margin: 5px 0;
}

.popup-header p {
    font-size: 16px;
    color: gray;
}

/* Profile Details */
.popup-details p {
    font-size: 16px;
    margin: 5px 0;
}

/* Buttons Section */
.popup-actions {
    margin-top: 20px;
    display: flex;
    justify-content: center; /* Center buttons horizontally */
    gap: 10px; /* Space between buttons */
}

/* Button Styling for Actions */
.popup-like-btn, .popup-message-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.popup-like-btn {
    background: #ff4d4d;
    color: white;
}

.popup-message-btn {
    background: #4da6ff;
    color: white;
}

.popup-like-btn:hover {
    background: #cc0000;
}

.popup-message-btn:hover {
    background: #0059b3;
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
<!-- Main Content for Matches Page -->
<div class="main-content">
    <h1>Find Matches</h1>

    <!-- Filters Section -->
<div class="filter-section" style="margin-bottom: 30px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="margin-bottom: 20px;">Filters</h2>
    <form action="matches.php" method="GET" style="display: flex; flex-wrap: wrap; gap: 20px;">
        
       <!-- Age Range Filter -->
<div style="flex: 1; min-width: 200px;">
    <label for="age-range" style="display: block; margin-bottom: 8px;">Age Range</label>
    <select id="age-range" name="age_range" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
        <option disabled selected>Select age range</option>
        <option value="18-25">18-25</option>
        <option value="26-30">26-30</option>
        <option value="31-35">31-35</option>
        <option value="36-40">36-40</option>
        <option value="41-45">41-45</option>
        <option value="46-50">46-50</option>
        <option value="51-55">51-55</option>
        <option value="56-60">56-60</option>
    </select>
</div>


        <!-- Gender Filter -->
        <div style="flex: 1; min-width: 200px;">
            <label for="gender" style="display: block; margin-bottom: 8px;">Gender</label>
            <select id="gender" name="gender" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <!-- Religion Filter -->
        <div style="flex: 1; min-width: 200px;">
            <label for="religion" style="display: block; margin-bottom: 8px;">Religion</label>
            <select id="religion" name="religion" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option disabled selected>Select Religion</option>
                <option value="hinduism">Hinduism</option>
				<option value="islam">Islam</option>
				<option value="christianity">Christianity</option>
				<option value="sikhism">Sikhism</option>
				<option value="buddhism">Buddhism</option>
				<option value="jainism">Jainism</option>
				<option value="judaism">Judaism</option>
				<option value="zoroastrianism">Zoroastrianism</option>
				<option value="bahai">Bahá'í</option>
				<option value="shinto">Shinto</option>
	<option value="other">Other</option>
            </select>
        </div>

        <!-- Caste Filter -->
        <div style="flex: 1; min-width: 200px;">
            <label for="caste" style="display: block; margin-bottom: 8px;">Caste</label>
            <select id="caste" name="caste" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
			     <option disabled selected>Select Caste</option>
				<option value="General">General</option>
                <option value="OBC">OBC</option>
                <option value="SC">SC</option>
                <option value="ST">ST</option>
			</select>
        </div>

        <!-- State Filter -->
        <div style="flex: 1; min-width: 200px;">
            <label for="state" style="display: block; margin-bottom: 8px;">State</label>
            <select id="state" name="state" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
			<option disabled selected>Select State</option>
        <option value="andhra-pradesh">Andhra Pradesh</option>
        <option value="arunachal-pradesh">Arunachal Pradesh</option>
        <option value="assam">Assam</option>
        <option value="bihar">Bihar</option>
        <option value="chhattisgarh">Chhattisgarh</option>
        <option value="goa">Goa</option>
        <option value="gujarat">Gujarat</option>
        <option value="haryana">Haryana</option>
        <option value="himachal-pradesh">Himachal Pradesh</option>
        <option value="jharkhand">Jharkhand</option>
        <option value="karnataka">Karnataka</option>
        <option value="kerala">Kerala</option>
        <option value="madhya-pradesh">Madhya Pradesh</option>
        <option value="maharashtra">Maharashtra</option>
        <option value="manipur">Manipur</option>
        <option value="meghalaya">Meghalaya</option>
        <option value="mizoram">Mizoram</option>
        <option value="nagaland">Nagaland</option>
        <option value="odisha">Odisha</option>
        <option value="punjab">Punjab</option>
        <option value="rajasthan">Rajasthan</option>
        <option value="sikkim">Sikkim</option>
        <option value="tamil-nadu">Tamil Nadu</option>
        <option value="telangana">Telangana</option>
        <option value="tripura">Tripura</option>
        <option value="uttar-pradesh">Uttar Pradesh</option>
        <option value="uttarakhand">Uttarakhand</option>
        <option value="west-bengal">West Bengal</option>
        <option value="andaman-nicobar">Andaman and Nicobar Islands</option>
        <option value="chandigarh">Chandigarh</option>
        <option value="dadra-nagar-haveli">Dadra and Nagar Haveli</option>
        <option value="daman-diu">Daman and Diu</option>
        <option value="delhi">Delhi</option>
        <option value="lakshadweep">Lakshadweep</option>
        <option value="puducherry">Puducherry</option>
        <option value="ladakh">Ladakh</option>
        <option value="jammu-kashmir">Jammu and Kashmir</option>
		</select>
        </div>

        <!-- Marital Status Filter -->
        <div style="flex: 1; min-width: 200px;">
            <label for="marital_status" style="display: block; margin-bottom: 8px;">Marital Status</label>
            <select id="marital_status" name="marital_status" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <option disabled selected>Select Marital Status</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
            </select>
        </div>
		
		<!-- Lifestyle Choices Filter -->
<div style="flex: 1; min-width: 200px;">
    <label for="lifestyle" style="display: block; margin-bottom: 8px;">Lifestyle Choices</label>
    <select id="lifestyle" name="prefer_lifestyle" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
        <option disabled selected>Select lifestyle choices</option>
        <option value="vegetarian">Vegetarian</option>
        <option value="non-vegetarian">Non-Vegetarian</option>
        <option value="vegan">Vegan</option>
        <option value="smoker">Smoker</option>
        <option value="non-smoker">Non-Smoker</option>
        <option value="drinker">Drinker</option>
        <option value="non-drinker">Non-Drinker</option>
    </select>
</div>

 <div class="input-field">
      <label for="lifestyle" style="display: block; margin-bottom: 8px;">Salary Range</label>
    <select id="salary_range" name="salary_range" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            <option disabled selected>Select your salary range</option>
            <option value="less-5-lakhs">Less than 5 Lakhs</option>
            <option value="5-7.5-lakhs">5 to 7.5 Lakhs</option>
            <option value="7.5-10-lakhs">7.5 to 10 Lakhs</option>
            <option value="10-12.5-lakhs">10 to 12.5 Lakhs</option>
            <option value="12.5-15-lakhs">12.5 to 15 Lakhs</option>
            <option value="15-17.5-lakhs">15 to 17.5 Lakhs</option>
            <option value="17.5-20-lakhs">17.5 to 20 Lakhs</option>
            <option value="more-20-lakhs">More than 20 Lakhs</option>
        </select>
    </div>

        <div style="flex: 1; min-width: 200px; align-self: flex-end;">
            <button type="submit" style="background: var(--primary-color); color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Apply Filters</button>
        </div>
    </form>
</div>

   


<div class="matches-list" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
    <h2 style="margin-bottom: 20px;">Your Matches</h2>

    <?php if (!empty($matches)) { ?>
        <?php foreach ($matches as $profile) { ?>
            <div class="match-card" style="display: flex; align-items: center; padding: 15px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px;">
                <img src="<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="User" style="width: 80px; height: 80px; border-radius: 50%; margin-right: 20px;">
                <div>
                    <h3 style="margin: 0;"><?php echo htmlspecialchars($profile['full_name']); ?></h3>
                    <p style="margin: 5px 0;">
                        Age: <span class="age" data-dob="<?php echo htmlspecialchars($profile['dob']); ?>"></span> | 
                        State: <?php echo ucwords($profile['state']); ?>
                    </p>
                    
                    <!-- Recommendation Button -->
                    <button class="view-profile-btn" 
                            onclick="showProfilePopup(
                                '<?php echo ucwords($profile['full_name']); ?>', 
                                '<?php echo ucwords($profile['gender']); ?>', 
                                 '<?php echo addslashes((string)$profile['dob']); ?>', 
                                '<?php echo ucwords($profile['marital_status']); ?>', 
                                '<?php echo ucwords($profile['country']); ?>', 
                                '<?php echo ucwords($profile['state']); ?>', 
                                '<?php echo ucwords($profile['qualification']); ?>', 
                                '<?php echo ucwords($profile['occupation']); ?>',
                                '<?php echo ucwords($profile['salary_range']); ?>',
                                '<?php echo $profile['profile_picture']; ?>'
                            )">
                        View Profile
                    </button>

<!-- Popup Modal -->
<div id="profilePopup" class="popup-overlay">
    <div class="popup-box">
        <span class="popup-close" onclick="closeProfilePopup()">&times;</span>
        <div class="popup-header">
            <img id="popupProfilePic" src="" alt="Profile Picture">
            <h2 id="popupFullName"></h2>
            <p id="popupAge"></p>
        </div>
        <div class="popup-details">
            <p><strong>Gender:</strong> <span id="popupGender"></span></p>
            <p><strong>Marital Status:</strong> <span id="popupMaritalStatus"></span></p>
            <p><strong>Country:</strong> <span id="popupCountry"></span></p>
            <p><strong>State:</strong> <span id="popupState"></span></p>
            <p><strong>Qualification:</strong> <span id="popupQualification"></span></p>
            <p><strong>Occupation:</strong> <span id="popupOccupation"></span></p>
            <p><strong>Salary Range:</strong> <span id="popupSalaryRange"></span></p>
        </div>
    </div>


    <!-- Action Buttons -->
    <div class="popup-actions">



      <button class="popup-like-btn" 
    onclick="likeProfile(<?php echo (int)$currentUserId; ?>, <?php echo (int)$profile['user_id']; ?>)">
    Like
</button>



       <!-- Ye line tab likhni chahiye jab tum loop me har user ka profile dikha rahe ho -->
<!-- Inside your PHP loop -->
<button class="popup-message-btn" onclick="messageUser(<?php echo htmlspecialchars($row['user_id']); ?>)">Message</button>

<!-- JavaScript function (only once on the page, not inside the loop) -->
<script>
  function messageUser(receiverId) {
    if (receiverId) {
      window.location.href = "message.php?receiver_id=" + receiverId;
    } else {
      alert("User ID not found");
    }
  }
</script>



    </div>
</div>

                </div>
            </div>
      
        
     <?php } ?>
    <?php } else { ?>
        <p>No matches found based on your filters.</p>
    <?php } ?>

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
	
	
	function showProfilePopup(fullName, gender, dob, maritalStatus, country, state, qualification, occupation, salaryRange, profilePic) {
    console.log("Popup Function Triggered! DOB:", dob); // Debugging Step

    // Check if dob is valid
    if (!dob || typeof dob !== "string") {
        console.error("Invalid DOB received:", dob);
        dob = "0000-00-00"; // Default value to prevent errors
    }

    // Ensure dob is a properly formatted string
    let formattedDOB = dob.toString().replace(/\//g, "-"); 

    // Populate modal fields
    document.getElementById("popupProfilePic").src = profilePic;
    document.getElementById("popupFullName").innerText = fullName;
    document.getElementById("popupAge").innerText = "Age: " + calculateAge(formattedDOB);
    document.getElementById("popupGender").innerText = gender;
    document.getElementById("popupMaritalStatus").innerText = maritalStatus;
    document.getElementById("popupCountry").innerText = country;
    document.getElementById("popupState").innerText = state;
    document.getElementById("popupQualification").innerText = qualification;
    document.getElementById("popupOccupation").innerText = occupation;
    document.getElementById("popupSalaryRange").innerText = salaryRange;

    // Show the popup
    document.getElementById("profilePopup").style.display = "flex";
}


// Close the popup
function closeProfilePopup() {
    document.getElementById("profilePopup").style.display = "none";
}

// Helper function to calculate age
 function calculateAge(dob) {
        const birthDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    // Update age in profile cards
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.age').forEach(function(element) {
            const dob = element.getAttribute('data-dob');
            if (dob) {
                element.textContent = calculateAge(dob);
            } else {
                element.textContent = "N/A";
            }
        });
    });

	
function likeProfile(likerId, likedId) {
    if (!likerId || !likedId) {
        console.error("Invalid user IDs!");
        return;
    }

    // AJAX Request to PHP
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
            alert("Failed to like profile.");
        }
    })
    .catch(error => console.error("Error:", error));
}



</script>

</body>
</html>