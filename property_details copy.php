<?php
// Include database connection
$servername = "localhost";  // Change this to your server
$username = "root";         // Change this to your database username
$password = "";             // Change this to your database password
$dbname = "truszed";    // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the property ID from the query string
$property_id = $_GET['id'];

// Fetch the property details from the database
$sql = "SELECT * FROM agent_properties WHERE id = $property_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Extract property details from the row
    $property_name = $row['property_name'];
    $price = $row['price'];
    $address = $row['address'];
    $dimensions = $row['dimensions'];
    $property_type = $row['property_type'];
    $bedrooms = $row['bedrooms'];
    $bathrooms = $row['bathrooms'];
    $toilets = $row['toilets'];
    $parking_space = $row['parking_space'];
    $post_image = "uploads/" . $row['post_image'];  // Path to the main image
    $other_images = $row['other_images'];  // CSV of other images (optional)
    $property_details = $row['property_details'];
    $market_status = $row['market_status'];
    $current_stars = $row['stars'];
    $agent_id = $row['agent_id']; // Assuming you have a stars column
} else {
    echo "Property not found.";
    exit;
}

// Handle the form submission to update the stars
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['stars'])) {
    $user_stars = $_POST['stars'];
    
    // Update the stars in the database
    $update_sql = "UPDATE agent_properties SET stars = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $user_stars, $property_id);
    $stmt->execute();
    
    // Redirect to refresh the page and show the updated stars
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $property_id);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $property_name; ?> - Property Details</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Property Details Page */
        .property-details-page {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .property-details-page h1 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .property-details-page .property-image img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .property-details-page .details p {
            font-size: 16px;
            margin: 5px 0;
        }

        /* Star stars Styles */
        .stars {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }

        .stars input {
            display: none;
        }

        .stars label {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.3s;
        }

        .stars label:hover,
        .stars input:checked ~ label {
            color: #ffb400;
        }

        .stars input:checked ~ label:hover,
        .stars input:checked ~ label:hover ~ label {
            color: #ffb400;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
         /* Property Details Page */
.property-details-page {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.property-details-page h1 {
    font-size: 28px;
    margin-bottom: 20px;
}

.property-details-page .property-image {
    width: 100%;
    max-width: 800px;
    margin-bottom: 20px;
}

.property-details-page .property-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.property-details-page .details {
    margin-bottom: 30px;
}

.property-details-page .details p {
    font-size: 16px;
    margin: 5px 0;
}

.property-details-page .other-images {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.property-details-page .other-image {
    width: 200px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

.property-details-page .back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.property-details-page .back-btn:hover {
    background-color: #0056b3;
}
/* Property Details Page */
.property-details-page {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
}

.property-details-page h1 {
    font-size: 28px;
    margin-bottom: 20px;
}

.property-details-page .property-image {
    width: 100%;
    max-width: 800px;
    margin-bottom: 20px;
}

.property-details-page .property-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.property-details-page .details {
    margin-bottom: 30px;
}

.property-details-page .details p {
    font-size: 16px;
    margin: 5px 0;
}

.property-details-page .other-images {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
}

.property-details-page .other-image {
    width: 100%;
    max-width: 200px;
    height: 150px;
    object-fit: cover;
    border-radius: 8px;
}

.property-details-page .back-btn {
    display: inline-block;
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}

.property-details-page .back-btn:hover {
    background-color: #0056b3;
}

/* Mobile Responsive Adjustments */
@media (max-width: 768px) {
    .property-details-page {
        width: 95%;
    }

    .property-details-page h1 {
        font-size: 24px;
    }

    .property-details-page .details p {
        font-size: 14px;
    }

    .property-details-page .other-image {
        width: 100%;
        max-width: 150px;
    }

    .property-details-page .property-image {
        max-width: 100%;
    }
}

/* Mobile-friendly images in the other-images section */
@media (max-width: 480px) {
    .property-details-page .other-image {
        max-width: 120px;
        height: 120px;
    }

    .property-details-page .back-btn {
        font-size: 14px;
        padding: 8px 16px;
    }
}
.property-details-page .property-image img {
    width: 100%;             /* Ensures the image fills its container */
    height: auto;            /* Maintains aspect ratio */
    max-width: 800px;        /* Ensures the image doesn't become too large on wide screens */
    margin-bottom: 20px;     /* Adds space below the image */
    border-radius: 10px;     /* Rounded corners for a more polished look */
    object-fit: cover;       /* Ensures the image covers the container without stretching */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);  /* Adds a subtle shadow around the image */
}


    </style>
</head>
<body>

    <div class="property-details-page">
        <h1><?php echo $property_name; ?></h1>
        <img src="http://localhost/truszed/agent/agent_dashboard/<?php echo $post_image; ?>" alt="Property Image" class="property-image">

        <div class="details">
            <p><strong>Price:</strong> $<?php echo number_format($price, 2); ?></p>
            <p><strong>Address:</strong> <?php echo $address; ?></p>
            <p><strong>Dimensions:</strong> <?php echo $dimensions; ?></p>
            <p><strong>Type:</strong> <?php echo ucfirst($property_type); ?></p>
            <p><strong>Bedrooms:</strong> <?php echo $bedrooms; ?></p>
            <p><strong>Bathrooms:</strong> <?php echo $bathrooms; ?></p>
            <p><strong>Agent Name:</strong> <?php echo $agent_id; ?></p>
            <p><strong>Toilets:</strong> <?php echo $toilets; ?></p>
            <p><strong>Parking Space:</strong> <?php echo $parking_space; ?> spaces</p>
            <p><strong>Market Status:</strong> <?php echo ucfirst($market_status); ?></p>
            <p><strong>Description:</strong> <?php echo nl2br($property_details); ?></p>
        </div>

        <!-- Star stars -->
        <div class="stars">
            <form method="POST" action="">
                <input type="radio" id="star5" name="stars" value="5" <?php echo $current_stars == 5 ? 'checked' : ''; ?>>
                <label for="star5">★</label>
                <input type="radio" id="star4" name="stars" value="4" <?php echo $current_stars == 4 ? 'checked' : ''; ?>>
                <label for="star4">★</label>
                <input type="radio" id="star3" name="stars" value="3" <?php echo $current_stars == 3 ? 'checked' : ''; ?>>
                <label for="star3">★</label>
                <input type="radio" id="star2" name="stars" value="2" <?php echo $current_stars == 2 ? 'checked' : ''; ?>>
                <label for="star2">★</label>
                <input type="radio" id="star1" name="stars" value="1" <?php echo $current_stars == 1 ? 'checked' : ''; ?>>
                <label for="star1">★</label>
                <br>
                <button type="submit" class="back-btn">Submit stars</button>
            </form>
        </div>

        <h3>Other Images</h3>
        <div class="other-images">
            <?php
            if (!empty($other_images)) {
                // Split the CSV of other images into an array
                $other_images_array = explode(',', $other_images);
                foreach ($other_images_array as $image) {
                    echo '<img src="http://localhost/truszed/agent/agent_dashboard/uploads/' . $image . '" alt="Other Image" class="other-image">';
                }
            }
            ?>
        </div>

        <a href="index.php" class="back-btn">Back to Properties</a>
    </div>

</body>
</html>
