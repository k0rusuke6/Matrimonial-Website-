<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";
exit();

session_start();
include 'db_connect.php'; // Connect to DB

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];

    // Fetch the current data from the database to keep unchanged fields
    $query = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        die("User not found.");
    }
    
    $current_data = $result->fetch_assoc();
    
    // Use existing data if fields are empty
    $full_name = !empty($_POST['full_name']) ? $_POST['full_name'] : $current_data['full_name'];
    $gender = !empty($_POST['gender']) ? $_POST['gender'] : $current_data['gender'];
    $dob = !empty($_POST['dob']) ? $_POST['dob'] : $current_data['dob'];
    $religion = !empty($_POST['religion']) ? $_POST['religion'] : $current_data['religion'];
    $caste = !empty($_POST['caste']) ? $_POST['caste'] : $current_data['caste'];
    $marital_status = !empty($_POST['marital_status']) ? $_POST['marital_status'] : $current_data['marital_status'];
    $country = !empty($_POST['country']) ? $_POST['country'] : $current_data['country'];
    $state = !empty($_POST['state']) ? $_POST['state'] : $current_data['state'];
    $city = !empty($_POST['city']) ? $_POST['city'] : $current_data['city'];
    $qualification = !empty($_POST['qualification']) ? $_POST['qualification'] : $current_data['qualification'];
    $institution = !empty($_POST['institution']) ? $_POST['institution'] : $current_data['institution'];
    $occupation = !empty($_POST['occupation']) ? $_POST['occupation'] : $current_data['occupation'];
    $job_location = !empty($_POST['job_location']) ? $_POST['job_location'] : $current_data['job_location'];
    $salary_range = !empty($_POST['salary_range']) ? $_POST['salary_range'] : $current_data['salary_range'];
    $hobbies = !empty($_POST['hobbies']) ? $_POST['hobbies'] : $current_data['hobbies'];
    $lifestyle = !empty($_POST['lifestyle']) ? $_POST['lifestyle'] : $current_data['lifestyle'];

    // Prepare an SQL query using prepared statements
    $sql = "UPDATE users SET 
            full_name=?, gender=?, dob=?, religion=?, caste=?, marital_status=?, 
            country=?, state=?, city=?, qualification=?, institution=?, occupation=?, 
            job_location=?, salary_range=?, hobbies=?, lifestyle=? 
            WHERE user_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssssi", 
        $full_name, $gender, $dob, $religion, $caste, $marital_status, 
        $country, $state, $city, $qualification, $institution, $occupation, 
        $job_location, $salary_range, $hobbies, $lifestyle, $user_id);

    if ($stmt->execute()) {
        echo "Profile updated successfully";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
