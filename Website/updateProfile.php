<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Step 1: Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Step 2: Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	include 'db_connect.php';
    // Sanitize and validate form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $religion = isset($_POST['religion']) ? mysqli_real_escape_string($conn, $_POST['religion']) : '';
    $caste = isset($_POST['caste']) ? mysqli_real_escape_string($conn, $_POST['caste']) : '';
    $marital_status = mysqli_real_escape_string($conn, $_POST['marital_status']);
    $country = isset($_POST['country']) ? mysqli_real_escape_string($conn, $_POST['country']) : '';
    $state = isset($_POST['state']) ? mysqli_real_escape_string($conn, $_POST['state']) : '';
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $qualification = isset($_POST['qualification']) ? mysqli_real_escape_string($conn, $_POST['qualification']) : '';
    $occupation = mysqli_real_escape_string($conn, $_POST['occupation']);
    $job_location = mysqli_real_escape_string($conn, $_POST['job_location']);
    $salary_range = isset($_POST['salary_range']) ? mysqli_real_escape_string($conn, $_POST['salary_range']) : '';
    $lifestyle = isset($_POST['lifestyle']) ? mysqli_real_escape_string($conn, $_POST['lifestyle']) : '';

    // Update the user's profile in the database
    $update_query = "UPDATE users SET full_name='$full_name', religion='$religion', caste='$caste', marital_status='$marital_status', country='$country', state='$state', city='$city', qualification='$qualification', occupation='$occupation', job_location='$job_location', salary_range='$salary_range', lifestyle='$lifestyle' WHERE user_id = '$user_id'";

    if (mysqli_query($conn, $update_query)) {
        echo "<script>
                // Show the success modal after update
                window.onload = function() {
                    document.getElementById('successModal').style.display = 'flex';
                    
                    // When the user clicks 'OK', close the modal and redirect
                    document.querySelector('.modal-close').addEventListener('click', function() {
                        window.location.href = 'profile.php'; // Redirect to profile page
                    });
                };
              </script>";
    } else {
        echo "<script>alert('Error updating profile: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<script>
    document.getElementById("editProfile").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default form submission
        let inputs = document.querySelectorAll(".form-input");
        inputs.forEach(input => input.disabled = false); // Enable all form inputs
    });
</script>

<!DOCTYPE html>
<html>
<head>
<style>
	/* Modal Background */
.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  z-index: 1000;
  justify-content: center;
  align-items: center;
  text-align: center;
}

/* Modal Content */
.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
  width: 300px;
  display: flex;
  flex-direction: column; /* Stack content vertically */
  justify-content: center;
  align-items: center;
  text-align: center;
}

/* Button Style */
.modal-close {
  background:#ff3366;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 3px;
  cursor: pointer;
  margin-top: 20px; /* Adds space between content and button */
}


</style>
</head>
<body>
<!-- Modal Structure -->
<div id="successModal" class="modal">
  <div class="modal-content">
    <h4>Profile Updated</h4>
    <p>Your profile has been successfully updated!</p>
    <button class="modal-close btn">OK</button>
  </div>
</div>
</body>
</html>
