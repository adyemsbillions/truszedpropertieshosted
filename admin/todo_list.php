<?php
// Database connection
$servername = "localhost";
$username = "root";  // Replace with your database username
$password = "";  // Replace with your database password
$dbname = "truszed";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle task creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = $conn->real_escape_string($_POST['task']);
    $sql = "INSERT INTO tasks (task) VALUES ('$task')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Task added successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle task deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM tasks WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Task deleted successfully!');</script>";
    } else {
        echo "Error deleting task: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: black;
            color:goldenrod;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color:black;
        }
        .task-list {
            list-style-type: none;
            padding: 0;
        }
        .task-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 18px;
        }
        .task-item span {
            flex: 1;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>To-Do List</h1>

    <!-- Task Input Form -->
    <form action="todo_list.php" method="POST">
        <input type="text" name="task" placeholder="Add a new task" required>
        <button type="submit">Add Task</button>
    </form>

    <!-- Task List -->
    <ul class="task-list">
        <?php
        // Fetch tasks from the database
        $result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li class='task-item'>";
                echo "<span>" . htmlspecialchars($row['task']) . "</span>";
                echo "<a href='?delete=" . $row['id'] . "' class='delete-btn'>Delete</a>";
                echo "</li>";
            }
        } else {
            echo "<li>No tasks yet.</li>";
        }
        ?>
    </ul>
</div>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
