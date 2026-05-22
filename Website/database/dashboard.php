<?php
session_start();
include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] === 'signup') {
        // Handle signup functionality
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // You can add more user data here and save it to the database
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
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $storedPassword);

        // Fetch the result
        if ($stmt->fetch() && password_verify($password, $storedPassword)) {
            // Successful login
            $_SESSION['user_id'] = $id;
            $_SESSION['email'] = $email;
            header("Location: dashboard.php"); // Redirect to the user's dashboard
            exit();
        } else {
            $error = "Invalid email or password";
        }

        $stmt->close();
        $conn->close();
    }
}
?>






<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $email = $_POST['email']; 
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $_SESSION['signup_data'] = [
        'email' => $email,
        'password' => $password
    ];

    header("Location: signup1.php");
    exit;

}