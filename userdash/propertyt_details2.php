<?php
// Database connection details
include('../db_connection.php');
// Get the property ID from the URL
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the property details based on the ID
$sql = "SELECT * FROM agent_properties WHERE id = $property_id";
$result = $conn->query($sql);

// Check if the property exists
if ($result->num_rows > 0) {
    // Fetch the property details
    $property = $result->fetch_assoc();
} else {
    echo "Property not found.";
    exit();
}
// Handle the form submission to update the stars
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stars'])) {
    $user_stars = $_POST['stars'];
    
    // Update the stars in the database
    $update_sql = "UPDATE agent_properties SET stars = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $user_stars, $property_id);
    $stmt->execute();
    
    // Redirect to refresh the page and show the updated stars
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $property_id);
    exit;
}

$conn->close();
?>
<?php
// Include the database connection
include '../db_connection.php';

// Start session to access user data
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if user is not logged in
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Get the property ID from the URL
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the property details based on the ID
$sql = "SELECT * FROM agent_properties WHERE id = $property_id";
$result = $conn->query($sql);

// Check if the property exists
if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
} else {
    echo "Property not found.";
    exit();
}

// Handle the message sending functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the message data from the POST request
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    if (!empty($message)) {
        // Sender ID is now taken from the session (user's ID)
        $sender_id = $user_id;
        $recipient_id = $property['agent_id'];  // Agent's ID from the property table
        $is_user_sender = true;  // User is sending the message

        // Insert the message into the database
        $stmt = $conn->prepare("INSERT INTO chat_messages (property_id, sender_id, recipient_id, message, is_user_sender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisi", $property_id, $sender_id, $recipient_id, $message, $is_user_sender);

        if ($stmt->execute()) {
            // Redirect back to the property detail page after sending the message
            header("Location: property_detail_sell.php?id=$property_id");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Fetch all messages for the property
$messages_sql = "SELECT * FROM chat_messages WHERE property_id = $property_id ORDER BY timestamp ASC";
$messages_result = $conn->query($messages_sql);

// Check if messages exist
$messages = [];
if ($messages_result->num_rows > 0) {
    while ($row = $messages_result->fetch_assoc()) {
        $messages[] = $row;
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        header {
            background-color:goldenrod; /* Purple color */
            color: black;
            padding: 20px 0;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .property-detail {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            display: flex;
            flex-wrap: wrap;
        }

        .property-detail img {
            width: 100%;
            max-width: 400px;
            height: auto;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 30px;
        }

        .property-detail .info {
            flex: 1;
        }

        .property-detail h2 {
            font-size: 2rem;
            color: #333;
        }

        .property-detail .price {
            font-size: 1.5rem;
            color: goldenrod; /* Purple color */
            margin-top: 10px;
            font-weight: bolder;
        }

        .property-detail .details {
            margin-top: 15px;
            color: #777;
        }

        .property-detail .description {
            margin-top: 20px;
            font-size: 1.1rem;
            line-height: 1.6;
        }

        .property-detail .gallery {
            margin-top: 30px;
        }

        .property-detail .gallery img {
            width: 100%;
            max-width: 250px;
            height: auto;
            margin: 10px;
            border-radius: 8px;
            object-fit: cover;
        }

        .back-button {
            display: inline-block;
            margin-top: 20px;
            background-color: black; /* Purple color */
            color: goldenrod;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: black; /* Darker purple for hover effect */
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .property-detail {
                flex-direction: column;
                align-items: center;
            }

            .property-detail img {
                max-width: 100%;
                margin-right: 0;
                margin-bottom: 20px;
            }

            .property-detail .info {
                text-align: center;
            }

            .property-detail .gallery img {
                width: 100%;
            }
        }
        
.chat-container {
            margin-top: 30px;
            background-color: #fff;
            padding: -10px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1)
            
        }

        .chat-messages {
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .message {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .message .sender {
            font-weight: bold;
        }

        .message .content {
            margin-top: 5px;
        }

        .message.user {
            text-align: right;
        }

        .message.agent {
            text-align: left;
        }

        .chat-input {
            display: flex;
            gap: 10px;
        }

        .chat-input input[type="text"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .chat-input button {
            background-color:black; /* Purple */
            color: goldenrod;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
        }

        .chat-input button:hover {
            background-color: #4a148c;
        }
    </style>
</head>
<body>

<header>
    <h1>Property Details</h1>
</header>

<div class="container">
    <div class="property-detail">
        <!-- Property Image -->
        <img src="/truszed/agent/agent_dashboard/uploads/<?php echo $property['post_image']; ?>" alt="Property Image">
        
        <div class="info">
            <h2><?php echo htmlspecialchars($property['property_name']); ?></h2>
            <p class="price">$<?php echo number_format($property['price']); ?>/month</p>

            <div class="details">
                <p><strong>Address:</strong> <?php echo isset($property['address']) ? htmlspecialchars($property['address']) : 'Not available'; ?></p>
                <p><strong>Size:</strong> <?php echo isset($property['dimensions']) ? htmlspecialchars($property['dimensions']) : 'Not available'; ?></p>
                <p><strong>Bedrooms:</strong> <?php echo $property['bedrooms']; ?> | <strong>Bathrooms:</strong> <?php echo $property['bathrooms']; ?> | <strong>Toilets:</strong> <?php echo $property['toilets']; ?></p>
                <p><strong>Parking Spaces:</strong> <?php echo $property['parking_space']; ?></p>
            </div>

            <div class="description">
                <h3>Description</h3>
                <p><?php echo isset($property['property_details']) ? nl2br(htmlspecialchars($property['property_details'])) : 'No description available.'; ?></p>
            </div>

            <!-- Additional Images -->
            <?php if (!empty($property['other_images'])): ?>
                <div class="gallery">
                    <h3>Gallery</h3>
                    <?php
                    $other_images = explode(',', $property['other_images']);
                    foreach ($other_images as $image) {
                        echo '<img src="/truszed/agent/agent_dashboard/uploads/' . trim($image) . '" alt="Additional Image">';
                    }
                    ?>
                </div>
            <?php endif; ?>
            <div class="chat-container">
                <div class="chat-messages">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $message): ?>
                            <div class="message <?php echo $message['is_user_sender'] ? 'user' : 'agent'; ?>">
                                <span class="sender">
                                    <?php echo $message['is_user_sender'] ? 'You' : 'Agent'; ?>:
                                </span>
                                <span class="content"><?php echo htmlspecialchars($message['message']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No messages yet.</p>
                    <?php endif; ?>
                </div>

                <form action="property_detail_sell.php?id=<?php echo $property_id; ?>" method="POST" class="chat-input">
                    <input type="text" name="message" placeholder="Type your message..." required>
                    <button type="submit">Send</button>
                </form>
            </div>
            <!-- Back Button -->
            <a href="index.php" class="back-button">Back to Listings</a>
        </div>
    </div>
</div>

</body>
</html>
