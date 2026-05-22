<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "matrimonial";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user_id is provided
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']); // Prevent SQL Injection

    // Fetch user details
    $sql = "SELECT * FROM users WHERE user_id = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Convert date of birth to age
        $row['age'] = date_diff(date_create($row['dob']), date_create('today'))->y;

        // Return data as JSON
        header('Content-Type: application/json');
        echo json_encode($row, JSON_PRETTY_PRINT);
    } else {
        echo json_encode(["error" => "Profile not found"]);
    }
}

$conn->close();
?>
