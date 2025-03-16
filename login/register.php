<?php
// Include Composer's autoloader
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $phone_number = mysqli_real_escape_string($conn, trim($_POST['phone_number']));
    $password = trim($_POST['password']);
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($phone_number) || empty($password)) {
        $error = "All fields are required!";
    } else {
        // Check if email or phone number already exists in the database
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR phone_number = ?");
        $stmt->bind_param("ss", $email, $phone_number);  // Check both email and phone number
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email or Phone number already exists!";
        } else {
            // Hash the password before saving to the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Generate a unique activation token
            $activationToken = bin2hex(random_bytes(50));
            
            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, password, activation_token) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $phone_number, $hashedPassword, $activationToken);
            
            if ($stmt->execute()) {
                // Send activation email using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();                                            // Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                        // Set the SMTP server (e.g., smtp.gmail.com)
                    $mail->SMTPAuth   = true;                                    // Enable SMTP authentication
                    $mail->Username   = 'akfemi2015@gmail.com';              // SMTP username (your email)
                    $mail->Password   = 'dmma xzmp gcso efqk';                   // SMTP password (your email password or app password)
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption
                    $mail->Port       = 587;                                    // TCP port to connect to

                    // Recipients
                    $mail->setFrom('no-reply@truszedproperties.com', 'Truszed Properties');
                    $mail->addAddress($email, $name);                            // Add a recipient

                    // Content
                    $activationLink = "http://localhost/truszed/login/activate.php?token=$activationToken";
                    $mail->isHTML(true);                                         // Set email format to HTML
                    $mail->Subject = 'Activate Your Account';

                    // HTML content with styling
                    $mail->Body    = "
                        <html>
                        <head>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    color: #333;
                                }
                                h1 {
                                    color: #333;
                                }
                                .highlight {
                                    color: goldenrod;
                                    font-weight: bold;
                                }
                                .content {
                                    padding: 20px;
                                    background-color: #f9f9f9;
                                    border-radius: 8px;
                                    border: 1px solid #e0e0e0;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                    width: 90%;
                                    max-width: 600px;
                                    margin: 20px auto;
                                }
                                .activation-button {
                                    display: inline-block;
                                    background-color: goldenrod;
                                    color: white;
                                    font-size: 18px;
                                    text-decoration: none;
                                    padding: 15px 30px;
                                    border-radius: 5px;
                                    text-align: center;
                                    width: 100%;
                                    max-width: 250px;
                                    margin: 15px 0;
                                    font-weight: bold;
                                }
                                .activation-button:hover {
                                    background-color: darkgoldenrod;
                                }
                                .content p {
                                    margin: 15px 0;
                                }
                            </style>
                        </head>
                        <body>
                            <div class='content'>
                                <h1>Hello $name,</h1>
                                <p>Thank you for signing up at <span class='highlight'>Truszed Properties</span>!</p>
                                <p>Please click the button below to activate your account:</p>
                                <a href='$activationLink' class='activation-button'>Activate Your Account</a>
                                <p>If you didn't sign up, please ignore this email.</p>
                            </div>
                        </body>
                        </html>
                    ";

                    $mail->send();
                    // Redirect to a page informing the user to check their email
                    header("Location: check_email.php");
                    exit();
                } catch (Exception $e) {
                    $error = "Failed to send activation email. Error: {$mail->ErrorInfo}";
                }
            } else {
                $error = "There was an error while registering the user. Please try again!";
            }
        }

        // Close the statement
        $stmt->close();
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
    <style>
        .link .login {
    border: none;
    outline: none;
    cursor: pointer;
    background: black;
    padding: 15px 0;
    border-radius: 6px;
    color: goldenrod;
    font-size: 1.25rem;
    font-weight: 600;
    transition: 0.2s ease;
}
.button a {
    padding: 15px 20px;
    background: black;
    border-radius: 6px;
    color: goldenrod;
    font-size: 1.063rem;
    font-weight: 600;
    transition: 0.2s ease;
}
.facebook-page h1 {
    color: goldenrod;
    font-size: 4rem;
    margin-bottom: 10px;
}

    </style>
</head>
<body>
    <div class="container flex">
        <div class="facebook-page flex">
            <div class="text">
                <h1>Truszed</h1>
                <p>Find your dream home with us.</p>
            </div>
            
            <form action="register.php" method="POST">
                
                <!-- Display error message if exists -->
                <?php if ($error): ?>
                    <div style="color: red; font-weight: bold; margin-bottom: 15px;"><?= $error ?></div>
                <?php endif; ?>
                
                <!-- Name input -->
                <input type="text" name="name" placeholder="Enter your name" required>

                <!-- Email input -->
                <input type="email" name="email" placeholder="Enter your Email" required>

                <!-- Phone number input -->
                <input type="tel" name="phone_number" placeholder="Phone number" required pattern="\d{11}">
              
                <!-- Password input -->
                <input type="password" name="password" placeholder="Password" required>

                <!-- Submit button -->
                <div class="link">
                    <button type="submit" class="login">Signup</button>
                    <a href="#" class="forgot">Forgot password?</a>
                </div>

                <hr>

                <!-- Link to login page -->
                <div class="button">
                    <a href="login.php">Have an account? Login</a>
                </div>

            </form>
        </div>
    </div>
</body>
</html>