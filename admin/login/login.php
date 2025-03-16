<?php
session_start(); // Start session to store logged-in admin info

include('db_connection.php');  // Include your database configuration

// Check if the form is submitted (POST request)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if the required fields are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Get the data from the form
        $admin_username = $_POST['username'];
        $admin_password = $_POST['password'];

        // Create a connection to the database
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check for connection errors
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the admin exists in the database
        $sql = "SELECT * FROM admin WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            
            // Verify the password
            if (password_verify($admin_password, $admin['password'])) {
                // Successful login, start a session
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("location: ../admin_dashboard.php"); // Redirect to the dashboard
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "Admin not found!";
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    } else {
        echo "Please fill in both username and password.";
    }
}
?>

<!-- Login Form -->
<form action="login.php" method="POST" class="login-form">
    <h2>Admin Login</h2>

    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit" class="submit-btn">Login</button>
</form>

<!-- CSS for Styling -->
<style>
    /* Basic reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #6a1b9a; /* Purple background */
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: white;
    }

    .login-form {
        background-color: #4e148c; /* Darker purple background for the form */
        padding: 30px;
        border-radius: 10px;
        width: 300px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .login-form h2 {
        text-align: center;
        color: gold; /* Gold text for the title */
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 10px;
        font-weight: bold;
        color: gold; /* Gold label text */
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    button.submit-btn {
        width: 100%;
        padding: 12px;
        background-color: gold; /* Gold button */
        border: none;
        border-radius: 5px;
        font-size: 16px;
        color: #4e148c; /* Dark purple text */
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button.submit-btn:hover {
        background-color: #f39c12; /* Lighter gold on hover */
    }

    button.submit-btn:active {
        background-color: #e67e22; /* Darker gold on click */
    }

    .error-message {
        color: red;
        margin-top: 10px;
        font-size: 14px;
    }
</style>
