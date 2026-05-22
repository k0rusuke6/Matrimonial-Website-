<?php
session_start();
session_destroy();

// Prevent browser from caching the logged-in page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

// Redirect to login page
header("Location: login.php");
exit;
?>
