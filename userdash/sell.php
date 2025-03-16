<?php
// Database connection details
include('../db_connection.php');
// Fetch all sell properties
$sql = "SELECT * FROM agent_properties WHERE property_type = 'sell' AND status = 'approved'";
$result = $conn->query($sql);

// Check if any properties exist
if ($result->num_rows > 0) {
    $properties = $result->fetch_all(MYSQLI_ASSOC);
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
    <title>Sell Properties</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: black; /* Purple color */
            color: goldenrod;
            padding: 20px 0;
            text-align: center;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
        }

        .property-listing {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .property-item {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 30%;
            padding: 20px;
            text-align: center;
        }

        .property-item img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .property-item h3 {
            font-size: 1.5rem;
            color: #333;
        }

        .property-item .price {
            font-size: 1.25rem;
            color:black; /* Purple color */
            margin-top: 10px;
        }

        .property-item .view-details {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color:black; /* Purple color */
            color: goldenrod;
            border-radius: 5px;
            text-decoration: none;
        }

        .property-item .view-details:hover {
            background-color:black;
        }

        @media (max-width: 768px) {
            .property-item {
                width: 48%;
            }
        }

        @media (max-width: 480px) {
            .property-item {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Sell Properties</h1>
</header>

<div class="container">
    <div class="property-listing">
        <?php if (count($properties) > 0): ?>
            <?php foreach ($properties as $property): ?>
                <div class="property-item">
                    <img src="/truszed/agent/agent_dashboard/uploads/<?php echo $property['post_image']; ?>" alt="Property Image">
                    <h3><?php echo htmlspecialchars($property['property_name']); ?></h3>
                    <p class="price">$<?php echo number_format($property['price']); ?></p>
                    <a href="property_detail_sell.php?id=<?php echo $property['id']; ?>" class="view-details">View Details</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No sell properties available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
