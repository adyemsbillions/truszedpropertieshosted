<?php
// Include database connection
$servername = "localhost";  // Change this to your server
$username = "root";         // Change this to your database username
$password = "";             // Change this to your database password
$dbname = "truszed";       // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

// Get the property ID from the query string
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the property details from the database using prepared statement
$sql = "SELECT * FROM agent_properties WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Extract property details from the row
    $property_name = $row['property_name'];
    $price = $row['price'];
    $address = $row['address'];
    $dimensions = $row['dimensions'];
    $property_type = $row['property_type'];
    $bedrooms = $row['bedrooms'];
    $bathrooms = $row['bathrooms'];
    $toilets = $row['toilets'];
    $parking_space = $row['parking_space'];
    $post_image = "uploads/" . $row['post_image'];  // Path to the main image
    $other_images = $row['other_images'];  // CSV of other images (optional)
    $property_details = $row['property_details'];
    $market_status = $row['market_status'];
    $current_stars = $row['stars'];
    $agent_id = $row['agent_id'];

    // Check if agent_id is valid (not empty and a positive integer)
    if (!empty($agent_id) && is_numeric($agent_id) && $agent_id > 0) {
        if ($agent_id == 1) {
            // If agent_id is 1, set agent name to "adyemsbillions"
            $agent_name = "this propery is posted by the admin";
        } else {
            // Fetch agent's full name using prepared statement
            $agent_sql = "SELECT full_name FROM agent WHERE id = ?";
            $agent_stmt = $conn->prepare($agent_sql);
            $agent_stmt->bind_param("i", $agent_id);
            $agent_stmt->execute();
            $agent_result = $agent_stmt->get_result();
            if ($agent_result->num_rows > 0) {
                $agent_row = $agent_result->fetch_assoc();
                $agent_name = $agent_row['full_name']; // Get agent's full name
            } else {
                $agent_name = "Unavailable"; // Display message when agent is not found
            }
            $agent_stmt->close();
        }
    } else {
        // If agent_id is invalid or empty
        $agent_name = "Unavailable"; // Display message when agent_id is invalid
    }
} else {
    echo "Property not found.";
    exit;
}
$stmt->close();

// Handle the form submission to update the stars
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stars'])) {
    $user_stars = intval($_POST['stars']);

    // Update the stars in the database using prepared statement
    $update_sql = "UPDATE agent_properties SET stars = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $user_stars, $property_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to refresh the page and show the updated stars
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $property_id);
    exit;
}

