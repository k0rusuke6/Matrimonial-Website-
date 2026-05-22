<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the session already contains data from page 1
    if (!isset($_SESSION['signup_data'])) {
        header("Location: login.php");
        exit;
    }

    // Get the data from the second page form
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $dob = $_POST['dob'];
    $religion = filter_input(INPUT_POST, 'religion', FILTER_SANITIZE_STRING);
    $caste = filter_input(INPUT_POST, 'caste', FILTER_SANITIZE_STRING);
    $marital_status = filter_input(INPUT_POST, 'marital_status', FILTER_SANITIZE_STRING);
    $phone_number = filter_input(INPUT_POST, 'phone_number', FILTER_SANITIZE_STRING);
    $country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_STRING);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $lifestyle = filter_input(INPUT_POST, 'lifestyle', FILTER_SANITIZE_STRING);

    // Handle profile picture upload
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $upload_dir = "uploads/"; // Ensure this directory exists and is writable
        $filename = basename($_FILES['profile_picture']['name']);
        $target_file = $upload_dir . uniqid() . "_" . $filename;

        // Validate the file type
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                $profile_picture = $target_file;
            } else {
                echo "Error uploading profile picture.";
            }
        } else {
            echo "Invalid file type for profile picture.";
        }
    }

    // Save the data into the session
    $_SESSION['signup_data'] = array_merge($_SESSION['signup_data'], [
        'full_name' => $full_name,
        'gender' => $gender,
        'dob' => $dob,
        'religion' => $religion,
        'caste' => $caste,
        'marital_status' => $marital_status,
        'phone_number' => $phone_number,
        'country' => $country,
        'state' => $state,
        'city' => $city,
        'lifestyle' => $lifestyle,
        'profile_picture' => $profile_picture
    ]);

    // Redirect to the third page
    header("Location: signup2.php");
    exit;
}
?>



