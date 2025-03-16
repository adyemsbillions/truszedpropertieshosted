<?php
include('db_connection.php');

// Check if ID and action are provided
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Ensure the action is valid (approve, suspend, reject)
    if (in_array($action, ['approve', 'suspend', 'reject'])) {
        
        // Fetch the current status of the agent
        $sql = "SELECT status FROM agent WHERE id = ?";
        $stmt = $db_connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // If agent exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Only update the status if it's 'pending'
            if ($row['status'] === 'pending') {
                // Update the status based on the action
                $update_sql = "UPDATE agent SET status = ? WHERE id = ?";
                $update_stmt = $db_connection->prepare($update_sql);
                $update_stmt->bind_param("si", $action, $id);
                
                if ($update_stmt->execute()) {
                    // Redirect to the dashboard after successful update
                    header("Location: agent_request.php");  // Change this to your dashboard URL
                    exit();
                } else {
                    echo "Error: " . $update_stmt->error;
                }
            } else {
                // If the agent is not in 'pending', no update is made
                echo "The agent's status is already set to " . ucfirst($row['status']) . ". No update was made.";
            }
        } else {
            echo "Agent not found.";
        }
    } else {
        echo "Invalid action.";
    }
} else {
    echo "Invalid parameters.";
}
?>
