<?php
// Include the database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "truszed";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM contact_us ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Messages</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f0fa;
        color: #333;
    }

    h1 {
        text-align: center;
        padding: 20px 10px;
        background-color: #5e2b97;
        color: #fff;
        margin-bottom: 30px;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .message-card {
        background-color: #fff;
        border-left: 6px solid #5e2b97;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.2s;
    }

    .message-card:hover {
        transform: translateY(-5px);
    }

    .message-card strong {
        color: #5e2b97;
    }

    .no-messages {
        text-align: center;
        color: #888;
        font-size: 18px;
        margin-top: 50px;
    }

    @media (max-width: 600px) {
        h1 {
            font-size: 24px;
        }

        .message-card {
            padding: 15px;
        }
    }
    </style>
</head>

<body>

    <h1>Messages From Our Visitors</h1>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='message-card'>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                echo "<p><strong>Subject:</strong> " . htmlspecialchars($row['subject']) . "</p>";
                echo "<p><strong>Message:</strong> " . nl2br(htmlspecialchars($row['message'])) . "</p>";
                echo "<p><strong>Time sent:</strong> " . htmlspecialchars($row['created_at']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-messages'>No messages to display.</p>";
        }

        $conn->close();
        ?>
    </div>

</body>

</html>