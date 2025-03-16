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
    $identifier = mysqli_real_escape_string($conn, trim($_POST['identifier']));  // Email or phone number
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($identifier) || empty($password)) {
        $error = "Both fields are required!";
    } else {
        // Check if the identifier is an email or phone number
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // It is an email, check the database for email
            $stmt = $conn->prepare("SELECT id, name, email, phone_number, password FROM users WHERE email = ?");
        } elseif (preg_match('/^\d{10}$/', $identifier)) {
            // It is a phone number, check the database for phone number
            $stmt = $conn->prepare("SELECT id, name, email, phone_number, password FROM users WHERE phone_number = ?");
        } else {
            $error = "Invalid email or phone number format!";
        }

        if (isset($stmt)) {
            $stmt->bind_param("s", $identifier);  // Bind the parameter (email or phone number)
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
                    header("Location: /truszed/userdash/userdash.php");
                    exit();
                } else {
                    $error = "Incorrect password!";
                }
            } else {
                $error = "No user found with this email or phone number!";
            }
            // Close the statement
            $stmt->close();
        }
    }
}

// Close the connection
$conn->close();
?>


<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facebook Login Page | CodingNepal</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="container flex">
      <div class="facebook-page flex">
        <div class="text">
          <h1>Truszed</h1>
          <p>Lets Help you find your dream home</p>
          
        </div>
        <form action="login.php" method="POST">
                
                <!-- Display error message if exists -->
                <?php if (isset($error) && $error): ?>
                    <div style="color: red; font-weight: bold; margin-bottom: 15px;"><?= $error ?></div>
                <?php endif; ?>
                
                <!-- Input for Email or Phone number -->
                <input type="text" name="identifier" placeholder="Email or Phone number" required>
                
                <!-- Input for Password -->
                <input type="password" name="password" placeholder="Password" required>

                <!-- Forgot password link and login button -->
                <div class="link">
                    <button type="submit" class="login">Login</button>
                    <a href="forgot_password.php" class="forgot">Forgot password?</a>
                </div>

                <hr>

                <!-- Link to Register if the user does not have an account -->
                <div class="button">
                    <a href="register.php">Create a new account</a>
                </div>

            </form>
      </div>
    </div>
  </body>
</html>