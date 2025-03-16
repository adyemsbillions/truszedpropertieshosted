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
header('Content-Disposition: attachment; filename="agent_properties_data.csv"');

// Open the output stream
$output = fopen('php://output', 'w');

// Add CSV headers (column names based on your agent_properties table)
fputcsv($output, [
    'ID', 'Property Name', 'Price', 'Address', 'Dimensions', 'Property Type', 
    'Bedrooms', 'Bathrooms', 'Toilets', 'Parking Space', 'Post Image', 'Other Images', 
    'Market Status', 'Created At', 'State', 'LGA', 'Property Details', 'Status', 
    'Stars', 'Agent ID'
]);

// Fetch data from the agent_properties table
$query = "SELECT * FROM agent_properties";
$result = $conn->query($query);

// Check if we have data
if ($result->num_rows > 0) {
    // Output data to the CSV
    while ($row = $result->fetch_assoc()) {
        // Write each row of property data to the CSV
        fputcsv($output, $row);
    }
}

// Close the output stream
fclose($output);

// Close the database connection
$conn->close();
exit();
?>
