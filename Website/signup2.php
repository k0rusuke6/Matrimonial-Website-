<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Ensure session contains previous data
    if (!isset($_SESSION['signup_data'])) {
        header("Location: login.php");
        exit;
    }

    // Debug session data
    echo "<pre>Session Data Before Merging Third-Page Data:\n";
    print_r($_SESSION['signup_data']);
    echo "</pre>";

    // Collect third-page data
    $qualification = filter_input(INPUT_POST, 'qualification', FILTER_SANITIZE_STRING);
    $institution = filter_input(INPUT_POST, 'institution', FILTER_SANITIZE_STRING);
    $occupation = filter_input(INPUT_POST, 'occupation', FILTER_SANITIZE_STRING);
    $job_location = filter_input(INPUT_POST, 'job_location', FILTER_SANITIZE_STRING);
    $salary_range = filter_input(INPUT_POST, 'salary_range', FILTER_SANITIZE_STRING);
    $age_range = filter_input(INPUT_POST, 'age_range', FILTER_SANITIZE_STRING);
    $prefer_lifestyle = filter_input(INPUT_POST, 'prefer_lifestyle', FILTER_SANITIZE_STRING);
    $prefer_qualification = filter_input(INPUT_POST, 'prefer_qualification', FILTER_SANITIZE_STRING);
    $prefer_occupation = filter_input(INPUT_POST, 'prefer_occupation', FILTER_SANITIZE_STRING);
    $prefer_religion = filter_input(INPUT_POST, 'prefer_religion', FILTER_SANITIZE_STRING);

 // Path to the fixed image you want to upload
$upload_dir = "uploads/";
$fixed_file = "aadhar.jpg"; // The fixed file name
$target_file = $upload_dir . $fixed_file;

// Check if a file was uploaded
if (isset($_FILES['identity_proof']) && $_FILES['identity_proof']['error'] === UPLOAD_ERR_OK) {
    // Move the uploaded file (but ignore it and just upload aadhar.jpg)
    if (move_uploaded_file($_FILES['identity_proof']['tmp_name'], $target_file)) {
        // File uploaded successfully, but we do not care about the uploaded file
        echo "File has been replaced by aadhar.jpg.";
    } else {
        echo "Failed to upload the file.";
    }
} else {
    // If no file uploaded, just ensure aadhar.jpg is present
    if (!file_exists($target_file)) {
        // You can choose to copy a default aadhar.jpg if it doesn't exist
        // Example: copy from a default location, if you have one
        // copy("path_to_default_aadhar.jpg", $target_file);
        echo "No file uploaded, but aadhar.jpg is set.";
    }
}

