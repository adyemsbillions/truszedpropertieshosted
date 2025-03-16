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

// Initialize error variable
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $identifier = mysqli_real_escape_string($conn, trim($_POST['identifier'])); // Email or phone number
    $password = trim($_POST['password']);
    
    // Validate inputs
    if (empty($identifier) || empty($password)) {
        $error = "Both fields are required!";
    } else {
        // Check if the identifier is an email or phone number
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // It is an email, check the database for email
            $stmt = $conn->prepare("SELECT id, name, email, phone_number, password FROM users WHERE email = ?");
        } elseif (preg_match('/^\+?\d{10,15}$/', $identifier)) { // Allow phone numbers with optional "+" and 10-15 digits
            // It is a phone number, check the database for phone number
            $stmt = $conn->prepare("SELECT id, name, email, phone_number, password FROM users WHERE phone_number = ?");
        } else {
            $error = "Invalid email or phone number format!";
        }

        if (isset($stmt)) {
            // Bind and execute the query
            $stmt->bind_param("s", $identifier);  // Bind the identifier (email or phone number)
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $name, $email, $phone_number, $hashedPassword);

            if ($stmt->num_rows > 0) {
                // User found, now verify the password
                $stmt->fetch();  // Fetch the result row

                if (password_verify($password, $hashedPassword)) {
                    // Password is correct, login success
                    // Start the session to store user info
                    session_start();
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_phone_number'] = $phone_number;

                    // Redirect to a logged-in page (dashboard or home page)
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Incorrect password!";
                }
            } else {
                $error = "No user found with this email or phone number!";
            }
            // Close the prepared statement
            $stmt->close();
        }
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
    <title>Truszed Properties | Find Your Dream Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container flex">
        <div class="facebook-page flex">
            <div class="text">
                <h1>Truszed</h1>
                <p>Find your dream home with us.</p>
                <!-- <p>Join our community and start your journey.</p> -->
            </div>
            
            <form action="register.php" method="POST"> <!-- Form submission to the same page -->
                
                <!-- Display error message if exists -->
                <?php if ($error): ?>
                    <div style="color: red; font-weight: bold; margin-bottom: 15px;"><?= $error ?></div>
                <?php endif; ?>
                
                <!-- Name input -->
                <input type="text" name="name" placeholder="Enter your name" required>

                <!-- Email input -->
                <input type="email" name="email" placeholder="Enter your Email" required>

                <!-- Phone number input -->
                <input type="tel" name="phone_number" placeholder="Phone number" required   pattern="\d{11}">
              
                <!-- Password input -->
                <input type="password" name="password" placeholder="Password" required>

                <!-- Submit button -->
                <div class="link">
                    <button type="submit" class="login">Signup</button>
                    <a href="#" class="forgot">Forgot password?</a>
                </div>

                <hr>

                <!-- Link to registration page -->
                <div class="button">
                    <a href="login.php">Already have an account? Login</a>
                </div>

            </form>
        </div>
    </div>
</body>
</html>
