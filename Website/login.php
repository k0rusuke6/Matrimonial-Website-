<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'signup') {
        // Handle signup functionality
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $_SESSION['signup_data'] = [
            'email' => $email,
            'password' => $password
        ];

        header("Location: signup1.php");
        exit;
    } elseif (isset($_POST['action']) && $_POST['action'] === 'login') {
        // Handle login functionality
        $email = $_POST['mail'];
        $password = $_POST['pass'];

        // Create a connection to the database
        $conn = new mysqli('localhost', 'root', '', 'matrimonial');

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind the statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT user_id, password, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($user_id, $storedPassword, $status);

        // Fetch the result
        if ($stmt->fetch() && password_verify($password, $storedPassword)) {
            // Check account status
            if ($status === 'approved') {
                // Store user ID and email in session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['email'] = $email;
                $stmt->close();

                // Fetch previous last login time
                $prevLoginStmt = $conn->prepare("SELECT last_login FROM users WHERE user_id = ?");
                $prevLoginStmt->bind_param("i", $user_id);
                $prevLoginStmt->execute();
                $prevLoginStmt->bind_result($last_login);
                $prevLoginStmt->fetch();
                $prevLoginStmt->close();

                // Store previous login time in session
                $_SESSION['last_login'] = $last_login;

                // Update last_login with the new login time
                $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                $updateStmt->bind_param("i", $user_id);
                $updateStmt->execute();
                $updateStmt->close();

                // Redirect to dashboard
                $conn->close();
                header("Location: dashboard.php");
                exit();
            } elseif ($status === 'pending') {
                $error = "Your profile is pending approval. Please wait.";
            } elseif ($status === 'rejected') {
                $error = "Your profile has been rejected.";
            }
        } else {
            $error = "Invalid email or password";
        }

        $stmt->close();
        $conn->close();
    }
}

?>



<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <title> Login and Registration Form </title>
    <link rel="stylesheet" href="style.css">
    <!-- Fontawesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>

	<style>
		/* Google Font Link */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}

body {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #333333;
  padding: 30px;
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


.container {
  position: relative;
  max-width: 850px;
  width: 100%;
  background: #fff;
  padding: 40px 30px;
  box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
  perspective: 2700px;
}

.container .cover {
  position: absolute;
  top: 0;
  left: 50%;
  height: 100%;
  width: 50%;
  z-index: 98;
  transition: all 1s ease;
  transform-origin: left;
  transform-style: preserve-3d;
  backface-visibility: hidden;
}

.container #flip:checked ~ .cover {
  transform: rotateY(-180deg);
}

.container #flip:checked ~ .forms .login-form {
  pointer-events: none;
}

.container .cover .front,
.container .cover .back {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
}

.cover .back {
  transform: rotateY(180deg);
}

.container .cover img {
  position: absolute;
  height: 100%;
  width: 100%;
  object-fit: cover;
  z-index: 10;
}

.container .cover .text {
  position: absolute;
  z-index: 10;
  height: 100%;
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.container .cover .text::before {
  content: '';
  position: absolute;
  height: 100%;
  width: 100%;
  opacity: 0.5;
  background: #333333;
}

.cover .text .text-1,
.cover .text .text-2 {
  z-index: 20;
  font-size: 26px;
  font-weight: 600;
  color: #fff;
  text-align: center;
}

.cover .text .text-2 {
  font-size: 15px;
  font-weight: 500;
}

.container .forms {
  height: 100%;
  width: 100%;
  background: #fff;
}

.container .form-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.form-content .login-form,
.form-content .signup-form {
  width: calc(100% / 2 - 25px);
}

.forms .form-content .title {
  position: relative;
  font-size: 24px;
  font-weight: 500;
  color: #333;
}

.forms .form-content .title:before {
  content: '';
  position: absolute;
  left: 0;
  bottom: 0;
  height: 3px;
  width: 25px;
  background: #333333;
}

.forms .signup-form .title:before {
  width: 20px;
}

.forms .form-content .input-boxes {
  margin-top: 30px;
}

.forms .form-content .input-box {
  display: flex;
  align-items: center;
  height: 50px;
  width: 100%;
  margin: 10px 0;
  position: relative;
}

.form-content .input-box input {
  height: 100%;
  width: 100%;
  outline: none;
  border: none;
  padding: 0 30px;
  font-size: 16px;
  font-weight: 500;
  border-bottom: 2px solid rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.form-content .input-box input:focus,
.form-content .input-box input:valid {
  border-color: #333333;
}

.form-content .input-box i {
  position: absolute;
  color: #333333;
  font-size: 17px;
}

.forms .form-content .text {
  font-size: 14px;
  font-weight: 500;
  color: #333;
}

.forms .form-content .text a {
  text-decoration: none;
	color:#5b13b9;
}

.forms .form-content .text a:hover {
  text-decoration: underline;
}

.forms .form-content .button {
  color: #fff;
  margin-top: 40px;
}

.forms .form-content .button input {
  color: #fff;
  background: black;
  border-radius: 6px;
  padding: 0;
  cursor: pointer;
  transition: all 0.4s ease;
}


.forms .form-content label {
  color: #5b13b9;
  cursor: pointer;
}

.forms .form-content label:hover {
  text-decoration: underline;
}

.forms .form-content .login-text,
.forms .form-content .sign-up-text {
  text-align: center;
  margin-top: 25px;
}

.container #flip {
  display: none;
}

@media (max-width: 730px) {
  .container .cover {
    display: none;
  }

  .form-content .login-form,
  .form-content .signup-form {
    width: 100%;
  }

  .form-content .signup-form {
    display: none;
  }

  .container #flip:checked ~ .forms .signup-form {
    display: block;
  }

  .container #flip:checked ~ .forms .login-form {
    display: none;
  }
}
	</style>
