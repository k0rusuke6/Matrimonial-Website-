<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basic authentication logic
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin'] = $username;
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid credentials"]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
       /* General Styles */
body {
    font-family: 'Poppins', Arial, sans-serif;
    margin: 0;
    background: linear-gradient(120deg, #ffffff, #f0f0f0);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    color: #333;
}

/* Form Container */
form {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
}

/* Form Inputs */
.form-input {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 25px;
    font-size: 16px;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Button Styles */
#login-btn {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 25px;
    background: linear-gradient(90deg, #6a11cb, #2575fc);
    color: white;
    font-size: 18px;
    font-weight: bold;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

#login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

#login-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

/* Error Message */
#error-msg {
    color: red;
    font-size: 14px;
    margin-top: 10px;
}

/* Progress Bar */
.attempt-bar {
    height: 5px;
    width: 100%;
    background-color: black;
    border-radius: 5px;
    margin: 10px 0;
    overflow: hidden;
}

.attempt-bar div {
    height: 100%;
    background-color: #ff6b6b;
    border-radius: 5px;
    transition: width 0.3s ease;
}

/* Footer Text */
#remaining-attempts {
    font-size: 14px;
    color: #666;
}

    </style>
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; text-align: center;">
        <h1>Admin Login</h1>
        <form id="login-form" onsubmit="validateLogin(event);">
            <input id="username" type="text" class="form-input" placeholder="Enter username" required>
            <input id="password" type="password" class="form-input" placeholder="Enter password" required>
            <button id="login-btn" type="submit">Login</button>
        </form>
        <div id="attempt-bar" class="attempt-bar" style="width: 100%;"></div>
        <p id="remaining-attempts">3 attempts remaining</p>
        <p id="error-msg" class="error-msg"></p>
    </div>
    <script>
        let attempts = 3; // Maximum login attempts

        function validateLogin(event) {
            event.preventDefault(); // Prevent default form submission
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const errorMsg = document.getElementById('error-msg');
            const attemptBar = document.getElementById('attempt-bar');
            const remainingAttempts = document.getElementById('remaining-attempts');
            const loginBtn = document.getElementById('login-btn');

            // Clear previous error message
            errorMsg.textContent = '';

            // Check if inputs are empty
            if (!username || !password) {
                errorMsg.textContent = 'Both fields are required.';
                return;
            }

            // Send POST request to PHP script
            fetch('', { // Empty string since PHP is in the same file
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Login successful!');
                    window.location.href = 'admin_dashboard.php'; // Redirect to dashboard
                } else {
                    attempts--;
                    errorMsg.textContent = data.message || 'Invalid credentials. Try again.';
                    attemptBar.style.width = (attempts / 3) * 100 + '%';
                    remainingAttempts.textContent = `${attempts} attempts remaining`;

                    if (attempts <= 0) {
                        loginBtn.disabled = true;
                        errorMsg.textContent = 'Too many failed attempts. Please try again later.';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorMsg.textContent = 'Something went wrong. Please try again.';
            });
        }
    </script>
</body>
</html>
