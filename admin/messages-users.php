<?php
session_start(); // Start session to check if the admin is logged in

include('../db_connection.php');  // Include the database configuration

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users for the dropdown
$sql = "SELECT id, name FROM users";
$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$success_message = ''; // Initialize the success message variable

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $user_id = $_POST['user_id'];
    $link = $_POST['link'];  // Get the link from the form

    // Check if the message is not empty
    if (!empty($message)) {
        $sender_id = $_SESSION['admin_id']; // Get the admin ID

        if ($user_id == 'all') {
            // Send to all users
            $send_sql = "SELECT id FROM users";
            $send_result = $conn->query($send_sql);
            while ($row = $send_result->fetch_assoc()) {
                // Insert message for each user along with the link
                $recipient_id = $row['id'];
                $insert_sql = "INSERT INTO messages (sender_id, recipient_id, message, link) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("iiss", $sender_id, $recipient_id, $message, $link);
                $stmt->execute();
            }
            $success_message = "Message sent to all users."; // Success message
        } else {
            // Send to the selected user
            $insert_sql = "INSERT INTO messages (sender_id, recipient_id, message, link) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iiss", $sender_id, $user_id, $message, $link);
            $stmt->execute();
            $success_message = "Message sent to the selected user."; // Success message
        }
    } else {
        $success_message = "Please enter a message to send."; // Error message if no message
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #4e148c;
            padding: 40px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4e148c;
        }

        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-btn {
            display: inline-block;
            background-color: #4e148c;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-btn:hover {
            background-color: #7b1fa2;
        }

        .success-message {
            margin: 20px 0;
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            text-align: center;
        }

        .error-message {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Send Message to Users</h1>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form action="messages-users.php" method="POST">
            <!-- Dropdown to select user or "Send to All Users" -->
            <select name="user_id" class="form-select" required>
                <option value="all">Send to All Users</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo $user['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <!-- Message Input -->
            <textarea name="message" class="form-textarea" rows="5" placeholder="Enter your message..." required></textarea>

            <!-- Optional Link Input -->
            <input type="text" name="link" class="form-input" placeholder="Optional link" />

            <!-- Send Button -->
            <button type="submit" class="form-btn">Send Message</button>
        </form>
    </div>
</body>
</html>
