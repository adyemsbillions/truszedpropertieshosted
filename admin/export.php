<?php
// Include the database connection file
// Include database connection
$servername = "localhost";  // Change this to your server
$username = "root";         // Change this to your database username
$password = "";             // Change this to your database password
$dbname = "truszed";        // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the file name and headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users_data.csv"');

// Open the output stream
$output = fopen('php://output', 'w');

// Add CSV headers (column names based on your users table)
fputcsv($output, ['ID', 'Name', 'Email', 'Phone Number', 'Alt Phone Number', 'Gender', 'Home Address', 'Residential Address', 'Password', 'Created At', 'Verification Token', 'Is Verified', 'Activation Token', 'Is Active']);

// Fetch data from the users table
$query = "SELECT * FROM users";
$result = $conn->query($query);

// Check if we have data
if ($result->num_rows > 0) {
    // Output data to the CSV
    while ($row = $result->fetch_assoc()) {
        // Write each row of user data to the CSV
        fputcsv($output, $row);
    }
}

// Close the output stream
fclose($output);

// Close the database connection
$conn->close();
exit();
?>