<body>
	<div class="logo">
    <a href="index.php">
        <img src="images/logo.ico" alt="Website Logo">
    </a>
</div>

  <div class="container">
    <input type="checkbox" id="flip">
    <div class="cover">
      <div class="front">
        <img src="images/frontImg.jpg" alt="">
        <div class="text">
          <span class="text-1">Every new friend is a <br> new adventure</span>
          <span class="text-2">Let's get connected</span>
        </div>
      </div>
      <div class="back">
        <img class="backImg" src="images/backImg.jpg" alt="">
        <div class="text">
          <span class="text-1">Complete miles of journey <br> with one step</span>
          <span class="text-2">Let's get started</span>
        </div>
      </div>
    </div>
    <div class="forms">
        <div class="form-content">
          <div class="login-form">
            <div class="title">Login</div>
          <form action="" method="POST">
	<input type="hidden" name="action" value="login">
            <div class="input-boxes">
              <div class="input-box">
                <i class="fas fa-envelope"></i>
                <input type="text" name="mail" placeholder="Enter your email" required>
              </div>
              <div class="input-box">
                <i class="fas fa-lock"></i>
                <input type="password" name="pass" placeholder="Enter your password" required>
              </div>
              <div class="text"><a href="forgot.php">Forgot password?</a></div>
		<?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
              <div class="button input-box">
                <input type="submit" value="Sumbit">
              </div>
              <div class="text sign-up-text">Don't have an account? <label for="flip">Sigup now</label></div>
		
            </div>
        </form>
      </div>
        <div class="signup-form">
          <div class="title">Signup</div>
      <form id="signupForm" action="" method="POST" onsubmit="validateForm(event)">
	<input type="hidden" name="action" value="signup">
    <div class="input-boxes">
        <div class="input-box">
            <i class="fas fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="input-box">
            <i class="fas fa-lock"></i>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="input-box">
            <i class="fas fa-lock"></i>
            <input type="password" id="confirmPassword" placeholder="Confirm password" required>
        </div>
        <div class="button input-box">
            <input type="submit" value="Submit">
        </div>
        <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
        <div id="errorMessage" style="color: red; margin-top: 10px;"></div>
    </div>
</form>



    </div>
    </div>
    </div>
  </div>


   <script>
        function validateForm(event) {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;
            var message = "";

            // Check if the password has at least one uppercase letter
            if (!/[A-Z]/.test(password)) {
                message += "Password must contain at least one uppercase letter.\n";
            }

            // Check if the password has at least one lowercase letter
            if (!/[a-z]/.test(password)) {
                message += "Password must contain at least one lowercase letter.\n";
            }

            // Check if the password has at least one number
            if (!/[0-9]/.test(password)) {
                message += "Password must contain at least one number.\n";
            }

            // Check if the password has at least one special character
            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                message += "Password must contain at least one special character.\n";
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                message += "Passwords do not match!";
            }

            if (message) {
                document.getElementById('errorMessage').textContent = message;
                event.preventDefault(); // Prevent form submission if validation fails
            } else {
                document.getElementById('errorMessage').textContent = ""; // Clear error message
                // Redirect to the next page
                window.location.href = "signup1.php"; 
            }
        }
    </script>
</body>
</html>