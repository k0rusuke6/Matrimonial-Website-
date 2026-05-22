<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'db_connect.php'; // Connect to DB

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user's ID

// Fetch user data
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found!";
    exit();
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

	.profile-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .profile-header h1 {
            font-size: 24px;
            font-weight: bold;
        }
        .edit-profile-btn {
            background: #ff3366;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .edit-profile-btn:hover {
            background: #e62e59;
        }
        .profile-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .profile-details div {
            rgba(0, 0, 0, 0.1);
		width:350px;
        }
        .profile-details label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
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

        /* Profile Form */
        .profile-form {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 800px;
            position: relative;
        }
.form-container {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two columns */
    gap: 20px; /* Space between fields */
    background: none; /* Remove background effect */
    box-shadow: none; /* Remove shadow */
    border: none; /* Remove border */
}
        .form-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .form-row {
            display: flex;
            gap: 30px;
            margin-bottom: 25px;
        }

.form-group {
    display: flex;
    flex-direction: column;
    padding: 0; /* Remove padding around fields */
    background: none; /* Remove background effect */
    box-shadow: none; /* Remove shadow */
}	

      


        label {
            display: block;
            margin-bottom: 8px;
            color: var(--secondary-color);
            font-weight: 600;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #f9f9f9;
            transition: 0.3s;
        }

        .form-input:disabled {
            background: #f0f0f0;
            color: #666;
        }

        .edit-btn {
            position: absolute;
            top: 30px;
            right: 40px;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            cursor: pointer;
            transition: 0.3s;
        }

        .edit-btn:hover {
            background: #c2185b;
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
   
	<form id="userForm" method="POST" action="updateProfile.php">

            <div class="profile-container">
        <div class="profile-header">
            <h1>User Profile</h1>
<button class="edit-profile-btn" id="editProfile">Edit Profile</button>
        </div>
        <div class="profile-details">
            
	<div class="form-container">
    <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" class="form-input" disabled>
    </div>

    <div class="form-group">
        <label for="religion">Religion</label>
<select id="religion" name="religion" class="form-input" disabled>
    <option disabled selected>Select your religion</option>
    <option value="hinduism" <?= ($user['religion'] == 'hinduism') ? 'selected' : '' ?>>Hinduism</option>
    <option value="islam" <?= ($user['religion'] == 'islam') ? 'selected' : '' ?>>Islam</option>
    <option value="christianity" <?= ($user['religion'] == 'christianity') ? 'selected' : '' ?>>Christianity</option>
    <option value="sikhism" <?= ($user['religion'] == 'sikhism') ? 'selected' : '' ?>>Sikhism</option>
    <option value="buddhism" <?= ($user['religion'] == 'buddhism') ? 'selected' : '' ?>>Buddhism</option>
    <option value="jainism" <?= ($user['religion'] == 'jainism') ? 'selected' : '' ?>>Jainism</option>
    <option value="judaism" <?= ($user['religion'] == 'judaism') ? 'selected' : '' ?>>Judaism</option>
    <option value="zoroastrianism" <?= ($user['religion'] == 'zoroastrianism') ? 'selected' : '' ?>>Zoroastrianism</option>
    <option value="bahai" <?= ($user['religion'] == 'bahai') ? 'selected' : '' ?>>Bahá'í</option>
    <option value="shinto" <?= ($user['religion'] == 'shinto') ? 'selected' : '' ?>>Shinto</option>
    <option value="other" <?= ($user['religion'] == 'other') ? 'selected' : '' ?>>Other</option>
</select>

    </div>

    <div class="form-group">
        <label for="caste">Caste</label>
<select id="caste" name="caste" class="form-input" disabled >
    <option disabled selected>Select your caste</option>
    <option value="general" <?= ($user['caste'] == 'general') ? 'selected' : '' ?>>General</option>
    <option value="obc" <?= ($user['caste'] == 'obc') ? 'selected' : '' ?>>OBC</option>
    <option value="sc" <?= ($user['caste'] == 'sc') ? 'selected' : '' ?>>SC</option>
    <option value="st" <?= ($user['caste'] == 'st') ? 'selected' : '' ?>>ST</option>
    <option value="other" <?= ($user['caste'] == 'other') ? 'selected' : '' ?>>Other</option>
</select>

    </div>

    <div class="form-group">
        <label for="marital_status">Marital Status</label>
        <select id="marital_status" name="marital_status" class="form-input" disabled>
            <option value="Single" <?= $user['marital_status'] == 'Single' ? 'selected' : '' ?>>Single</option>
            <option value="Married" <?= $user['marital_status'] == 'Married' ? 'selected' : '' ?>>Married</option>
            <option value="Divorced" <?= $user['marital_status'] == 'Divorced' ? 'selected' : '' ?>>Divorced</option>
            <option value="Widowed" <?= $user['marital_status'] == 'Widowed' ? 'selected' : '' ?>>Widowed</option>
		 <option value="Separated" <?= $user['marital_status'] == 'Separated' ? 'selected' : '' ?>>Separated</option>
        </select>
    </div>


    <div class="form-group">
        <label for="country">Country</label>
<select id="country" name="country" class="form-input" disabled>
    <option disabled selected>Select Country</option>
    <option value="Indian" <?= ($user['country'] == 'Indian') ? 'selected' : '' ?>>Indian</option>
    <option value="NRI" <?= ($user['country'] == 'NRI') ? 'selected' : '' ?>>NRI</option>
</select>

    </div>

    <div class="form-group">
       <label for="state">State</label>
<select id="state" name="state" class="form-input" disabled>
    <option disabled selected>Select your state</option>
    <option value="andhra-pradesh" <?= ($user['state'] == 'andhra-pradesh') ? 'selected' : '' ?>>Andhra Pradesh</option>
    <option value="arunachal-pradesh" <?= ($user['state'] == 'arunachal-pradesh') ? 'selected' : '' ?>>Arunachal Pradesh</option>
    <option value="assam" <?= ($user['state'] == 'assam') ? 'selected' : '' ?>>Assam</option>
    <option value="bihar" <?= ($user['state'] == 'bihar') ? 'selected' : '' ?>>Bihar</option>
    <option value="chhattisgarh" <?= ($user['state'] == 'chhattisgarh') ? 'selected' : '' ?>>Chhattisgarh</option>
    <option value="goa" <?= ($user['state'] == 'goa') ? 'selected' : '' ?>>Goa</option>
    <option value="gujarat" <?= ($user['state'] == 'gujarat') ? 'selected' : '' ?>>Gujarat</option>
    <option value="haryana" <?= ($user['state'] == 'haryana') ? 'selected' : '' ?>>Haryana</option>
    <option value="himachal-pradesh" <?= ($user['state'] == 'himachal-pradesh') ? 'selected' : '' ?>>Himachal Pradesh</option>
    <option value="jharkhand" <?= ($user['state'] == 'jharkhand') ? 'selected' : '' ?>>Jharkhand</option>
    <option value="karnataka" <?= ($user['state'] == 'karnataka') ? 'selected' : '' ?>>Karnataka</option>
    <option value="kerala" <?= ($user['state'] == 'kerala') ? 'selected' : '' ?>>Kerala</option>
    <option value="madhya-pradesh" <?= ($user['state'] == 'madhya-pradesh') ? 'selected' : '' ?>>Madhya Pradesh</option>
    <option value="maharashtra" <?= ($user['state'] == 'maharashtra') ? 'selected' : '' ?>>Maharashtra</option>
    <option value="manipur" <?= ($user['state'] == 'manipur') ? 'selected' : '' ?>>Manipur</option>
    <option value="meghalaya" <?= ($user['state'] == 'meghalaya') ? 'selected' : '' ?>>Meghalaya</option>
    <option value="mizoram" <?= ($user['state'] == 'mizoram') ? 'selected' : '' ?>>Mizoram</option>
    <option value="nagaland" <?= ($user['state'] == 'nagaland') ? 'selected' : '' ?>>Nagaland</option>
    <option value="odisha" <?= ($user['state'] == 'odisha') ? 'selected' : '' ?>>Odisha</option>
    <option value="punjab" <?= ($user['state'] == 'punjab') ? 'selected' : '' ?>>Punjab</option>
    <option value="rajasthan" <?= ($user['state'] == 'rajasthan') ? 'selected' : '' ?>>Rajasthan</option>
    <option value="sikkim" <?= ($user['state'] == 'sikkim') ? 'selected' : '' ?>>Sikkim</option>
    <option value="tamil-nadu" <?= ($user['state'] == 'tamil-nadu') ? 'selected' : '' ?>>Tamil Nadu</option>
    <option value="telangana" <?= ($user['state'] == 'telangana') ? 'selected' : '' ?>>Telangana</option>
    <option value="tripura" <?= ($user['state'] == 'tripura') ? 'selected' : '' ?>>Tripura</option>
    <option value="uttar-pradesh" <?= ($user['state'] == 'uttar-pradesh') ? 'selected' : '' ?>>Uttar Pradesh</option>
    <option value="uttarakhand" <?= ($user['state'] == 'uttarakhand') ? 'selected' : '' ?>>Uttarakhand</option>
    <option value="west-bengal" <?= ($user['state'] == 'west-bengal') ? 'selected' : '' ?>>West Bengal</option>
    <option value="andaman-nicobar" <?= ($user['state'] == 'andaman-nicobar') ? 'selected' : '' ?>>Andaman and Nicobar Islands</option>
    <option value="chandigarh" <?= ($user['state'] == 'chandigarh') ? 'selected' : '' ?>>Chandigarh</option>
    <option value="dadra-nagar-haveli" <?= ($user['state'] == 'dadra-nagar-haveli') ? 'selected' : '' ?>>Dadra and Nagar Haveli</option>
    <option value="daman-diu" <?= ($user['state'] == 'daman-diu') ? 'selected' : '' ?>>Daman and Diu</option>
    <option value="delhi" <?= ($user['state'] == 'delhi') ? 'selected' : '' ?>>Delhi</option>
    <option value="lakshadweep" <?= ($user['state'] == 'lakshadweep') ? 'selected' : '' ?>>Lakshadweep</option>
    <option value="puducherry" <?= ($user['state'] == 'puducherry') ? 'selected' : '' ?>>Puducherry</option>
    <option value="ladakh" <?= ($user['state'] == 'ladakh') ? 'selected' : '' ?>>Ladakh</option>
    <option value="jammu-kashmir" <?= ($user['state'] == 'jammu-kashmir') ? 'selected' : '' ?>>Jammu and Kashmir</option>
</select>

    </div>

    <div class="form-group">
        <label for="city">City</label>
        <input type="text" id="city" name="city" value="<?= htmlspecialchars($user['city']) ?>" class="form-input" disabled>
    </div>



    <div class="form-group">
        <label for="qualification">Highest Qualification</label>
<select id="qualification" name="qualification" class="form-input" disabled>
    <option disabled selected>Select your qualification</option>
    <option value="bachelors" <?= ($user['qualification'] == 'bachelors') ? 'selected' : '' ?>>Bachelor's</option>
    <option value="masters" <?= ($user['qualification'] == 'masters') ? 'selected' : '' ?>>Master's</option>
    <option value="phd" <?= ($user['qualification'] == 'phd') ? 'selected' : '' ?>>PhD</option>
    <option value="diploma" <?= ($user['qualification'] == 'diploma') ? 'selected' : '' ?>>Diploma</option>
    <option value="highschool" <?= ($user['qualification'] == 'highschool') ? 'selected' : '' ?>>High School</option>
    <option value="other" <?= ($user['qualification'] == 'other') ? 'selected' : '' ?>>Other</option>
</select>

    </div>

    <div class="form-group">
        <label for="occupation">Occupation</label>
        <input type="text" id="occupation" name="occupation" value="<?= htmlspecialchars($user['occupation']) ?>" class="form-input" disabled>
    </div>

    <div class="form-group">
        <label for="job_location">Job Location</label>
        <input type="text" id="job_location" name="job_location" value="<?= htmlspecialchars($user['job_location']) ?>" class="form-input" disabled>
    </div>

    <div class="form-group">
       
 <label for="salary_range">Salary Range</label>
<select id="salary_range" name="salary_range" class="form-input" disabled>
    <option disabled selected>Select your salary range</option>
    <option value="less-5-lakhs" <?= ($user['salary_range'] == 'less-5-lakhs') ? 'selected' : '' ?>>Less than 5 Lakhs</option>
    <option value="5-7.5-lakhs" <?= ($user['salary_range'] == '5-7.5-lakhs') ? 'selected' : '' ?>>5 to 7.5 Lakhs</option>
    <option value="7.5-10-lakhs" <?= ($user['salary_range'] == '7.5-10-lakhs') ? 'selected' : '' ?>>7.5 to 10 Lakhs</option>
    <option value="10-12.5-lakhs" <?= ($user['salary_range'] == '10-12.5-lakhs') ? 'selected' : '' ?>>10 to 12.5 Lakhs</option>
    <option value="12.5-15-lakhs" <?= ($user['salary_range'] == '12.5-15-lakhs') ? 'selected' : '' ?>>12.5 to 15 Lakhs</option>
    <option value="15-17.5-lakhs" <?= ($user['salary_range'] == '15-17.5-lakhs') ? 'selected' : '' ?>>15 to 17.5 Lakhs</option>
    <option value="17.5-20-lakhs" <?= ($user['salary_range'] == '17.5-20-lakhs') ? 'selected' : '' ?>>17.5 to 20 Lakhs</option>
    <option value="more-20-lakhs" <?= ($user['salary_range'] == 'more-20-lakhs') ? 'selected' : '' ?>>More than 20 Lakhs</option>
</select>

    </div>

  

    <div class="form-group">
        <label for="lifestyle">Lifestyle Choices</label>
<select id="lifestyle" name="lifestyle" class="form-input" disabled>
    <option disabled selected>Select your lifestyle choices</option>
    <option value="vegetarian" <?= ($user['lifestyle'] == 'vegetarian') ? 'selected' : '' ?>>Vegetarian</option>
    <option value="non-vegetarian" <?= ($user['lifestyle'] == 'non-vegetarian') ? 'selected' : '' ?>>Non-Vegetarian</option>
    <option value="vegan" <?= ($user['lifestyle'] == 'vegan') ? 'selected' : '' ?>>Vegan</option>
    <option value="smoker" <?= ($user['lifestyle'] == 'smoker') ? 'selected' : '' ?>>Smoker</option>
    <option value="non-smoker" <?= ($user['lifestyle'] == 'non-smoker') ? 'selected' : '' ?>>Non-Smoker</option>
    <option value="drinker" <?= ($user['lifestyle'] == 'drinker') ? 'selected' : '' ?>>Drinker</option>
    <option value="non-drinker" <?= ($user['lifestyle'] == 'non-drinker') ? 'selected' : '' ?>>Non-Drinker</option>
</select>

    </div>
	</div>
	</div>
	</div>
</form>

    
 

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
let isEditing = false;

document.getElementById("editProfile").addEventListener("click", function(e) {
    e.preventDefault();

    const form = document.getElementById("userForm");
    // Select all relevant form elements: input, select, textarea
    const allFields = form.querySelectorAll("input, select, textarea");

    if (!isEditing) {
        allFields.forEach(field => field.disabled = false);
        this.textContent = "Save Changes";
    } else {
        // Enable all fields before submitting
        allFields.forEach(field => field.disabled = false);
        form.submit();
    }

    isEditing = !isEditing;
});
</script>




<script>
    // Trigger the logout modal
    document.querySelector('.logout-link').addEventListener('click', function(e) {
        e.preventDefault();
        const modal = document.getElementById('logoutModal');
        modal.style.display = 'flex'; // Show the logout modal
    });

    // Close the logout modal when "Cancel" is clicked
    document.getElementById('cancelLogout').addEventListener('click', function() {
        document.getElementById('logoutModal').style.display = 'none'; // Hide the modal
    });

    // Redirect to logout.php when "Confirm" is clicked
    document.getElementById('confirmLogout').addEventListener('click', function() {
        window.location.href = 'logout.php'; // Redirect to logout.php
    });
	
	


</script>
</body>
</html>