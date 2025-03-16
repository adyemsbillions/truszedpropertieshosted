<?php
// Database connection details
include('../db_connection.php');

// SQL query to fetch all properties of type 'rent'
$sql = "SELECT * FROM agent_properties WHERE property_type = 'rent'";
$result = $conn->query($sql);

// Check if there are any properties
if ($result->num_rows > 0) {
    // Start outputting the properties
    $properties = [];
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
} else {
    $properties = [];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Properties</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        header {
            background-color:goldenrod; /* Purple color */
            color: black;
            padding: 20px 0;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .property-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: 15px 0;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 20px;
        }

        .property-card img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            max-height: 200px; /* Limit the image height */
            object-fit: cover;
        }

        .property-card h3 {
            font-size: 1.5rem;
            margin: 0;
            color: #333;
        }

        .property-card p {
            color: #555;
        }

        .property-card .price {
            font-size: 1.2rem;
            color: goldenrod; /* Purple color */
            margin-top: 10px;
            font-weight: bolder;
        }

        .property-card .details {
            margin-top: 10px;
            color: #777;
        }

        .property-card a {
            display: inline-block;
            margin-top: 15px;
            background-color: black; /* Purple color */
            color: goldenrod;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        
        }

        .property-card a:hover {
            background-color:black; /* Darker purple for hover effect */
        }

        .property-card a:focus {
            outline: none;
            box-shadow: 0 0 3px #4a148c;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .property-card {
                flex-direction: column;
                align-items: center;
            }

            .property-card img {
                max-height: 250px; /* Adjust image height for smaller screens */
                width: 100%;
            }

            .property-card h3 {
                font-size: 1.3rem;
                text-align: center;
            }

            .property-card .details {
                text-align: center;
            }

            .property-card .price {
                font-size: 1.1rem;
            }

            .property-card a {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .property-card h3 {
                font-size: 1.2rem;
            }

            .property-card .price {
                font-size: 1rem;
            }

            .property-card .details {
                font-size: 0.9rem;
            }

            .property-card a {
                font-size: 1rem;
                padding: 12px 25px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Rent Properties</h1>
    <p>Explore available properties for rent</p>
</header>

<div class="container">
    <?php if (count($properties) > 0): ?>
        <?php foreach ($properties as $property): ?>
            <div class="property-card">
                <!-- Updated image path -->
                <img src="/truszed/agent/agent_dashboard/uploads/<?php echo $property['post_image']; ?>" alt="Property Image">
                <div>
                    <h3><?php echo htmlspecialchars($property['property_name']); ?></h3>
                    <p><?php echo isset($property['property_details']) ? htmlspecialchars($property['property_details']) : 'No description available.'; ?></p>
                    <p class="price">$<?php echo number_format($property['price']); ?></p>
                    <div class="details">
                        <p>Address: <?php echo isset($property['address']) ? htmlspecialchars($property['address']) : 'Address not provided'; ?></p>
                        <p>Size: <?php echo isset($property['dimensions']) ? htmlspecialchars($property['dimensions']) : 'Size not provided'; ?></p>
                    </div>
                    <a href="propertyt_details2.php?id=<?php echo $property['id']; ?>">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No properties available for rent at the moment.</p>
    <?php endif; ?>
</div>

</body>
</html>
