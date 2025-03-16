<?php
include('db_connection.php');  // Include your database configuration

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    $admin_username = $_POST['username'];
    $admin_password = $_POST['password'];

    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check for connection errors
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Hash the password
    $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

    // Check if the username already exists
    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Username already exists!";
    } else {
        // Insert the new admin into the database
        $sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $admin_username, $hashed_password);

        if ($stmt->execute()) {
            echo "Admin account created successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    // Close the connection
    $stmt->close();
    $conn->close();
}
?>

<!-- Signup Form -->
<form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Sign Up</button>
</form>
