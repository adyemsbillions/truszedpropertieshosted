<?php
// Start session
session_start();

// Include database connection file
require_once '../db_connection.php';

// Check if the agent is logged in
if (!isset($_SESSION['agent_logged_in'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch the logged-in agent's ID from the session
$agent_id = $_SESSION['agent_id']; // Assuming agent_id is stored in session

// Fetch agent data from the database
$sql = "SELECT * FROM agent WHERE id = ?";
if ($stmt = $db_connection->prepare($sql)) {
    $stmt->bind_param("i", $agent_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the agent's data
        $row = $result->fetch_assoc();
    } else {
        echo "Agent not found.";
        exit();
    }
} else {
    echo "Error with database query.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Profile</title>
    <style>
        /* Add your styling here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        .profile-info {
            margin: 20px 0;
        }

        .profile-info p {
            margin: 10px 0;
        }

        .profile-info strong {
            font-weight: 600;
        }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>My Profile</h1>
        <h2>Agent Details</h2>

        <div class="profile-info">
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($row['full_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
            <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($row['phone_number']); ?></p>
            <p><strong>Birth Date:</strong> <?php echo htmlspecialchars($row['birth_date']); ?></p>
            <p><strong>Address Line 1:</strong> <?php echo htmlspecialchars($row['address_line1']); ?></p>
            <p><strong>Address Line 2:</strong> <?php echo htmlspecialchars($row['address_line2']); ?></p>
            <p><strong>ID Type:</strong> <?php echo htmlspecialchars($row['id_type']); ?></p>
            <p><strong>ID Number:</strong> <?php echo htmlspecialchars($row['id_number']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
            <p><strong>ID Front Image:</strong> <a href="http://localhost/truszed/agent/login/uploads/<?php echo htmlspecialchars($row['id_front_image']); ?>" target="_blank">View</a></p>
            <p><strong>ID Back Image:</strong> <a href="http://localhost/truszed/agent/login/uploads/<?php echo htmlspecialchars($row['id_back_image']); ?>" target="_blank">View</a></p>

            

        <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
    </div>

</body>
</html>
