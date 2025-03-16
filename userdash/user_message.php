<?php
session_start(); // Start session to check if the user is logged in

include('../db_connection.php');  // Include the database configuration

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];  // Get the logged-in user's ID

// Initialize unread_count with a default value of 0
$unread_count = 0;

// SQL query to count unread messages (only those with `read = 0`)
$sql = "SELECT COUNT(*) AS unread_count FROM messages WHERE recipient_id = ? AND `read` = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($unread_count);
$stmt->fetch();
$stmt->close();

// SQL query to get all messages for the user (including messages sent to all users)
$sql_messages = "SELECT m.id, m.sender_id, m.message, m.link, m.sent_at, m.read, a.username AS sender_name
        FROM messages m
        LEFT JOIN admin a ON m.sender_id = a.id
        WHERE m.recipient_id = ? OR m.recipient_id IS NULL
        ORDER BY m.sent_at DESC"; 

$stmt_messages = $conn->prepare($sql_messages);
$stmt_messages->bind_param("i", $user_id);
$stmt_messages->execute();
$result = $stmt_messages->get_result();

$messages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

$stmt_messages->close();
$conn->close();

// Function to display link if available
function displayLink($link) {
    if ($link) {
        return "<br><a href='$link' target='_blank'>Click here to visit the link</a>";
    }
    return "";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Messages</title>
    <link rel="stylesheet" href="styles.css">
    <style>
   body {
    font-family: Arial, sans-serif;
    background-color: #fff;
    color:black;
    padding: 40px;
    margin: 0;
}

.messages-container {
    max-width: 800px;
    margin: auto;
    padding: 30px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color:goldenrod;
    font-size: 2rem; /* Make the font size larger */
    margin-bottom: 20px;
}

.message {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    margin-bottom: 10px;
    background-color: #f1f1f1;
    border-radius: 5px;
}

.message p {
    margin: 0;
}

.message .sender-name {
    font-weight: bold;
    color: #4e148c;
}

.message .sent-at {
    font-size: 0.9em;
    color: #777;
}

.message .view-message-btn {
    background-color: black;
    color: goldenrod;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1em;
    margin-top: 10px;
    display: inline-block;
    transition: background-color 0.3s ease;
}

.message .view-message-btn:hover {
    background-color: #7b1fa2;
}

.back-btn {
    display: inline-block;
    background-color:black;
    color: goldenrod;
    padding: 10px 20px;
    text-decoration: none;
    font-size: 1.2em;
    margin-top: 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.back-btn:hover {
    background-color: #7b1fa2;
}

.message-count {
    font-size: 1.2em;
    font-weight: bold;
    color: red;
    margin-left: 10px;
}

/* Media Queries for Responsiveness */
@media (max-width: 768px) {
    body {
        padding: 20px;
    }

    .messages-container {
        padding: 20px;
    }

    h1 {
        font-size: 1.8rem;
    }

    .message {
        padding: 10px;
    }

    .message .view-message-btn {
        font-size: 0.9em;
        padding: 6px 12px;
    }

    .back-btn {
        font-size: 1em;
        padding: 8px 16px;
    }

    .message-count {
        font-size: 1.1em;
    }
}

@media (max-width: 480px) {
    body {
        padding: 15px;
    }

    .messages-container {
        padding: 15px;
    }

    h1 {
        font-size: 1.5rem;
    }

    .message {
        padding: 8px;
    }

    .message .view-message-btn {
        font-size: 0.8em;
        padding: 6px 12px;
    }

    .back-btn {
        font-size: 0.9em;
        padding: 8px 16px;
    }

    .message-count {
        font-size: 1em;
    }
}

    </style>
</head>
<body>
    <div class="messages-container">
       
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message <?php echo $message['read'] == 1 ? 'read' : ''; ?>" id="message-<?php echo $message['id']; ?>">
                    <p class="sender-name">From: <?php echo $message['sender_name']; ?> (Admin)</p>
                    <p class="message-content"><?php echo $message['message']; ?></p>
                    <p class="sent-at">Sent at: <?php echo date("F j, Y, g:i a", strtotime($message['sent_at'])); ?></p>
                    
                    <?php if ($message['read'] == 0): ?>
                        <button class="view-message-btn" onclick="markAsRead(<?php echo $message['id']; ?>)">mark as read</button>
                    <?php else: ?>
                        <h6 class="read-status">You have already read this message.</h6>
                    <?php endif; ?>

                    <?php echo displayLink($message['link']); // Display link if available ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No messages found.</p>
        <?php endif; ?>

        <a href="userdash.php" class="back-btn">Back to Dashboard</a>
    </div>

    <script>
        // AJAX function to mark message as read
        function markAsRead(messageId) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "mark_as_read.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status == 200) {
                    // If the message is successfully marked as read, update the UI
                    document.getElementById("message-" + messageId).classList.add("read");
                    document.getElementById("messageCount").innerText = parseInt(document.getElementById("messageCount").innerText) - 1;
                
                } else {
                    alert("Failed to mark the message as read.");
                }
            };
            xhr.send("message_id=" + messageId);
        }
    </script>
</body>
</html>
