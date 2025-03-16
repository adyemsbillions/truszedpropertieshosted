<?php
// Database connection details
include('../db_connection.php');
// Get the message data from the POST request
$property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
$recipient_id = isset($_POST['recipient_id']) ? intval($_POST['recipient_id']) : 0;
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Assuming user is sending the message, set user_sender as TRUE
$user_id = 1;  // Replace with the actual logged-in user ID
$is_user_sender = true;  // Set to FALSE if the agent sends the message

// Insert the message into the database
$sql = "INSERT INTO chat_messages (property_id, sender_id, recipient_id, message, is_user_sender)
        VALUES ($property_id, $user_id, $recipient_id, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $message, $is_user_sender);

if ($stmt->execute()) {
    header("Location: property_detail.php?id=$property_id");  // Redirect back to the property detail page
} else {
    echo "Error: " . $stmt->error;
}

// Close the database connection
$conn->close();
?>
