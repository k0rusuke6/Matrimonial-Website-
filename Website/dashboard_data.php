<?php
include 'db_connect.php';

// Fetch total users
$totalUsersQuery = "SELECT COUNT(*) AS total FROM users";
$totalUsers = $conn->query($totalUsersQuery)->fetch_assoc()['total'] ?? 0;

// Fetch approved profiles
$approvedProfilesQuery = "SELECT COUNT(*) AS approved FROM users WHERE status = 'approved'";
$approvedProfiles = $conn->query($approvedProfilesQuery)->fetch_assoc()['approved'] ?? 0;

// Fetch active users
$activeUsersQuery = "SELECT COUNT(*) AS active FROM users WHERE status = 'rejected'";
$activeUsers = $conn->query($activeUsersQuery)->fetch_assoc()['active'] ?? 0;

// Fetch pending profiles
$pendingProfilesQuery = "SELECT COUNT(*) AS pending FROM users WHERE status = 'pending'";
$pendingProfiles = $conn->query($pendingProfilesQuery)->fetch_assoc()['pending'] ?? 0;

// Fetch rejected profiles
$rejectedProfilesQuery = "SELECT COUNT(*) AS rejected FROM users WHERE status = 'rejected'";
$rejectedProfiles = $conn->query($rejectedProfilesQuery)->fetch_assoc()['rejected'] ?? 0;

// Fetch gender ratio
$maleCountQuery = "SELECT COUNT(*) AS male FROM users WHERE gender = 'male'";
$femaleCountQuery = "SELECT COUNT(*) AS female FROM users WHERE gender = 'female'";

$maleCount = $conn->query($maleCountQuery)->fetch_assoc()['male'] ?? 0;
$femaleCount = $conn->query($femaleCountQuery)->fetch_assoc()['female'] ?? 0;

$maleToFemaleRatio = "$maleCount:$femaleCount";


// Return JSON data
header('Content-Type: application/json');
echo json_encode([
    'total_users' => $totalUsers,
    'active_users' => $activeUsers,
    'approved_profiles' => $approvedProfiles,
    'pending_profiles' => $pendingProfiles,
    'rejected_profiles' => $rejectedProfiles,
    'male_to_female_ratio' => $maleToFemaleRatio
]);
?>
