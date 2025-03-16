<?php
require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $agent_id = $_GET['id'];

    // Fetch the agent's details from the database
    $sql = "SELECT * FROM agent WHERE id = ?";
    if ($stmt = $db_connection->prepare($sql)) {
        $stmt->bind_param("i", $agent_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row['status'];

            // Set the headers for the download
            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="status_report_' . $agent_id . '.txt"');

            // Create the content of the file
            $content = "Agent ID: " . $agent_id . "\n";
            $content .= "Full Name: " . $row['full_name'] . "\n";
            $content .= "Email: " . $row['email'] . "\n";
            $content .= "Status: " . ucfirst($status) . "\n";
            echo $content;
        } else {
            echo "Agent not found.";
        }
    } else {
        echo "Error preparing statement.";
    }
} else {
    echo "Invalid request.";
}
?>
