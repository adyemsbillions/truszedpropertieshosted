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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Roboto', sans-serif;
}

.flex {
  display: flex;
  align-items: center;
}

.container {
  padding: 0 15px;
  min-height: 100vh;
  justify-content: center;
  background: #f0f2f5;
}

.facebook-page {
  justify-content: space-between;
  max-width: 1000px;
  width: 100%;
}

.facebook-page .text {
  margin-bottom: 90px;
}

.facebook-page h1 {
  color: black;
  font-size: 4rem;
  margin-bottom: 10px;
}

.facebook-page p {
  font-size: 1.75rem;
  white-space: nowrap;
}

form {
  display: flex;
  flex-direction: column;
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1),
    0 8px 16px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 100%;
}

form input {
  height: 40px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 15px;
  font-size: 1rem;
  padding: 0 14px;
}
.checkbox{
  height: 10px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-bottom: 15px;
  font-size: 1rem;
  padding: 0 14px;
  text-align: left;
  margin-right: 10px; 
}
form input:focus {
  outline: none;
  border-color: black;
}

::placeholder {
  color: #777;
  font-size: 1.063rem;
}

.link {
  display: flex;
  flex-direction: column;
  text-align: center;
  gap: 15px;
}

.link .login {
  border: none;
  outline: none;
  cursor: pointer;
  background: black;
  padding: 15px 0;
  border-radius: 6px;
  color: #fff;
  font-size: 1.25rem;
  font-weight: 600;
  transition: 0.2s ease;
}

.link .login:hover {
  background: black;
}

form a {
  text-decoration: none;
}

.link .forgot {
  color: black;
  font-size: 0.875rem;
}

.link .forgot:hover {
  text-decoration: underline;
}

hr {
  border: none;
  height: 1px;
  background-color: #ccc;
  margin-bottom: 20px;
  margin-top: 20px;
}

.button {
  margin-top: 25px;
  text-align: center;
  margin-bottom: 20px;
  color: goldenrod;
}

.button a {
  padding: 15px 20px;
  background:black;
  border-radius: 6px;
  color: goldenrod;
  font-size: 1.063rem;
  font-weight: 600;
  transition: 0.2s ease;
}

.button a:hover {
  background: black;
}

@media (max-width: 900px) {
  .facebook-page {
    flex-direction: column;
    text-align: center;
  }

  .facebook-page .text {
    margin-bottom: 30px;
  }
}

@media (max-width: 460px) {
  .facebook-page h1 {
    font-size: 3.5rem;
  }

  .facebook-page p {
    font-size: 1.3rem;
  }

  form {
    padding: 15px;
  }
}
    </style>
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