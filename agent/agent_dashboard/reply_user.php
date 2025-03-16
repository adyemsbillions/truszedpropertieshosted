<?php
// Start session
session_start();

// Include database connection
require_once '../db_connection.php';

// Check if the agent is logged in
if (!isset($_SESSION['agent_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch the logged-in agent's ID from the session
$agent_id = $_SESSION['agent_id'];

// Fetch agent data from the database
$sql = "SELECT * FROM agent WHERE id = ?";
if ($stmt = $db_connection->prepare($sql)) {
    $stmt->bind_param("i", $agent_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the agent's data
        $row = $result->fetch_assoc();
    } else {
        echo "Agent not found.";
        exit();
    }
} else {
    echo "Error with database query.";
    exit();
}

// Fetch properties posted by the agent from the agent_properties table
$sql = "SELECT * FROM agent_properties WHERE agent_id = ?";
if ($stmt = $db_connection->prepare($sql)) {
    $stmt->bind_param("i", $agent_id);
    $stmt->execute();
    $properties_result = $stmt->get_result();

    $properties = [];
    if ($properties_result->num_rows > 0) {
        while ($property_row = $properties_result->fetch_assoc()) {
            $properties[] = $property_row;
        }
    }
} else {
    echo "Error fetching properties.";
    exit();
}

// Handle the message sending functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $property_id = isset($_POST['property_id']) ? intval($_POST['property_id']) : 0;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

    if (!empty($message) && $property_id > 0 && $user_id > 0) {
        // The agent is replying to the user
        $sender_id = $agent_id;  // Agent is sending the message
        $recipient_id = $user_id;  // User is the recipient
        $is_user_sender = false;  // Agent replies, so this is false

        // Insert the new message into the database
        $stmt = $db_connection->prepare("INSERT INTO chat_messages (property_id, sender_id, recipient_id, message, is_user_sender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisi", $property_id, $sender_id, $recipient_id, $message, $is_user_sender);

        if ($stmt->execute()) {
            // Redirect to the same page to reload the messages after sending the reply
            header("Location: reply_user.php");
            exit();
        } else {
            echo "Error sending message.";
        }
    }
}

// Fetch messages for each property
$messages_for_properties = [];

foreach ($properties as $property) {
    $property_id = $property['id'];
    
    // Fetch messages for this property
    $messages_sql = "SELECT * FROM chat_messages WHERE property_id = ? ORDER BY timestamp ASC";
    if ($stmt = $db_connection->prepare($messages_sql)) {
        $stmt->bind_param("i", $property_id);
        $stmt->execute();
        $messages_result = $stmt->get_result();

        $messages = [];
        if ($messages_result->num_rows > 0) {
            while ($message_row = $messages_result->fetch_assoc()) {
                $messages[] = $message_row;
            }
        }
        $messages_for_properties[$property_id] = $messages;
    } else {
        echo "Error fetching messages for property $property_id.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Reply to User</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #6a1b9a;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 22px;
        }

        h3 {
            color: #444;
            font-size: 20px;
            margin-top: 20px;
        }

        h4 {
            color: #6a1b9a;
            margin-top: 10px;
        }

        .property {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .property-info {
            margin-bottom: 20px;
        }

        .message-container {
            margin-top: 20px;
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            max-height: 300px;
            overflow-y: auto;
        }

        .message {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            background-color: #fff;
        }

        .user-message {
            background-color: #cce5ff;
            text-align: left;
        }

        .agent-message {
            background-color: #e6e6e6;
            text-align: right;
        }

        .reply-form {
            display: flex;
            flex-direction: column;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: vertical;
            min-height: 100px;
            box-sizing: border-box;
        }

        .submit-button {
            padding: 10px 20px;
            background-color: #6a1b9a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 150px;
            align-self: flex-start;
        }

        .submit-button:hover {
            background-color: #4a148c;
        }

        .no-messages {
            color: #888;
            text-align: center;
        }

        .message-list {
            list-style: none;
            padding-left: 0;
        }
        
        .message-list li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<header>
    <h1>Welcome, <?php echo htmlspecialchars($row['full_name']); ?>!</h1>
</header>

<div class="container">
    <h2>Properties You Have Posted</h2>
    <?php if (!empty($properties)): ?>
        <?php foreach ($properties as $property): ?>
            <div class="property">
                <h3><?php echo htmlspecialchars($property['property_name']); ?></h3>
                <div class="property-info">
                    <p><strong>Price:</strong> $<?php echo number_format($property['price']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($property['address']); ?></p>
                    <p><strong>Size:</strong> <?php echo htmlspecialchars($property['dimensions']); ?></p>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($property['property_details'])); ?></p>
                </div>

                <!-- Messages -->
                <div class="message-container">
                    <?php if (!empty($messages_for_properties[$property['id']])): ?>
                        <ul class="message-list">
                            <?php foreach ($messages_for_properties[$property['id']] as $message): ?>
                                <li class="message <?php echo $message['is_user_sender'] == 1 ? 'user-message' : 'agent-message'; ?>">
                                    <strong><?php echo ($message['is_user_sender'] == 1) ? 'User' : 'Agent'; ?>:</strong>
                                    <p><?php echo htmlspecialchars($message['message']); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="no-messages">No messages yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Reply Form -->
                <h4>Reply to User</h4>
                <form method="POST" action="" class="reply-form">
                    <textarea name="message" placeholder="Type your reply..." required></textarea>
                    <input type="hidden" name="property_id" value="<?php echo $property['id']; ?>">
                    <input type="hidden" name="user_id" value="1"> <!-- Replace with actual user ID -->
                    <button type="submit" class="submit-button">Send Reply</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No properties posted yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
