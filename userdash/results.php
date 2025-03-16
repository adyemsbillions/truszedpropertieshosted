<?php
// Step 1: Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "truszed";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Get the search query from the URL (via GET method)
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';
$searchQuery = htmlspecialchars($searchQuery); // Prevent XSS

// Step 3: If a search term is provided, search for properties
$result = null;
if (!empty($searchQuery)) {
    $sql = "SELECT * FROM agent_properties WHERE state LIKE ? OR property_name LIKE ? OR price LIKE ?";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%$searchQuery%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // If no search term, show a message
    $result = null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">  <!-- Assuming you have a separate CSS file -->
</head>
<body>
    <!-- Property Listings Section -->
    <section class="properties-listings">
        <center>
            <h1>Search Results</h1>
        </center>

        <?php
        if (!empty($searchQuery)) {
            if ($result && $result->num_rows > 0) {
                echo '<div class="property-card-container">';  // Wrapper for all the cards

                // Loop through each result and display as a property card
                while($row = $result->fetch_assoc()) {
                    $property_id = $row['id'];
                    $property_name = $row['property_name'];
                    $price = $row['price'];
                    $post_image = "uploads/" . $row['post_image'];  // Relative path to the image
                    $property_type = $row['property_type'];
                    $bedrooms = $row['bedrooms'];
                    $address = $row['address'];
                    ?>
                    <div class="property-card">
                        <div class="property-image">
                            <img src="http://localhost/truszed/agent/agent_dashboard/<?php echo $post_image; ?>" alt="Property Image">
                        </div>

                        <div class="property-details">
                            <h3><?php echo htmlspecialchars($property_name); ?></h3>
                            <p class="price">&#8358;<?php echo number_format($price, 2); ?></p>
                            <p class="property-type"><?php echo ucfirst($property_type); ?></p>
                            <p class="bedrooms"><?php echo $bedrooms; ?> Bedrooms</p>
                            <a href="property_details.php?id=<?php echo $property_id; ?>" class="view-details">View Details</a>
                        </div>
                    </div>
                    <?php
                }
                echo '</div>'; // Close the property card container
            } else {
                echo "<p>No properties found matching your search.</p>";  // Message when no properties are found
            }
        } else {
            echo "<p>Please enter a search term.</p>";
        }

        $conn->close();  // Close the database connection
        ?>
    </section>

    <button class="styled-button" onclick="window.location.href='search_by_select.php'">Search By State</button>

</body>
</html>

<style>
    .styled-button {
        background-color: goldenrod;
        color: black;
        font-size: 16px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .styled-button:hover {
        background-color: goldenrod;
    }

    .styled-button:focus {
        outline: none;
    }

    .styled-button:active {
        background-color: #3e8e41;
    }

    .property-card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        padding: 20px;
    }

    .property-card {
        width: 24.5%;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: white;
        transition: transform 0.10s ease;
    }

    .property-card:hover {
        transform: translateY(-10px);
    }

    .property-card .property-image img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid #ddd;
    }

    .property-card .property-details {
        padding: 15px;
        text-align: left;
    }

    .property-card .property-details h3 {
        font-size: 18px;
        color: #333;
        margin: 2px 0;
    }

    .property-card .property-details .price {
        font-size: 20px;
        font-weight: bold;
        color: black;
        font-weight: bolder;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }

    .property-card .property-details .property-type {
        font-size: 14px;
        color: #888;
        margin-top: 0px;
    }

    .property-card .property-details .bedrooms {
        font-size: 14px;
        color: #555;
        margin-top: 0px;
    }

    .property-card .property-details .view-details {
        display: inline-block;
        padding: 4px 16px;
        margin-top: 0px;
        background-color: black;
        color: goldenrod;
        text-decoration: none;
        border-radius: 3px;
        text-align: center;
    }

    .property-card .property-details .view-details:hover {
        background-color:black;
    }

    /* Responsive Layout for smaller screens */
    @media (max-width: 1024px) {
        .property-card {
            width: 48%;
        }
    }

    @media (max-width: 768px) {
        .property-card {
            width: 48%;
        }
    }

    @media (max-width: 480px) {
        .property-card {
            width: 100%;
        }
    }
</style>