// Now, store the path to the DB
$identity_proof = $target_file; // This is the fixed file path

    // Store data in session
    $_SESSION['signup_data'] = array_merge($_SESSION['signup_data'], [
        'qualification' => $qualification,
        'institution' => $institution,
        'occupation' => $occupation,
        'job_location' => $job_location,
        'salary_range' => $salary_range,
        'identity_proof' => $identity_proof,
        'age_range' => $age_range,
        'prefer_lifestyle' => $prefer_lifestyle,
        'prefer_qualification' => $prefer_qualification,
        'prefer_occupation' => $prefer_occupation,
        'prefer_religion' => $prefer_religion
    ]);

    // Debug session data after merging
    echo "<pre>Session Data After Merging Third-Page Data:\n";
    print_r($_SESSION['signup_data']);
    echo "</pre>";

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'matrimonial');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO users (
        email, password, full_name, gender, dob, religion, caste, marital_status, 
        profile_picture, phone_number, country, state, city, lifestyle, 
        qualification, institution, occupation, job_location, salary_range, 
        identity_proof, age_range, prefer_lifestyle, prefer_qualification, 
        prefer_occupation, prefer_religion
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Error preparing SQL statement: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssssssssssssssssssss",
        $_SESSION['signup_data']['email'],
        $_SESSION['signup_data']['password'],
        $_SESSION['signup_data']['full_name'],
        $_SESSION['signup_data']['gender'],
        $_SESSION['signup_data']['dob'],
        $_SESSION['signup_data']['religion'],
        $_SESSION['signup_data']['caste'],
        $_SESSION['signup_data']['marital_status'],
        $_SESSION['signup_data']['profile_picture'],
        $_SESSION['signup_data']['phone_number'],
        $_SESSION['signup_data']['country'],
        $_SESSION['signup_data']['state'],
        $_SESSION['signup_data']['city'],
        $_SESSION['signup_data']['lifestyle'],
        $_SESSION['signup_data']['qualification'],
        $_SESSION['signup_data']['institution'],
        $_SESSION['signup_data']['occupation'],
        $_SESSION['signup_data']['job_location'],
        $_SESSION['signup_data']['salary_range'],
        $_SESSION['signup_data']['identity_proof'],
        $_SESSION['signup_data']['age_range'],
        $_SESSION['signup_data']['prefer_lifestyle'],
        $_SESSION['signup_data']['prefer_qualification'],
        $_SESSION['signup_data']['prefer_occupation'],
        $_SESSION['signup_data']['prefer_religion']
    );

    // Execute query
    if ($stmt->execute()) {
        echo "<script>
                alert('Successfully signed up');
                window.location.href = 'login.php';
              </script>";
        unset($_SESSION['signup_data']); // Clear only signup data
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<!-- Coding By CodingNepal - codingnepalweb.com -->
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

       <form action="signup2.php" method="POST">

    <div class="form first">
        <div class="details personal">
            <span class="title">Education & Profession </span>

            <div class="fields">
               <form action="signup2.php" method="POST" enctype="multipart/form-data">


    <div class="input-field">
        <label for="qualification">Highest Qualification</label>
        <select id="qualification" name="qualification" required>
            <option disabled selected>Select your qualification</option>
            <option value="bachelors">Bachelor's</option>
            <option value="masters">Master's</option>
            <option value="phd">PhD</option>
            <option value="diploma">Diploma</option>
	<option value="highschool">High School</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div class="input-field">
        <label for="institution">Institution</label>
        <input type="text" id="institution" name="institution" placeholder="Enter your institution name" required>
    </div>

    <div class="input-field">
        <label for="occupation">Occupation</label>
        <input type="text" id="occupation" name="occupation" placeholder="Enter your occupation" required>
    </div>

    <div class="input-field">
        <label for="job-location">Job Location</label>
        <input type="text" id="job-location" name="job_location" placeholder="Enter your job location" required>
    </div>

    <div class="input-field">
        <label for="salary-range">Salary Range</label>
        <select id="salary-range" name="salary_range" required>
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

     <div class="input-field">
        <label for="identity_proof">Upload Identity Proof (Aadhaar, PAN, etc.)</label>
        <input type="file" id="identity_proof" name="identity_proof" accept=".jpg, .jpeg, .png, .pdf" required>
    </div>


        <div class="details contact">
            <span class="title">Partner Preference</span>

            <div class="fields">
               
    <div class="input-field">
        <label for="age-range">Age Range</label>
        <select id="age-range" name="age_range" required>
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

            <div class="input-field">
    <label for="lifestyle">Lifestyle Choices</label>
    <select id="lifestyle" name="prefer_lifestyle" required>
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


    <div class="input-field">
        <label for="education-level">Education Level</label>
        <select id="education-level" name="prefer_qualification" required>
            <option disabled selected>Select education level</option>
            <option value="bachelors">Bachelor's</option>
            <option value="masters">Master's</option>
            <option value="phd">PhD</option>
            <option value="diploma">Diploma</option>
	<option value="highschool">High School</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div class="input-field">
        <label for="occupation">Occupation</label>
        <input type="text" id="occupation" name="prefer_occupation" placeholder="Enter preferred occupation" required>
    </div>

    <div class="input-field">
    <label for="religion">Religion</label>
    <select id="religion" name="prefer_religion"  required>
        <option disabled selected>Select preferred religion</option>
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
        <label for="hobbies">Hobbies and Interests</label>
        <textarea id="hobbies" name="prefer_hobbies" rows="4" placeholder="Enter preferred hobbies and interests" required></textarea>
    </div>

    <button type="submit" class="submit-btn">Submit</button>
</form>


    <script src="script.js"></script>
</body>
</html>