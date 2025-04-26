<?php
session_start();

include('../db_connection.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$sql = "SELECT id, name FROM users";
$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = $_POST['message'];
    $user_id = $_POST['user_id'];
    $link = $_POST['link'];

    if (!empty($message)) {
        $sender_id = $_SESSION['admin_id'];

        if ($user_id == 'all') {
            $send_sql = "SELECT id FROM users";
            $send_result = $conn->query($send_sql);
            while ($row = $send_result->fetch_assoc()) {
                $recipient_id = $row['id'];
                $insert_sql = "INSERT INTO messages (sender_id, recipient_id, message, link) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                $stmt->bind_param("iiss", $sender_id, $recipient_id, $message, $link);
                $stmt->execute();
            }
            $success_message = "Message sent to all users.";
        } else {
            $insert_sql = "INSERT INTO messages (sender_id, recipient_id, message, link) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iiss", $sender_id, $user_id, $message, $link);
            $stmt->execute();
            $success_message = "Message sent to the selected user.";
        }
    } else {
        $success_message = "Please enter a message to send.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f8f0fc, #ebe1f7);
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .form-container {
        background: #fff;
        padding: 30px 25px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
    }

    h1 {
        text-align: center;
        color: #5e2b97;
        margin-bottom: 25px;
        font-size: 28px;
    }

    form {
        display: flex;
        flex-direction: column;
    }

    .form-select,
    .form-textarea,
    .form-input {
        padding: 12px 14px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        background-color: #fafafa;
        outline-color: #5e2b97;
    }

    .form-textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-btn {
        background-color: #5e2b97;
        color: white;
        padding: 12px 20px;
        font-size: 17px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-btn:hover {
        background-color: #7b1fa2;
    }

    .success-message {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 20px;
        font-size: 16px;
    }

    @media (max-width: 600px) {
        .form-container {
            padding: 20px 15px;
        }

        h1 {
            font-size: 24px;
        }

        .form-select,
        .form-textarea,
        .form-input {
            font-size: 14px;
        }

        .form-btn {
            font-size: 15px;
        }
    }
    </style>
</head>

<body>

    <div class="form-container">
        <h1>Send Message to Users</h1>

        <?php if (!empty($success_message)): ?>
        <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form action="messages-users.php" method="POST">
            <select name="user_id" class="form-select" required>
                <option value="all">Send to All Users</option>
                <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlspecialchars($user['id']); ?>">
                    <?php echo htmlspecialchars($user['name']); ?></option>
                <?php endforeach; ?>
            </select>

            <textarea name="message" class="form-textarea" placeholder="Enter your message..." required></textarea>

            <input type="text" name="link" class="form-input" placeholder="Optional link (e.g., https://example.com)">

            <button type="submit" class="form-btn">Send Message</button>
        </form>
    </div>

</body>

</html>