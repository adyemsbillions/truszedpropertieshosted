<?php
session_start(); // Start the session to check if the admin is logged in

include('../db_connection.php');  // Include the database configuration

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // If the admin is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to get all users' details from the users table
$sql = "SELECT id, name, email, phone_number, alt_phone_number, gender, home_address, residential_address, created_at FROM users";
$result = $conn->query($sql);

// Check if there are any users in the table
if ($result->num_rows > 0) {
    // Fetch all user details into an array
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $users = [];
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // SQL query to delete the user
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    // Redirect back to the users list page after deleting
    header("Location: users_list.php");
    exit();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional CSS -->
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #fff; /* White background */
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            color: #4e148c; /* Purple text */
            padding: 30px;
            margin: 30px auto;
            max-width: 1100px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            background-color: #fff; /* White background */
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #4e148c; /* Purple color */
        }

        /* Table Styling */
        .users-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: left;
        }

        .users-table th, .users-table td {
            padding: 12px;
            border: 1px solid #ddd;
        }

        .users-table th {
            background-color: #4e148c; /* Dark Purple */
            color: white; /* White color for text */
            font-weight: bold;
        }

        .users-table tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        .users-table td {
            color: #333;
        }

        /* Action Buttons */
        .action-btn {
            display: inline-block;
            background-color: #4e148c;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            font-size: 1em;
            margin-top: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #7b1fa2; /* Lighter purple on hover */
        }

        /* Back Button Styling */
        .back-btn {
            display: inline-block;
            background-color: #4e148c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 1.2em;
            margin-top: 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #7b1fa2;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .users-table th, .users-table td {
                padding: 8px;
            }

            .dashboard-container {
                padding: 20px;
                margin: 20px;
            }
        }

    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 class="text-center">All Users Details</h1>

        <?php if (!empty($users)): ?>
        <table class="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Alt. Phone</th>
                    <th>Gender</th>
                    <th>Home Address</th>
                    <th>Residential Address</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['phone_number']; ?></td>
                    <td><?php echo $user['alt_phone_number']; ?></td>
                    <td><?php echo $user['gender']; ?></td>
                    <td><?php echo $user['home_address']; ?></td>
                    <td><?php echo $user['residential_address']; ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                    <td>
                        <!-- Edit Button -->
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="action-btn">Edit</a>

                        <!-- Delete Button -->
                        <a href="?delete_id=<?php echo $user['id']; ?>" class="action-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No users found in the database.</p>
        <?php endif; ?>

        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
