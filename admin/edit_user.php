<?php
session_start(); // Start the session to check if the admin is logged in

include('../db_connection.php');  // Include the database configuration

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details from the database
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Update user details based on form submission
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $alt_phone_number = $_POST['alt_phone_number'];
        $gender = $_POST['gender'];
        $home_address = $_POST['home_address'];
        $residential_address = $_POST['residential_address'];

        // Update the user in the database
        $update_sql = "UPDATE users SET name = ?, email = ?, phone_number = ?, alt_phone_number = ?, gender = ?, home_address = ?, residential_address = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sssssssi", $name, $email, $phone_number, $alt_phone_number, $gender, $home_address, $residential_address, $user_id);
        $update_stmt->execute();

        // Redirect back to the users list page
        header("Location: all_users.php");
        exit();
    }
} else {
    // If no user ID is provided, redirect to the users list
    header("Location: users_list.php");
    exit();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            color: #4e148c;
            padding: 40px;
        }

        .form-container {
            max-width: 600px;
            margin: auto;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4e148c;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-btn {
            display: inline-block;
            background-color: #4e148c;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            font-size: 1.1em;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .form-btn:hover {
            background-color: #7b1fa2;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Edit User</h1>
        <form action="edit_user.php?id=<?php echo $user['id']; ?>" method="POST">
            <input type="text" name="name" class="form-input" value="<?php echo $user['name']; ?>" required>
            <input type="email" name="email" class="form-input" value="<?php echo $user['email']; ?>" required>
            <input type="text" name="phone_number" class="form-input" value="<?php echo $user['phone_number']; ?>" required>
            <input type="text" name="alt_phone_number" class="form-input" value="<?php echo $user['alt_phone_number']; ?>">
            <select name="gender" class="form-input" required>
                <option value="Male" <?php echo ($user['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($user['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
            </select>
            <textarea name="home_address" class="form-input" required><?php echo $user['home_address']; ?></textarea>
            <textarea name="residential_address" class="form-input" required><?php echo $user['residential_address']; ?></textarea>
            <button type="submit" class="form-btn">Update User</button>
        </form>
    </div>
</body>
</html>
