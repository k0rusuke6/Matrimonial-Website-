<?php
include 'db_connect.php'; // your db connection file
$query = $_GET['query'];

$stmt = $conn->prepare("SELECT user_id, full_name, gender, dob, marital_status, country, state, institution, occupation, profile_picture FROM users WHERE full_name LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("s", $query);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $dob = date_diff(date_create($row['dob']), date_create('today'))->y . " years"; // calculate age
    $profilePic = htmlspecialchars($row['profile_picture']);
    $fullName = htmlspecialchars($row['full_name']);

    echo '<div class="search-option" onclick="showProfileModal('
        . '\'' . addslashes($row['full_name']) . '\', '
        . '\'' . addslashes($row['gender']) . '\', '
        . '\'' . addslashes($dob) . '\', '
        . '\'' . addslashes($row['marital_status']) . '\', '
        . '\'' . addslashes($row['country']) . '\', '
        . '\'' . addslashes($row['state']) . '\', '
        . '\'' . addslashes($row['institution']) . '\', '
        . '\'' . addslashes($row['occupation']) . '\', '
        . '\'' . addslashes($profilePic) . '\');'
        . 'document.getElementById(\'searchOptions\').style.display=\'none\';">'
        . '<img src="' . $profilePic . '" alt="' . $fullName . '" style="width: 30px; height: 30px; border-radius: 50%; margin-right: 10px; vertical-align: middle;">'
        . $fullName
        . '</div>';
}

$stmt->close();
$conn->close();
?>
