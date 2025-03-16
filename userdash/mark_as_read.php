<?php
session_start(); // Start session to check if the user is logged in

include('../db_connection.php');  // Include the database configuration

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];  // Get the logged-in user's ID

// Check if the message ID is sent via AJAX
if (isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];

    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the message status to 'read'
    $update_sql = "UPDATE messages SET `read` = 1 WHERE id = ? AND recipient_id = ?";

    if ($stmt = $conn->prepare($update_sql)) {
        $stmt->bind_param("ii", $message_id, $user_id);  // Bind the parameters as integers
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();

    echo "Message marked as read."; // Response sent to AJAX
} else {
    echo "No message ID provided.";
}
?>
