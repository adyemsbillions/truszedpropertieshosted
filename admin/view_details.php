<?php
include('db_connection.php');

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the agent details based on the ID
    $sql = "SELECT * FROM agent WHERE id = ?";
    $stmt = $db_connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $agent = $result->fetch_assoc();
    } else {
        echo "Agent not found.";
        exit();
    }
} else {
    echo "Invalid parameters.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Agent Details</title>
    <style>
        .container {
            padding: 20px;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .detail-table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Agent Details</h1>
        <table class="detail-table">
            <tr>
                <th>ID</th>
                <td><?php echo $agent['id']; ?></td>
            </tr>
            <tr>
                <th>Full Name</th>
                <td><?php echo $agent['full_name']; ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo $agent['email']; ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo $agent['phone_number']; ?></td>
            </tr>
            <tr>
                <th>Birth Date</th>
                <td><?php echo $agent['birth_date']; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo ucfirst($agent['status']); ?></td>
            </tr>
        </table>
        <br>
        <a href="admin_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
