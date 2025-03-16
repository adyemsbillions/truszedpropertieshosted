<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Admin is logged in, proceed with the page content
echo "Welcome, " . $_SESSION['admin_username'];
?>
