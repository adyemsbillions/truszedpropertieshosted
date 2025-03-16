<?php
// db_connection.php

$servername = "localhost";
$username = "root";  // Your database username
$password = "";      // Your database password
$dbname = "truszed"; // Your database name

// Create connection
$db_connection = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db_connection->connect_error) {
    die("Connection failed: " . $db_connection->connect_error);
}
?>