// Handle the message sending functionality
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    // Get the message data from the POST request
    $message = trim($_POST['message']);
    if (!empty($message)) {
        // Sender ID is now taken from the session (user's ID)
        $sender_id = $user_id;
        $recipient_id = $row['agent_id'];  // Agent's ID from the property table
        $is_user_sender = true;  // User is sending the message

        // Insert the message into the database using prepared statement
        $stmt = $conn->prepare("INSERT INTO chat_messages (property_id, sender_id, recipient_id, message, is_user_sender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisi", $property_id, $sender_id, $recipient_id, $message, $is_user_sender);

        if ($stmt->execute()) {
            // Redirect back to the property detail page after sending the message
            header("Location: property_detail_sell.php?id=$property_id");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all messages for the property using prepared statement
$messages_sql = "SELECT * FROM chat_messages WHERE property_id = ? ORDER BY timestamp ASC";
$stmt = $conn->prepare($messages_sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$messages_result = $stmt->get_result();

// Check if messages exist
$messages = [];
if ($messages_result->num_rows > 0) {
    while ($row = $messages_result->fetch_assoc()) {
        $messages[] = $row;
    }
}
$stmt->close();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property_name); ?> - Property Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    /* Property Details Page */
    .property-details-page {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .property-details-page h1 {
        font-size: 28px;
        margin-bottom: 20px;
    }

    .property-details-page .property-image {
        width: 100%;
        max-width: 800px;
        margin-bottom: 20px;
    }

    .property-details-page .property-image img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .property-details-page .details {
        margin-bottom: 30px;
    }

    .property-details-page .details p {
        font-size: 16px;
        margin: 5px 0;
    }

    .property-details-page .other-images {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
    }

    .property-details-page .other-image {
        width: 100%;
        max-width: 200px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Star Rating Styles */
    .stars {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
    }

    .stars input {
        display: none;
    }

    .stars label {
        font-size: 30px;
        color: #ddd;
        cursor: pointer;
        transition: color 0.3s;
    }

    .stars label:hover,
    .stars input:checked~label {
        color: #ffb400;
    }

    .stars input:checked~label:hover,
    .stars input:checked~label:hover~label {
        color: #ffb400;
    }

    .property-details-page .back-btn {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background-color: black;
        color: goldenrod;
        text-decoration: none;
        border-radius: 5px;
    }

    .property-details-page .back-btn:hover {
        background-color: black;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 768px) {
        .property-details-page {
            width: 95%;
        }

        .property-details-page h1 {
            font-size: 24px;
        }

        .property-details-page .details p {
            font-size: 14px;
        }

        .property-details-page .other-image {
            width: 100%;
            max-width: 150px;
        }

        .property-details-page .property-image {
            max-width: 100%;
        }
    }

    /* Mobile-friendly images in the other-images section */
    @media (max-width: 480px) {
        .property-details-page .other-image {
            max-width: 120px;
            height: 120px;
        }

        .property-details-page .back-btn {
            font-size: 14px;
            padding: 8px 16px;
        }
    }

    /* Chat Styles */
    .chat-container {
        margin-top: 20px;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
    }

    .chat-messages {
        max-height: 300px;
        overflow-y: auto;
        margin-bottom: 10px;
        padding: 10px;
        background-color: #f9f9f9;
    }

    .chat-messages .message {
        margin: 5px 0;
        padding: 8px;
        border-radius: 5px;
    }

    .chat-messages .message.user {
        background-color: #007bff;
        color: white;
        text-align: right;
    }

    .chat-messages .message.agent {
        background-color: #e9ecef;
        text-align: left;
    }

    .chat-input {
        display: flex;
        gap: 10px;
    }

    .chat-input input {
        flex: 1;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .chat-input button {
        padding: 8px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .chat-input button:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="property-details-page">
        <h1><?php echo htmlspecialchars($property_name); ?></h1>
        <img src="http://localhost/truszed/agent/agent_dashboard/<?php echo htmlspecialchars($post_image); ?>"
            alt="Property Image" class="property-image">

        <div class="details">
            <p><strong>Price:</strong> ₦<?php echo number_format($price, 2); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
            <p><strong>Dimensions:</strong> <?php echo htmlspecialchars($dimensions); ?></p>
            <p><strong>Type:</strong> <?php echo ucfirst(htmlspecialchars($property_type)); ?></p>
            <p><strong>Bedrooms:</strong> <?php echo htmlspecialchars($bedrooms); ?></p>
            <p><strong>Bathrooms:</strong> <?php echo htmlspecialchars($bathrooms); ?></p>
            <p><strong>Agent Name:</strong> <?php echo htmlspecialchars($agent_name); ?></p>
            <p><strong>Toilets:</strong> <?php echo htmlspecialchars($toilets); ?></p>
            <p><strong>Parking Space:</strong> <?php echo htmlspecialchars($parking_space); ?> spaces</p>
            <p><strong>Market Status:</strong> <?php echo ucfirst(htmlspecialchars($market_status)); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($property_details)); ?></p>
        </div>

        <!-- Star Rating -->
        <div class="stars">
            <form method="POST" action="">
                <input type="radio" id="star5" name="stars" value="5"
                    <?php echo $current_stars == 5 ? 'checked' : ''; ?>>
                <label for="star5">★</label>
                <input type="radio" id="star4" name="stars" value="4"
                    <?php echo $current_stars == 4 ? 'checked' : ''; ?>>
                <label for="star4">★</label>
                <input type="radio" id="star3" name="stars" value="3"
                    <?php echo $current_stars == 3 ? 'checked' : ''; ?>>
                <label for="star3">★</label>
                <input type="radio" id="star2" name="stars" value="2"
                    <?php echo $current_stars == 2 ? 'checked' : ''; ?>>
                <label for="star2">★</label>
                <input type="radio" id="star1" name="stars" value="1"
                    <?php echo $current_stars == 1 ? 'checked' : ''; ?>>
                <label for="star1">★</label>
                <br>
                <button type="submit" class="back-btn">Submit Rating</button>
            </form>
        </div>

        <h3>Other Images</h3>
        <div class="other-images">
            <?php
            if (!empty($other_images)) {
                // Split the CSV of other images into an array
                $other_images_array = explode(',', $other_images);
                foreach ($other_images_array as $image) {
                    echo '<img src="http://localhost/truszed/agent/agent_dashboard/uploads/' . htmlspecialchars(trim($image)) . '" alt="Other Image" class="other-image">';
                }
            }
            ?>
        </div>

        <!-- Chat Section -->
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

        <a href="login/login.php" class="back-btn">Login And Explore</a>
        <a href="index.php" class="back-btn">Back to Properties</a>
    </div>
</body>

</html>