<?php
// Database connection parameters
$servername = "localhost";  // Database server
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "truszed";        // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8 for correct encoding
$conn->set_charset("utf8");

// Return the connection object
return $conn;
?>
