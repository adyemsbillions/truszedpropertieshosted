<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "truszed"; // Database name as per your provided schema

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Display pending requests
$sql = "SELECT * FROM agent WHERE status = 'approved'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Pending Agent Requests</h2>";
    // Loop through each agent request
    while($agent = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;'>";
        echo "<strong>Name:</strong> " . $agent['full_name'] . "<br>";
        echo "<strong>Email:</strong> " . $agent['email'] . "<br>";
        echo "<strong>Phone Number:</strong> " . $agent['phone_number'] . "<br>";
        echo "<strong>Birth Date:</strong> " . $agent['birth_date'] . "<br>";
        echo "<strong>Gender:</strong> " . $agent['gender'] . "<br>";
        echo "<strong>Address:</strong> " . $agent['address_line1'] . ", " . $agent['address_line2'] . "<br>";
        echo "<strong>ID Type:</strong> " . $agent['id_type'] . "<br>";
        echo "<strong>ID Number:</strong> " . $agent['id_number'] . "<br>";
        echo "<strong>ID Front Image:</strong> " . $agent['id_front_image'] . "<br>";
        echo "<strong>ID Back Image:</strong> " . $agent['id_back_image'] . "<br>";
        echo "<strong>Status:</strong> " . $agent['status'] . "<br>";

        // Form to approve or reject the agent
        echo '<form action="" method="post">
                <input type="hidden" name="agent_id" value="' . $agent['id'] . '">
             
              </form>';
        echo "</div>";
    }
} else {
    echo "No pending requests.";
}

// Handle approval or rejection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['agent_id']) && isset($_POST['action'])) {
        $agent_id = $_POST['agent_id'];
        $action = $_POST['action'];

        // Determine new status based on the action
        if ($action == 'approve') {
            $new_status = 'approved';
        } elseif ($action == 'reject') {
            $new_status = 'rejected';
        } else {
            die("Invalid action.");
        }

        // Update agent status in the database
        $sql = "UPDATE agent SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $agent_id);

        if ($stmt->execute()) {
            echo "Agent status updated to: " . $new_status;
            // Optionally, you can redirect or reload the page to reflect the updated status
            echo '<br><a href="approve_agent.php">Go back to pending requests</a>';
        } else {
            echo "Error updating agent status: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>
<style>
    /* General page style */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Container for the content */
.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
}

/* Heading style */
h2 {
    text-align: center;
    color: #6a1b9a; /* Purple color */
    font-size: 28px;
    margin-bottom: 20px;
}

/* Pending request block */
div.agent-block {
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Agent details style */
div.agent-block strong {
    color: #6a1b9a; /* Purple color */
}

/* Button style */
input[type="submit"] {
    background-color: #6a1b9a; /* Purple color */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    margin-right: 10px;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #9c4d97; /* Lighter purple */
}

/* Form for approve/reject buttons */
form {
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
}

/* No pending requests message */
.no-pending {
    text-align: center;
    font-size: 18px;
    color: #888;
    padding: 20px;
}

/* Success message */
.success {
    color: green;
    font-weight: bold;
    margin-bottom: 20px;
}

</style>