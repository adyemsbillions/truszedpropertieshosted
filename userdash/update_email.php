<?php
// Database connection
$host = 'localhost'; // Your database host
$username = 'root'; // Your database username
$password = ''; // Your database password
$dbname = 'truszed'; // Your database name

// Create connection
$connection = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Hardcoded email (for example purposes)
$user_email = "adyemsgodlove@gmail.com"; // Replace with the email you want to test

// Generate a unique token
$verification_token = md5(uniqid(rand(), true));

// Insert the user into the database (with the verification token)
$sql = "INSERT INTO users (email, verification_token, is_verified) VALUES ('$user_email', '$verification_token', 0)";
if (mysqli_query($connection, $sql)) {
    // Send verification email
    $to = $user_email;
    $subject = "Verify Your Email Address";
    $verification_url = "http://localhost/truszed/userdash/update_email.php?token=$verification_token";

    $message = "
    <html>
    <head>
        <title>Verify Your Email</title>
    </head>
    <body>
        <p>Dear User,</p>
        <p>Thank you for registering on our website. Please click the link below to verify your email address:</p>
        <p><a href='$verification_url'>$verification_url</a></p>
        <p>If you did not sign up for this account, please ignore this email.</p>
    </body>
    </html>
    ";

    // Set email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: no-reply@yourwebsite.com" . "\r\n"; // Change to your sender email

    // Send the email
    if (mail($to, $subject, $message, $headers)) {
        echo "Verification email has been sent!";
    } else {
        echo "Error sending verification email.";
    }
} else {
    echo "Error inserting user: " . mysqli_error($connection);
}

// If a token is provided in the URL, verify it
if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($connection, $_GET['token']);

    // Check if the token exists in the database
    $sql = "SELECT * FROM users WHERE verification_token = '$token' LIMIT 1";
    $result = mysqli_query($connection, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Token found, mark user as verified
        $update_sql = "UPDATE users SET is_verified = 1 WHERE verification_token = '$token'";
        if (mysqli_query($connection, $update_sql)) {
            echo "Your email has been successfully verified!";
        } else {
            echo "Error verifying your email.";
        }
    } else {
        echo "Invalid verification link!";
    }
}
?>
