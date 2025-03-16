<?php
// Include the database connection
$servername = "localhost";  // Database server
$username = "root";         // Database username
$password = "";             // Database password
$dbname = "truszed";        // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch contact data from the database
$sql = "SELECT * FROM contact_us ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="style.css"> <!-- You can add your styles here -->
</head>
<body>
    <h1>Messages From Our Visitors</h1>

    <!-- Display Submitted Messages -->
    <?php
    // Check if there are any messages in the database
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<strong>Name:</strong> " . $row['name'] . "<br>";
            echo "<strong>Email:</strong> " . $row['email'] . "<br>";
            echo "<strong>Subject:</strong> " . $row['subject'] . "<br>";
            echo "<strong>Message:</strong> " . $row['message'] . "<br><br>";
            echo "<strong>Time sent:</strong> " . $row['created_at'] . "<br>";
            echo "</div>";
        }
    } else {
        echo "No messages to display.";
    }

    // Close the connection
    $conn->close();
    ?>
</body>
</html>
