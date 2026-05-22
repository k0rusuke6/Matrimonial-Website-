
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
    border-radius: 6px;
    padding: 30px;
    margin: 0 15px;
    height:320px;
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
.reset-password-box {
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    text-align: center;
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
}

.reset-password-box h3 {
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.reset-password-box label {
    display: block;
    margin-bottom: 5px;
    text-align: left;
    font-size:14px;
    font-weight: bold;
    color: #333;
}

.reset-password-box input {
    width: calc(100% - 25px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

.reset-password-box input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.reset-password-box button {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    color: #fff;
    background-color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
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

   <title>Reset Password </title>
</head>
<body>

	<div class="logo">
    <a href="index.php">
        <img src="images/logo.ico" alt="Website Logo">
    </a>
</div>
    <div class="container">
        <header>Reset Password</header>
	<div class="reset-password-box">
    <form method="post" action="reset_password.php" id="resetPasswordForm">
        
        <label for="newPassword">New Password</label>
        <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password" required>
        
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" required>
        
        <button type="submit" id="changePasswordButton">Change Password</button>
    </form>
</div>

</div>

<script>
document.getElementById('resetPasswordForm').addEventListener('submit', function (event) {
    var newPassword = document.getElementById('newPassword').value;
    var confirmPassword = document.getElementById('confirmPassword').value;

    if (newPassword !== confirmPassword) {
        alert('Passwords do not match. Please try again.');
        event.preventDefault(); // Prevent form submission
    } else if (newPassword.length < 6) {
        alert('Password must be at least 6 characters long.');
        event.preventDefault();
    }
});
</script>


    <script src="script.js"></script>
</body>
</html>