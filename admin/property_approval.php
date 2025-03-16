<?php
// Include the database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "truszed";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle approval or rejection logic
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];  // 'approve' or 'reject'
    $property_id = (int) $_GET['id']; // Property ID passed via URL

    // Update the status based on the action
    if ($action == 'approve') {
        $sql_update = "UPDATE agent_properties SET status = 'approved' WHERE id = $property_id";
    } elseif ($action == 'reject') {
        $sql_update = "UPDATE agent_properties SET status = 'rejected' WHERE id = $property_id";
    } else {
        $sql_update = "";  // No valid action
    }

    // Execute the update query
    if (!empty($sql_update)) {
        if ($conn->query($sql_update) === TRUE) {
            // Redirect back to the page after the update
            header("Location: property_approval.php");
            exit(); // Make sure to stop further script execution after redirect
        } else {
            echo "<p class='error'>Error updating status: " . $conn->error . "</p>";
        }
    }
}

// Check if a property ID is provided for viewing details
if (isset($_GET['view_id'])) {
    $view_id = (int) $_GET['view_id'];

    // Fetch the property details from the database
    $sql_view = "SELECT * FROM agent_properties WHERE id = $view_id";
    $result_view = $conn->query($sql_view);

    if ($result_view->num_rows > 0) {
        $property = $result_view->fetch_assoc();
        $show_details = true; // Show the property details
    } else {
        $show_details = false;
    }
} else {
    $show_details = false;
}

// Fetch pending properties for admin review
$sql = "SELECT * FROM agent_properties WHERE status = 'pending'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pending Properties</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        button {
            background-color: purple;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: purple;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .property-detail {
            margin-bottom: 20px;
        }
        h2 {
            text-align: center;
        }
        .button {
            background-color: purple;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            margin: 20px auto;
        }
    </style>
</head>
<body>

    <div class="container">
        <?php if ($show_details): ?>
            <h2>Property Details</h2>
            <div class="property-detail">
                <strong>Property Name:</strong> <?php echo htmlspecialchars($property['property_name']); ?>
            </div>
            <div class="property-detail">
                <strong>Price:</strong> $<?php echo htmlspecialchars($property['price']); ?>
            </div>
            <div class="property-detail">
                <strong>Address:</strong> <?php echo htmlspecialchars($property['address']); ?>
            </div>
            <div class="property-detail">
                <strong>Dimensions:</strong> <?php echo htmlspecialchars($property['dimensions']); ?>
            </div>
            <div class="property-detail">
                <strong>Property Type:</strong> <?php echo htmlspecialchars($property['property_type']); ?>
            </div>
            <div class="property-detail">
                <strong>Bedrooms:</strong> <?php echo htmlspecialchars($property['bedrooms']); ?>
            </div>
            <div class="property-detail">
                <strong>Bathrooms:</strong> <?php echo htmlspecialchars($property['bathrooms']); ?>
            </div>
            <div class="property-detail">
                <strong>Toilets:</strong> <?php echo htmlspecialchars($property['toilets']); ?>
            </div>
            <div class="property-detail">
                <strong>Parking Spaces:</strong> <?php echo htmlspecialchars($property['parking_space']); ?>
            </div>
            <div class="property-detail">
                <strong>State:</strong> <?php echo htmlspecialchars($property['state']); ?>
            </div>
            <div class="property-detail">
                <strong>local government</strong> <?php echo nl2br(htmlspecialchars($property['lga'])); ?>
            </div>
            <div class="property-detail">
                <strong>Property Details:</strong> <?php echo nl2br(htmlspecialchars($property['property_details'])); ?>
            </div>

            <a href="property_approval.php" class="button">Back to Pending Properties</a>

        <?php else: ?>
            <h2>Pending Properties for Approval</h2>

            <?php
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Property Name</th><th>Price</th><th>Address</th><th>Actions</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['property_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                    echo "<td>
                            <a href='property_approval.php?action=approve&id=" . $row['id'] . "'><button>Approve</button></a>
                            <a href='property_approval.php?action=reject&id=" . $row['id'] . "'><button>Reject</button></a>
                            <a href='property_approval.php?view_id=" . $row['id'] . "'><button>View Details</button></a>
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No pending properties found.</p>";
            }
            ?>
        <?php endif; ?>

    </div>

</body>
</html>

<?php
$conn->close();
?>
