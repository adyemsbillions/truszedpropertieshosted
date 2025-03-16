<?php
// Database connection parameters
$servername = "localhost";  // Replace with your server
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "truszed";        // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the token from the URL
$token = $_GET['token'];

// Check if the token exists in the database
$stmt = $conn->prepare("SELECT id FROM users WHERE activation_token = ? AND is_active = 0");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .success {
            color: green;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .error {
            color: red;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            background-color: goldenrod;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: bold;
        }
        .button:hover {
            background-color: darkgoldenrod;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($stmt->num_rows > 0) {
            // Activate the user's account
            $stmt = $conn->prepare("UPDATE users SET is_active = 1, activation_token = NULL WHERE activation_token = ?");
            $stmt->bind_param("s", $token);
            
            if ($stmt->execute()) {
                echo "<h1>Account Activated!</h1>";
                echo "<p class='success'>Your account has been activated successfully. You can now log in.</p>";
                echo "<a href='login.php' class='button'>Go to Login</a>";
            } else {
                echo "<h1>Error</h1>";
                echo "<p class='error'>There was an error activating your account. Please try again!</p>";
            }
        } else {
            echo "<h1>Invalid Token</h1>";
            echo "<p class='error'>Invalid or expired activation token!</p>";
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
        ?>
    </div>
</body>
</html>
