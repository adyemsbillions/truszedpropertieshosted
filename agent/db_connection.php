<?php
// db_connection.php

$servername = "localhost"; // Hostname, typically "localhost"
$username = "root";        // Your MySQL username (default is "root")
$password = "";            // Your MySQL password (default is empty for XAMPP)
$dbname = "truszed"; // Replace with your actual database name

// Create connection
$db_connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db_connection->connect_error) {
    die("Connection failed: " . $db_connection->connect_error);
}
?>