<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
   <style>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
	/* ===== Google Font Import - Poppins ===== */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600&display=swap');
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body{
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #333333;
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

.container{
    position: relative;
    max-width: 900px;
    width: 100%;
    border-radius: 6px;
    padding: 30px;
    margin: 0 15px;
    background-color: #fff;
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
}
.container header{
    position: relative;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}
.container header::before{
    content: "";
    position: absolute;
    left: 0;
    bottom: -2px;
    height: 3px;
    width: 27px;
    border-radius: 8px;
    background-color: #333333;
}
.container form{
    position: relative;
    margin-top: 16px;
    min-height: 490px;
    background-color: #fff;
    overflow: hidden;
}
.container form .form{
    position: absolute;
    background-color: #fff;
    transition: 0.3s ease;
}
.container form .form.second{
    opacity: 0;
    pointer-events: none;
    transform: translateX(100%);
}
form.secActive .form.second{
    opacity: 1;
    pointer-events: auto;
    transform: translateX(0);
}
form.secActive .form.first{
    opacity: 0;
    pointer-events: none;
    transform: translateX(-100%);
}

.container form .title{
    display: block;
    margin-bottom: 8px;
    font-size: 16px;
    font-weight: 500;
    margin: 6px 0;
    color: #333;
}
.container form .fields{
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}
form .fields .input-field{
    display: flex;
    width: calc(100% / 3 - 15px);
    flex-direction: column;
    margin: 4px 0;
}
.input-field label{
    font-size: 12px;
    font-weight: 500;
    color: #2e2e2e;
}


.input-field input, select{
    outline: none;
    font-size: 14px;
    font-weight: 400;
    color: #333;
    border-radius: 5px;
    border: 1px solid #aaa;
    padding: 0 15px;
    height: 42px;
    margin: 8px 0;
}



.input-field input :focus,
.input-field select:focus{
    box-shadow: 0 3px 6px rgba(0,0,0,0.13);
}
.input-field select,
.input-field input[type="date"]{
    color: #707070;
}
.input-field input[type="date"]:valid{
    color: #333;
}


.container form button, .backBtn{
    display: flex;
    align-items: center;
    justify-content: center;
    height: 45px;
    max-width: 200px;
    width: 100%;
    border: none;
    outline: none;
    color: #fff;
    border-radius: 5px;
    margin: 25px 0;
    background-color: black;
    transition: all 0.3s linear;
    cursor: pointer;
}
.container form .btnText{
    font-size: 14px;
    font-weight: 400;
}
form button:hover{
    background-color: #265df2;
}
form button i,
form .backBtn i{
    margin: 0 6px;
}
form .backBtn i{
    transform: rotate(180deg);
}
form .buttons{
    display: flex;
    align-items: center;
}
form .buttons button , .backBtn{
    margin-right: 14px;
}

@media (max-width: 750px) {
    .container form{
        overflow-y: scroll;
    }
    .container form::-webkit-scrollbar{
       display: none;
    }
    form .fields .input-field{
        width: calc(100% / 2 - 15px);
    }
}

@media (max-width: 550px) {
    form .fields .input-field{
        width: 100%;
    }
}
	</style>

   <title>Regisration Form </title>
</head>
<body>

	<div class="logo">
    <a href="index.php">
        <img src="images/logo.ico" alt="Website Logo">
    </a>
</div>
    <div class="container">
        <header>Registration</header>

       <form id ="FormId" action="signup1.php" method="POST" enctype="multipart/form-data">
    <div class="form first">
        <div class="details personal">
            <span class="title">Personal Information</span>

            <div class="fields">
                <div class="input-field" ">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name = "full_name" placeholder="Enter your full name" required>
                </div>

                <div class="input-field">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option disabled selected>Select gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="input-field">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name ="dob" placeholder="Enter birth date" required>
                </div>

              <div class="input-field">
    <label for="religion">Religion</label>
    <select id="religion" name="religion" required>
        <option disabled selected>Select your religion</option>
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



                <div class="input-field">
    <label for="caste">Caste</label>
    <select id="caste" name="caste" required>
        <option disabled selected>Select your caste</option>
        <option value="general">General</option>
        <option value="obc">OBC</option>
        <option value="sc">SC</option>
        <option value="st">ST</option>
        <option value="other">Other</option>
    </select>
</div>


               <div class="input-field">
    <label for="marital-status">Marital Status</label>
    <select id="marital-status" name="marital_status" required>
        <option disabled selected>Select your marital status</option>
        <option value="single">Single</option>
        <option value="married">Married</option>
        <option value="divorced">Divorced</option>
        <option value="widowed">Widowed</option>
        <option value="separated">Separated</option>
    </select>
</div>

            </div>
        </div>

        <div class="details contact">
            	

            <div class="fields">
                <div class="input-field">
    <label for="user-image">Upload Your Image</label>
    <input type="file" id="user-image" name="profile_picture" accept="image/*"placeholder="Select your profile image">
</div>

                <div class="input-field">
    <label for="phone-number">Phone Number</label>
    <input type="tel" id="phone-number" name="phone_number" placeholder="Enter your phone number" pattern="[0-9]{10}" required>
	</div>


                
                  <div class="input-field">
                    <label for="country">Country</label>
                    <select id="country" name="country" required>
                        <option disabled selected>Select Country</option>
                        <option>Indian</option>
                        <option>NRI</option>
                    </select>
                </div>
		

              <div class="input-field">
    <label for="state">State</label>
    <select id="state" required name="state" onchange="populateCities()">
        <option disabled selected>Select your state</option>
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


                <div class="input-field">
    <label for="city">City</label>
    <select id="city" name="city" required>
        <option disabled selected>Select your city</option>
    </select>
	</div>


                <div class="input-field">
    <label for="lifestyle">Lifestyle Choices</label>
    <select id="lifestyle" name="lifestyle" required>
        <option disabled selected>Select your lifestyle choices</option>
        <option value="vegetarian">Vegetarian</option>
        <option value="non-vegetarian">Non-Vegetarian</option>
        <option value="vegan">Vegan</option>
        <option value="smoker">Smoker</option>
        <option value="non-smoker">Non-Smoker</option>
        <option value="drinker">Drinker</option>
        <option value="non-drinker">Non-Drinker</option>
    </select>
</div>

            </div>

            <button class="nextBtn">
                <span class="btnText">Next</span>
                <i class="uil uil-navigator"></i>
            </button>
        </div> 
    </div>

        </form>
    </div>
<script>
    function populateCities() {
        var stateSelect = document.getElementById("state");
        var citySelect = document.getElementById("city");

        // Clear previous city options
        citySelect.innerHTML = '<option disabled selected>Select your city</option>';

        var cities = [];

        if (stateSelect.value === "andhra-pradesh") {
            cities = ["Visakhapatnam", "Vijayawada", "Guntur", "Nellore"];
        } else if (stateSelect.value === "arunachal-pradesh") {
            cities = ["Itanagar", "Tawang", "Ziro", "Pasighat"];
        } else if (stateSelect.value === "assam") {
            cities = ["Guwahati", "Silchar", "Dibrugarh", "Jorhat"];
        } else if (stateSelect.value === "bihar") {
            cities = ["Patna", "Gaya", "Bhagalpur", "Muzaffarpur"];
        } else if (stateSelect.value === "chhattisgarh") {
            cities = ["Raipur", "Bilaspur", "Durg", "Bhilai"];
        } else if (stateSelect.value === "goa") {
            cities = ["Panaji", "Margao", "Vasco da Gama", "Mapusa"];
        } else if (stateSelect.value === "gujarat") {
            cities = ["Ahmedabad", "Surat", "Vadodara", "Rajkot"];
        } else if (stateSelect.value === "haryana") {
            cities = ["Gurgaon", "Faridabad", "Panipat", "Ambala"];
        } else if (stateSelect.value === "himachal-pradesh") {
            cities = ["Shimla", "Manali", "Dharamshala", "Solan"];
        } else if (stateSelect.value === "jharkhand") {
            cities = ["Ranchi", "Jamshedpur", "Dhanbad", "Bokaro"];
        } else if (stateSelect.value === "karnataka") {
            cities = ["Bangalore", "Mysore", "Mangalore", "Hubli"];
        } else if (stateSelect.value === "kerala") {
            cities = ["Thiruvananthapuram", "Kochi", "Kozhikode", "Thrissur"];
        } else if (stateSelect.value === "madhya-pradesh") {
            cities = ["Bhopal", "Indore", "Gwalior", "Jabalpur"];
        } else if (stateSelect.value === "maharashtra") {
            cities = ["Mumbai", "Pune", "Nagpur", "Nashik"];
        } else if (stateSelect.value === "manipur") {
            cities = ["Imphal", "Churachandpur", "Bishnupur", "Thoubal"];
        } else if (stateSelect.value === "meghalaya") {
            cities = ["Shillong", "Tura", "Jowai", "Nongpoh"];
        } else if (stateSelect.value === "mizoram") {
            cities = ["Aizawl", "Lunglei", "Champhai", "Kolasib"];
        } else if (stateSelect.value === "nagaland") {
            cities = ["Kohima", "Dimapur", "Mokokchung", "Tuensang"];
        } else if (stateSelect.value === "odisha") {
            cities = ["Bhubaneswar", "Cuttack", "Rourkela", "Puri"];
        } else if (stateSelect.value === "punjab") {
            cities = ["Ludhiana", "Amritsar", "Jalandhar", "Patiala"];
        } else if (stateSelect.value === "rajasthan") {
            cities = ["Jaipur", "Jodhpur", "Udaipur", "Kota"];
        } else if (stateSelect.value === "sikkim") {
            cities = ["Gangtok", "Gyalshing", "Mangan", "Namchi"];
        } else if (stateSelect.value === "tamil-nadu") {
            cities = ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli"];
        } else if (stateSelect.value === "telangana") {
            cities = ["Hyderabad", "Warangal", "Nizamabad", "Khammam"];
        } else if (stateSelect.value === "tripura") {
            cities = ["Agartala", "Udaipur", "Dharmanagar", "Kailashahar"];
        } else if (stateSelect.value === "uttar-pradesh") {
            cities = ["Lucknow", "Kanpur", "Varanasi", "Agra"];
        } else if (stateSelect.value === "uttarakhand") {
            cities = ["Dehradun", "Haridwar", "Nainital", "Roorkee"];
        } else if (stateSelect.value === "west-bengal") {
            cities = ["Kolkata", "Howrah", "Darjeeling", "Siliguri"];
        } else if (stateSelect.value === "andaman-nicobar") {
            cities = ["Port Blair", "Diglipur", "Rangat", "Mayabunder"];
        } else if (stateSelect.value === "chandigarh") {
            cities = ["Chandigarh"];
        } else if (stateSelect.value === "dadra-nagar-haveli") {
            cities = ["Silvassa"];
        } else if (stateSelect.value === "daman-diu") {
            cities = ["Daman", "Diu"];
        } else if (stateSelect.value === "delhi") {
            cities = ["New Delhi"];
        } else if (stateSelect.value === "lakshadweep") {
            cities = ["Kavaratti", "Agatti", "Amini", "Andrott"];
        } else if (stateSelect.value === "puducherry") {
            cities = ["Puducherry", "Karaikal", "Mahe", "Yanam"];
        } else if (stateSelect.value === "ladakh") {
            cities = ["Leh", "Kargil"];
        } else if (stateSelect.value === "jammu-kashmir") {
            cities = ["Srinagar", "Jammu", "Anantnag", "Baramulla"];
        } else {
            cities = []; // Default empty array if no state selected
        }

        cities.forEach(function(city) {
            var option = document.createElement("option");
            option.value = city.toLowerCase().replace(/\s+/g, '-');
            option.textContent = city;
            citySelect.appendChild(option);
        });
    }
</script>

<script>
	document.getElementById('FormId').addEventListener('submit', function(event) {
    var dob = new Date(document.getElementById('dob').value);
    var today = new Date();
    var age = today.getFullYear() - dob.getFullYear();
    var monthDifference = today.getMonth() - dob.getMonth();

    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    if (age < 18) {
        event.preventDefault();
        alert('You must be at least 18 years old.');
    }
});

</script>


    <script src="script.js"></script>
</body>
</html>