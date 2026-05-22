<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'matrimonial');
    if ($conn->connect_error) {
        die('Database connection failed: ' . $conn->connect_error);
    }

    // Check if the email is registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Save the OTP in the session (or database if needed)
        session_start();
        $_SESSION['otp'] = $otp;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'yeagerist601@gmail.com'; // Replace with your email
            $mail->Password = 'elfcgjphtkrbmbtz';   // Replace with your app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('aayushmaurya601@gmail.com', 'Your Name'); // Replace with your email and name
            $mail->addAddress($email); // Send to the user's email

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body = "Your OTP code is: <b>$otp</b>";

            $mail->send();
            echo 'OTP sent successfully!';
        } catch (Exception $e) {
            echo "Failed to send OTP. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "The email address is not registered.";
    }

    $stmt->close();
    $conn->close();
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
    width: 400px;
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

/* General Form Styling */
form#forgot {
    max-width: 500px;
    margin: 30px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #f9f9f9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: Arial, sans-serif;
}

/* Label Styling */
form#forgot label {
    display: block;
    margin-bottom: 5px; /* Reduced margin for closer alignment */
    font-size: 14px;
    font-weight: bold;
    color: #333;
}

/* Specific Fix for the Email Label */
form#forgot #input-field {
    margin-bottom: 5px !important; /* Override unnecessary gap here */
}

/* Input Styling */
form#forgot input[type="email"],
form#forgot input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px; /* Reduced overall bottom margin */
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    color: #333;
    background-color: #fff;
}

form#forgot input[type="email"]:focus,
form#forgot input[type="text"]:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Input Group Styling */
.input-group {
    display: flex;
    align-items: center;
    gap: 10px; /* Adjust gap between email box and button */
    margin-bottom: 15px;
}

.input-group input[type="email"] {
    flex: 2; /* Email input takes more width */
}

.input-group #sendOtpButton {
    flex: 1; /* OTP button takes less width */
    padding: 10px;
    background-color: black;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    text-align: center;
}



/* Button Styling */
form#forgot .submit-btn {
    width: 100%;
    padding: 10px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color:black;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
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

   <title>Forgot Password </title>
</head>
<body>

	<div class="logo">
    <a href="index.php">
        <img src="images/logo.ico" alt="Website Logo">
    </a>
</div>
    <div class="container">
        <header>Forgot Password</header>
	<form method="post" action="forgot_password.php" id="forgot">
    <label id="input-field" for="email">Enter Your Registered Email</label>
    <div class="input-group">
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
        <button type="button" id="sendOtpButton">Send OTP</button>
    </div>
    <label for="otp">Enter OTP</label>
    <input type="text" id="otp" name="otp" placeholder="Enter OTP" maxlength="6" pattern="[0-9]{6}" required>
    <button type="submit" class="submit-btn">Verify and Submit</button>
</form>
</div>

<script>
    document.getElementById('sendOtpButton').addEventListener('click', function() {
        var emailInput = document.getElementById('email').value.trim(); // Get and trim email value

        if (emailInput) {
            // Use fetch to send the email to the server for OTP generation
          fetch('forgot_password.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'email=' + encodeURIComponent(emailInput)
})
            .then(response => response.text()) // Parse the response as text
            .then(data => {
                if (data === 'success') {
                    alert('OTP has been sent to your email!');
                } else {
                    alert('Error: ' + data); // Show error message from the server
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while sending the OTP. Please try again.');
            });
        } else {
            alert('Please enter your email address.');
        }
    });
</script>



    <script src="script.js"></script>
</body>
</html>