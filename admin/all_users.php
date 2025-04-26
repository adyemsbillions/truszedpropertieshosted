<?php
session_start();

include('../db_connection.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, email, phone_number, alt_phone_number, gender, home_address, residential_address, created_at FROM users";
$result = $conn->query($sql);

$users = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: users_list.php");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users List</title>
    <style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(120deg, #f8f4fc, #f1e6fa);
        margin: 0;
        padding: 0;
    }

    .dashboard-container {
        background: #fff;
        margin: 30px auto;
        max-width: 1200px;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #5e2b97;
        margin-bottom: 30px;
    }

    .users-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .users-table th,
    .users-table td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .users-table th {
        background-color: #5e2b97;
        color: #fff;
        font-weight: 600;
    }

    .users-table tr:nth-child(even) {
        background-color: #f9f5fc;
    }

    .users-table td {
        color: #333;
        word-break: break-word;
    }

    .action-btn {
        background-color: #5e2b97;
        color: white;
        padding: 7px 14px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        font-size: 14px;
        transition: background-color 0.3s ease;
        margin: 2px;
    }

    .action-btn:hover {
        background-color: #7b1fa2;
    }

    .back-btn {
        display: inline-block;
        background-color: #5e2b97;
        color: #fff;
        padding: 10px 20px;
        font-size: 16px;
        text-decoration: none;
        border-radius: 5px;
        margin: 20px 0 0 0;
        text-align: center;
        transition: background-color 0.3s ease;
    }

    .back-btn:hover {
        background-color: #7b1fa2;
    }

    .no-users {
        text-align: center;
        font-size: 18px;
        color: #777;
        padding: 20px;
    }

    @media (max-width: 768px) {

        .users-table,
        .users-table thead,
        .users-table tbody,
        .users-table th,
        .users-table td,
        .users-table tr {
            display: block;
        }

        .users-table thead tr {
            display: none;
        }

        .users-table tr {
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 10px;
            background: #fff;
        }

        .users-table td {
            text-align: right;
            padding-left: 50%;
            position: relative;
        }

        .users-table td::before {
            content: attr(data-label);
            position: absolute;
            left: 15px;
            width: 45%;
            padding-left: 10px;
            font-weight: bold;
            text-align: left;
            color: #5e2b97;
        }

        h1 {
            font-size: 24px;
        }
    }
    </style>
</head>

<body>

    <div class="dashboard-container">
        <h1>All Users Details</h1>

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
                    <td data-label="ID"><?php echo htmlspecialchars($user['id']); ?></td>
                    <td data-label="Name"><?php echo htmlspecialchars($user['name']); ?></td>
                    <td data-label="Email"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td data-label="Phone Number"><?php echo htmlspecialchars($user['phone_number']); ?></td>
                    <td data-label="Alt. Phone"><?php echo htmlspecialchars($user['alt_phone_number']); ?></td>
                    <td data-label="Gender"><?php echo htmlspecialchars($user['gender']); ?></td>
                    <td data-label="Home Address"><?php echo htmlspecialchars($user['home_address']); ?></td>
                    <td data-label="Residential Address"><?php echo htmlspecialchars($user['residential_address']); ?>
                    </td>
                    <td data-label="Created At"><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td data-label="Actions">
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="action-btn">Edit</a>
                        <a href="?delete_id=<?php echo $user['id']; ?>" class="action-btn"
                            onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-users">No users found in the database.</div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
        </div>
    </div>

</body>

</html>