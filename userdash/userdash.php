<?php
session_start();
include('../db_connection.php');
// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];  // Get the logged-in user's ID

// SQL query to count unread messages
$sql = "SELECT COUNT(*) AS unread_count FROM messages WHERE recipient_id = ? AND `read` = 0";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($unread_count);
$stmt->fetch();
$stmt->close();

$conn->close();
?>

<?php
// Database connection
include('../db_connection.php');

// Initialize profile status and other variables
$profile_complete = false;
$error = '';
$success = '';

// Check if user is logged in and retrieve their profile data
// session_start();
$user_id = $_SESSION['user_id'] ?? null; // Assuming user_id is stored in session
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Fetch user profile data from the database
$stmt = $conn->prepare("SELECT name, email, phone_number, alt_phone_number, gender, home_address, residential_address FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($name, $email, $phone_number, $alt_phone_number, $gender, $home_address, $residential_address);
$stmt->fetch();
$stmt->close();

// Check if the profile is complete (you can adjust the criteria as needed)
if ($alt_phone_number && $gender && $home_address && $residential_address) {
    $profile_complete = true;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update profile details
    $alt_phone_number = mysqli_real_escape_string($conn, $_POST['alt_phone_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $home_address = mysqli_real_escape_string($conn, $_POST['home_address']);
    $residential_address = mysqli_real_escape_string($conn, $_POST['residential_address']);

    // Update the profile in the database
    $stmt = $conn->prepare("UPDATE users SET alt_phone_number = ?, gender = ?, home_address = ?, residential_address = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $alt_phone_number, $gender, $home_address, $residential_address, $user_id);

    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
        // Check profile completion after update
        if ($alt_phone_number && $gender && $home_address && $residential_address) {
            $profile_complete = true;
        } else {
            $profile_complete = false;
        }
    } else {
        $error = "There was an error updating your profile.";
    }

    $stmt->close();
}

$conn->close();
?>
<?php
// Database connection details
include('../db_connection.php');
// SQL query to get the count of agents
$sqlAgentCount = "SELECT COUNT(*) AS agent_count FROM agent";
$resultAgentCount = $conn->query($sqlAgentCount);

// Get agent count
if ($resultAgentCount->num_rows > 0) {
    $rowAgent = $resultAgentCount->fetch_assoc();
    $agentCount = $rowAgent['agent_count'];
} else {
    $agentCount = 0;
}

// SQL query to get the total number of properties
$sqlPropertyCount = "SELECT COUNT(*) AS total_properties FROM agent_properties";
$resultPropertyCount = $conn->query($sqlPropertyCount);

// Get total properties count
if ($resultPropertyCount->num_rows > 0) {
    $rowProperties = $resultPropertyCount->fetch_assoc();
    $totalProperties = $rowProperties['total_properties'];
} else {
    $totalProperties = 0;
}

// SQL query to get the count of properties by type (buy, rent, sell)
$sqlPropertyTypes = "SELECT property_type, COUNT(*) AS count FROM agent_properties GROUP BY property_type";
$resultPropertyTypes = $conn->query($sqlPropertyTypes);

// Initialize counts for each property type
$buyCount = 0;
$rentCount = 0;
$sellCount = 0;

// Fetch the counts for property types
if ($resultPropertyTypes->num_rows > 0) {
    while ($row = $resultPropertyTypes->fetch_assoc()) {
        if ($row['property_type'] == 'buy') {
            $buyCount = $row['count'];
        } elseif ($row['property_type'] == 'rent') {
            $rentCount = $row['count'];
        } elseif ($row['property_type'] == 'sell') {
            $sellCount = $row['count'];
        }
    }
} else {
    // If no property types are found, keep counts as 0
    $buyCount = 0;
    $rentCount = 0;
    $sellCount = 0;
}

// Debugging output - print the variables
// echo "Agent Count: " . $agentCount . "<br>";
// echo "Total Properties: " . $totalProperties . "<br>";
// echo "Buy Properties Count: " . $buyCount . "<br>";
// echo "Rent Properties Count: " . $rentCount . "<br>";
// echo "Sell Properties Count: " . $sellCount . "<br>";

// Close the database connection
$conn->close();
?>

<!-- HTML Part where the counts will be echoed -->


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Untree.co" />
    <link rel="shortcut icon" href="images/truszedlogo.png" />

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap5" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="fonts/icomoon/style.css" />
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css" />

    <link rel="stylesheet" href="css/tiny-slider.css" />
    <link rel="stylesheet" href="css/aos.css" />
    <link rel="stylesheet" href="css/style.css" />

    <title>
     User Dashboard
    </title>
  </head>
  <body>
    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close">
          <span class="icofont-close js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div>
<style>
  .menu-bg-wrap{
    background-color: black;
 
  
  }
  
  .site-nav .site-navigation .site-menu > li > a {
    font-size: 14px;
    padding: 10px 15px;
    display: inline-block;
    text-decoration: none !important;
    color: goldenrod;
    font-family: 'Times New Roman', Times, serif;
}

.logo {
    font-size: 24px;
    color: goldenrod !important;
    font-weight: 500;
    font-family: 'Times New Roman', Times, serif;
}
</style>
    <nav class="site-nav">
      <div class="container">
        <div class="menu-bg-wrap">
          <div class="site-navigation">
          <a href="userdash.php" class="logo m-0 float-start">
  <img src="newlogo.png" alt="Truszed Properties" style="height: 40px; width: 65px;">
</a>



            <ul style="color: goldenrod;"
              class="js-clone-nav d-none d-lg-inline-block text-start site-menu float-end"
            >
              <li class="active"><a href="index.html" style="color: goldenrod;">Home</a></li>
             
              <li class="has-children" style="color: goldenrod" ;>
                <a href="" >About Us</a>
                <ul class="dropdown">
                  <li><a href="about.php">About us</a></li>
                  <li><a href="our_vision.php">Our vision</a></li>
                  <li><a href="our_mission.php">Our Mission</a></li>
                 
                 
                </ul>
              </li>
              <li class="has-children" style="color: goldenrod" ;>
                <a href="" >properties</a>
                <ul class="dropdown">
                  <li><a href="sell.php">Buy a property</a></li>
                  <li><a href="rent.php">Rent a property</a></li>
<!--                  
                  <li class="has-children" style="color: goldenrod;">
                    <a href="#">Dropdown</a>
                    <ul class="dropdown">
                      <li><a href="#">Sub Menu One</a></li>
                      <li><a href="#">Sub Menu Two</a></li>
                      <li><a href="#">Sub Menu Three</a></li>
                    </ul>
                  </li> -->
                </ul>
              </li>
              <li style="color: goldenrod;"><a href="services.html">Services</a></li>
             
              <li style="color: goldenrod;"><a href="contact.php">Contact Us</a></li>
              <!-- <li style="color: goldenrod;"><a href="my_profile.php">My Profile</a></li> -->
            </ul>

            <a
              href="#"
              class="burger light me-auto float-end mt-1 site-menu-toggle js-menu-toggle d-inline-block d-lg-none"
              data-toggle="collapse"
              data-target="#main-navbar"
            >
              <span></span>
            </a>
          </div>
        </div>
      </div>
    </nav>

    <div class="hero">
      <div class="hero-slide">
        <div
          class="img overlay"
          style="background-image: url('images/hero_bg_3.jpg')"
        ></div>
        <div
          class="img overlay"
          style="background-image: url('images/hero_bg_2.jpg')"
        ></div>
        <div
          class="img overlay"
          style="background-image: url('images/hero_bg_1.jpg')"
        ></div>
      </div>

      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-lg-9 text-center">
            <h1 class="heading" data-aos="fade-up">
            Welcome <?php echo "$name" ?>
            </h1>
             <!-- Step 1: Display the search form -->
    <form action="results.php" method="GET" class="narrow-w form-search d-flex align-items-stretch mb-3">
        <input
            type="text"
            class="form-control px-4"
            placeholder="Search by: state, property name, or price"
            name="search">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
          </div>
        </div>
      </div>
    </div>

 
            </div>
          </div>
        </div>
      </div>
    </div>
<!-- Property Listings Section -->
<section class="properties-listings">
    <center>
        <h1>Most Recently Uploaded</h1>
    </center>
    <?php
    include('../db_connection.php'); // Include database connection

    // Fetch the last 8 available and approved properties
    $sql = "SELECT * FROM agent_properties WHERE  status = 'approved' ORDER BY id DESC LIMIT 4";  // Fetch the last 8 approved and available properties
    $result = $conn->query($sql);

    // Debugging: Check if the query executed successfully
    if ($result === false) {
        echo "Error in SQL query: " . $conn->error;  // Output SQL error
    } else {
        if ($result->num_rows > 0) {
            echo '<div class="property-card-container">';  // Wrapper for all the cards
            while($row = $result->fetch_assoc()) {
                // Fetch the necessary fields for each property
                $property_id = $row['id'];
                $property_name = $row['property_name'];
                $price = $row['price'];
                $lga = $row['lga'];
                $state = $row['state'];
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
                    <p class="property-type"><?php echo "Type: &nbsp;". ucfirst($property_type); ?></p>
                    <p class="property-type"><?php echo ucfirst($state); ?></p>
                    <p class="property-type"><?php echo ucfirst($lga); ?></p>
                    <p class="bedrooms"><?php echo $bedrooms; ?> Bedrooms</p>
                    <a href="property_details.php?id=<?php echo $property_id; ?>" class="view-details">View Details</a>
                </div>
            </div>
    <?php
            }
            echo '</div>'; // Close the property card container
        } else {
            echo "<p>No properties found matching the criteria.</p>";  // Message when no properties are found
        }
    }

    $conn->close();  // Close the database connection
    ?>
</section>

    &nbsp;  &nbsp;  &nbsp;  <button class="styled-button">See All Properties</button>
    <br>
    <br>
    <style>
        .styled-button {
            background-color: black; /* Green background */
            color: white; /* White text */
            font-size: 16px; /* Adjust font size */
            padding: 10px 20px; /* Padding around the text */
            border: none; /* Remove the default border */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor on hover */
            transition: background-color 0.3s ease; /* Smooth transition for hover effect */
        }

        .styled-button:hover {
            background-color: goldenrod; /* Darker green on hover */
        }

        .styled-button:focus {
            outline: none; /* Remove the outline when focused */
        }

        .styled-button:active {
            background-color: #3e8e41; /* Darker green when button is clicked */
        }
        .hero .form-search .btn{
          background-color: black;
          color: goldenrod;
        }
    </style>
</section>

<style>
   
 /* General reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

/* Container for the property cards (Flexbox) */
.property-card-container {
    display: flex;
    flex-wrap: wrap; /* Allow cards to wrap onto the next line */
    justify-content: space-between; /* Distribute cards evenly with space between them */
    padding: 15px;
}

/* Individual Property Card */
.property-card {
    width: 24.5%; /* Four cards per row */
    margin-bottom: 20px; /* Space between rows */
    border: 1px solid #ddd;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background-color: white;
    transition: transform 0.10s ease;
}

.property-card:hover {
    transform: translateY(-10px); /* Hover effect */
}

/* Property image styling */
.property-card .property-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 1px solid #ddd;
}

/* Property details section */
.property-card .property-details {
    padding: 15px;
    text-align: left;
}

.property-card .property-details h3 {
    font-size: 18px;
    color: #333;
    margin: 2px 0;
}
.property-card .property-details p {
    font-size: 18px;
    color: #333;
    margin: 2px 0;
}


.property-card .property-details .price {
    font-size: 20px;
    font-weight: bold;
    color: goldenrod; /* Green for price */
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
    background-color: black; /* Blue button */
    color: goldenrod;
    text-decoration: none;
    border-radius: 3px;
    text-align: center;
}

.property-card .property-details .view-details:hover {
    background-color: black; /* Darker blue on hover */
}

/* Responsive Layout for smaller screens (Mobile and Tablets) */
@media (max-width: 1024px) {
    .property-card {
        width: 48%; /* 2 cards per row on tablet */
    }
}

@media (max-width: 768px) {
    .property-card {
        width: 48%; /* 2 cards per row on tablet */
    }
}

@media (max-width: 480px) {
    .property-card {
        width: 100%; /* 1 card per row on mobile */
    }
} 
/* General styles for the features section */
.features-1 {
  padding: 50px 0;
  background-color: #f7f7f7;
}

.features-1 .container {
  max-width: 1140px;
  margin: 0 auto;
}

.features-1 .row {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
}

/* Box feature (card) styling */
.features-1 .box-feature {
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  transition: all 0.3s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%; /* Ensure all cards have the same height */
  overflow: hidden; /* Prevent content from overflowing */
}

/* Hover effect */
.features-1 .box-feature:hover {
  transform: translateY(-10px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

/* Icon styling */
.features-1 .box-feature span {
  display: block;
  font-size: 40px;
  color: #DAA520; /* Goldenrod color for icons */
  margin-bottom: 20px;
}

/* Heading styling */
.features-1 .box-feature h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #333;
  margin-bottom: 15px;
  flex-grow: 0; /* Prevent the heading from stretching */
}

/* Paragraph styling */
.features-1 .box-feature p {
  font-size: 1rem;
  color: #666;
  margin-bottom: 20px;
  flex-grow: 1; /* Allow paragraph to grow and take remaining space */
}

/* Learn More link styling */
.features-1 .learn-more {
  font-size: 1rem;
  font-weight: 500;
  color: #DAA520; /* Goldenrod color for the link */
  text-decoration: none;
  border-bottom: 2px solid transparent;
  transition: all 0.3s ease;
}

.features-1 .learn-more:hover {
  color: #b8860b; /* Darker goldenrod color for hover */
  border-color: #b8860b; /* Border on hover */
}

/* Mobile responsiveness */
@media (max-width: 767px) {
  .features-1 .col-6 {
    width: 100%;
    margin-bottom: 30px;
  }

  .features-1 .col-6 .box-feature {
    padding: 15px;
  }
}

/* Responsive layout for medium to large screens */
@media (min-width: 768px) {
  .features-1 .col-lg-3 {
    width: 48%; /* 2 columns */
    margin-bottom: 30px;
  }
}

@media (min-width: 1024px) {
  .features-1 .col-lg-3 {
    width: 23%; /* 4 columns for larger screens */
  }
}

</style>
    <section class="features-1">
      <div class="container">
        <div class="row">
          <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
          <div class="box-feature">
  <span class="flaticon-house"></span>
  <h3 class="mb-3">Our Properties</h3>
  <p>
    Discover a variety of properties suited to your needs. From cozy homes to spacious estates, we have options to fit every lifestyle.
  </p>
  <p><a href="#" class="learn-more">Learn More</a></p>
</div>

          </div>
          <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="500">
          <div class="box-feature">
  <span class="flaticon-building"></span>
  <h3 class="mb-3">Property for Sale</h3>
  <p>
    Explore a wide range of properties available for sale. Whether you're looking for a new home or an investment opportunity, we have options to suit every need.
  </p>
  <p><a href="#" class="learn-more">Learn More</a></p>
</div>

          </div>
          <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
          <div class="box-feature">
  <span class="flaticon-house-3"></span>
  <h3 class="mb-3">Real Estate Agent</h3>
  <p>
    Our experienced real estate agents are here to help you navigate the market. Whether buying or renting, we provide expert guidance every step of the way.
  </p>
  <p><a href="#" class="learn-more">Learn More</a></p>
</div>

          </div>
          <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="600">
          <div class="box-feature">
  <span class="flaticon-house-1"></span>
  <h3 class="mb-3">Properties for Rent</h3>
  <p>
    Find the perfect property to rent. We offer a variety of options, whether you're looking for a short-term lease or a long-term home.
  </p>
  <p><a href="#" class="learn-more">Learn More</a></p>
</div>

          </div>
        </div>
      </div>
    </section>


    <div class="section section-4 bg-light">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-lg-5">
            <h2 class="font-weight-bold heading text-primary mb-4">
              Let's find home that's perfect for you
            </h2>
            <p class="text-black-50">
From cozy homes to spacious estates, we have options to fit every lifestyle.
            </p>
          </div>
        </div>
        <div class="row justify-content-between mb-5">
          <div class="col-lg-7 mb-5 mb-lg-0 order-lg-2">
            <div class="img-about dots">
              <img src="images/hero_bg_3.jpg" alt="Image" class="img-fluid" />
            </div>
          </div>
          <div class="col-lg-4">
            <div class="d-flex feature-h">
              <span class="wrap-icon me-3">
                <span class="icon-home2"></span>
              </span>
              <div class="feature-text">
                <h3 class="heading"><?php echo $totalProperties; ?> Properties</h3>
                <p class="text-black-50">
                Explore our collection of 32 unique properties, each offering something special. Whether you're looking for a modern apartment, a cozy home, or an investment opportunity, you'll find it here.
                </p>
              </div>
            </div>

            <div class="d-flex feature-h">
              <span class="wrap-icon me-3">
                <span class="icon-person"></span>
              </span>
              <div class="feature-text">
  <h3 class="heading"> <?php echo $agentCount; ?> Top Rated Agents</h3>
  <p class="text-black-50">
    Our top-rated agents are dedicated to providing exceptional service. With years of experience and in-depth market knowledge, they’re here to help you find the perfect property.
  </p>
</div>

            </div>

            <div class="d-flex feature-h">
              <span class="wrap-icon me-3">
                <span class="icon-security"></span>
              </span>
              <div class="feature-text">
  <h3 class="heading">Legit Properties</h3>
  <p class="text-black-50">
    All our properties are verified and legit, offering you peace of mind. With trusted listings and thorough background checks, we ensure every property meets high standards.
  </p>
</div>

            </div>
          </div>
        </div>
        <div class="row section-counter mt-5">
          <div
            class="col-6 col-sm-6 col-md-6 col-lg-3"
            data-aos="fade-up"
            data-aos-delay="300"
          >
            <div class="counter-wrap mb-5 mb-lg-0">
              <span class="number"
                >
    <style>
      .tth{
        color: black;
        font-size: 20px;      }
    </style>
<p><span class="countup text-primary"><?php echo $rentCount; ?></span></p>
<p class="tth">rentals</p>
            </div>
          </div>
          <div
            class="col-6 col-sm-6 col-md-6 col-lg-3"
            data-aos="fade-up"
            data-aos-delay="400"
          >
            <div class="counter-wrap mb-5 mb-lg-0">
              <span class="number"
                >
                <p><span class="countup text-primary"><?php echo $sellCount; ?></span></p>
             <p class="tth">Sales</p>
            </div>
          </div>
          <div
            class="col-6 col-sm-6 col-md-6 col-lg-3"
            data-aos="fade-up"
            data-aos-delay="500"
          >
            <div class="counter-wrap mb-5 mb-lg-0">
              <span class="number"
                >
                <p><span class="countup text-primary"><?php echo $totalProperties; ?></span></p>
                <p class="tth">Total properties</p>
            </div>
          </div>
          <div
            class="col-6 col-sm-6 col-md-6 col-lg-3"
            data-aos="fade-up"
            data-aos-delay="600"
          >
            <div class="counter-wrap mb-5 mb-lg-0">
              <span class="number"
                >
                
                <p><span class="countup text-primary"><?php echo $agentCount; ?></span></p>
                <p class="tth">Total agents</p>
            </div>
          </div>
        </div>
      </div>
    </div>
<style>
  .btn.btn-primary {
    background: black;
  
}
.text-white {
    --bs-text-opacity: 1;
    color: goldenrod !important;
}
</style>
    <div class="section">
      <div class="row justify-content-center footer-cta" data-aos="fade-up">
        <div class="col-lg-7 mx-auto text-center">
          <h2 class="mb-4">Be a part of our growing real state agents</h2>
          <p>
            <a
              href="http://localhost/truszed/agent/login/register.php"
              target="_blank"
              class="btn btn-primary text-white py-3 px-4"
              >Apply for Real Estate agent</a
            >
          </p>
        </div>
        <!-- /.col-lg-7 -->
      </div>
      <!-- /.row -->
    </div>

    <div class="section section-5 bg-light">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-lg-6 mb-5">
            <h2 class="font-weight-bold heading text-primary mb-4">
              Our Agents
            </h2>
            <p class="text-black-50">
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam
              enim pariatur similique debitis vel nisi qui reprehenderit totam?
              Quod maiores.
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
            <div class="h-100 person">
              <img
                src="images/person_1-min.jpg"
                alt="Image"
                class="img-fluid"
              />

              <div class="person-contents">
                <h2 class="mb-0"><a href="#">James Doe</a></h2>
                <span class="meta d-block mb-3">Real Estate Agent</span>
                <p>
                  Lorem ipsum dolor sit amet consectetur adipisicing elit.
                  Facere officiis inventore cumque tenetur laboriosam, minus
                  culpa doloremque odio, neque molestias?
                </p>

                <ul class="social list-unstyled list-inline dark-hover">
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-twitter"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-facebook"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-linkedin"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-instagram"></span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
            <div class="h-100 person">
              <img
                src="images/person_2-min.jpg"
                alt="Image"
                class="img-fluid"
              />

              <div class="person-contents">
                <h2 class="mb-0"><a href="#">Jean Smith</a></h2>
                <span class="meta d-block mb-3">Real Estate Agent</span>
                <p>
                  Lorem ipsum dolor sit amet consectetur adipisicing elit.
                  Facere officiis inventore cumque tenetur laboriosam, minus
                  culpa doloremque odio, neque molestias?
                </p>

                <ul class="social list-unstyled list-inline dark-hover">
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-twitter"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-facebook"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-linkedin"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-instagram"></span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-md-6 col-lg-4 mb-5 mb-lg-0">
            <div class="h-100 person">
              <img
                src="images/person_3-min.jpg"
                alt="Image"
                class="img-fluid"
              />

              <div class="person-contents">
                <h2 class="mb-0"><a href="#">Alicia Huston</a></h2>
                <span class="meta d-block mb-3">Real Estate Agent</span>
                <p>
                  Lorem ipsum dolor sit amet consectetur adipisicing elit.
                  Facere officiis inventore cumque tenetur laboriosam, minus
                  culpa doloremque odio, neque molestias?
                </p>

                <ul class="social list-unstyled list-inline dark-hover">
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-twitter"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-facebook"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-linkedin"></span></a>
                  </li>
                  <li class="list-inline-item">
                    <a href="#"><span class="icon-instagram"></span></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="widget">
              <h3>Contact</h3>
              <address>here is address</address>
              <ul class="list-unstyled links">
                <li><a href="tel://11234567890">+234900000000</a></li>
                <li><a href="tel://11234567890">+234900000000</a></li>
                <li>
                  <a href="info@truszedproperties.com">info@truszedproperties.com</a>
                </li>
              </ul>
            </div>
            <!-- /.widget -->
          </div>
          <!-- /.col-lg-4 -->
          <div class="col-lg-4">
            <div class="widget">
              <h3>Sources</h3>
              <ul class="list-unstyled float-start links">
                <li><a href="#">About us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Vision</a></li>
                <li><a href="#">Mission</a></li>
                <li><a href="#">Terms</a></li>
                <li><a href="#">Privacy</a></li>
              </ul>
              <ul class="list-unstyled float-start links">
                <li><a href="#">Partners</a></li>
                <li><a href="#">Business</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Creative</a></li>
              </ul>
            </div>
            <!-- /.widget -->
          </div>
          <!-- /.col-lg-4 -->
          <div class="col-lg-4">
            <div class="widget">
              <h3>Links</h3>
              <ul class="list-unstyled links">
                <li><a href="#">Our Vision</a></li>
                <li><a href="#">About us</a></li>
                <li><a href="#">Contact us</a></li>
              </ul>

              <ul class="list-unstyled social">
                <li>
                  <a href="#"><span class="icon-instagram"></span></a>
                </li>
                <li>
                  <a href="#"><span class="icon-twitter"></span></a>
                </li>
                <li>
                  <a href="#"><span class="icon-facebook"></span></a>
                </li>
                <li>
                  <a href="#"><span class="icon-linkedin"></span></a>
                </li>
                <li>
                  <a href="#"><span class="icon-pinterest"></span></a>
                </li>
                <li>
                  <a href="#"><span class="icon-dribbble"></span></a>
                </li>
              </ul>
            </div>
            <!-- /.widget -->
          </div>
          <!-- /.col-lg-4 -->
        </div>
        <!-- /.row -->

        <div class="row mt-5">
          <div class="col-12 text-center">
            <!-- 
              **==========
              NOTE: 
              Please don't remove this copyright link unless you buy the license here https://untree.co/license/  
              **==========
            -->

            <p>
              Copyright &copy;
              <script>
                document.write(new Date().getFullYear());
              </script>
              . All Rights Reserved. &mdash; Designed <i>Truszed</i>
              
            </p>
           
          </div>
        </div>
      </div>
      <!-- /.container -->
    </div>
    
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Chat Container - Positioned at the bottom-right of the page */
        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        /* Chatbox itself (Initially hidden) */
        .chat-box {
            display: none; /* Hidden by default */
            width: 300px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .chat-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
        }

        .chat-messages {
            height: 200px;
            padding: 10px;
            overflow-y: auto;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }

        .message {
            padding: 8px;
            margin-bottom: 8px;
            border-radius: 4px;
            font-size: 14px;
        }

        .bot-message {
            background-color: #e0e0e0;
        }

        .user-message {
            background-color: #007bff;
            color: white;
            align-self: flex-end;
        }

        .chat-input-container {
            display: flex;
            padding: 10px;
        }

        .chat-input-container input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        .chat-input-container button {
            padding: 8px 16px;
            background-color: goldenrod;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Improved Chat Icon (Visible initially) */
        .open-chat-btn {
            padding: 16px;
            background-color: black;
            color: goldenrod;
            border: none;
            border-radius: 50%;
            font-size: 28px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            width: 60px; /* Adjust size */
            height: 60px; /* Adjust size */
        }

        /* Chat Bubble Icon (SVG) */
        .open-chat-btn svg {
            width: 24px;
            height: 24px;
            fill: goldenrod;
        }

        /* Pending Message Count Badge */
        .message-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: goldenrod;
            color: black;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block; /* Ensure it is visible */
        }

        /* Close Button on Chatbox */
        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }

        .open-chat-btn:hover {
            background-color: black;
        }

    </style>
</head>
<body>

<!-- Your website content -->


<!-- Chatbox Container -->
<div class="chat-container">
    <!-- Chat Icon (Visible initially) -->
    <a href="user_message.php">
        <button class="open-chat-btn">
            <!-- SVG Chat Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M3 19h4v4h1.5l5-5h5.5c1.1 0 1.99-.9 1.99-2L21 6c0-1.1-.89-2-2-2H5c-1.1 0-2 .9-2 2v12c0 1.1.89 2 2 2zm0-14h16v12H5V5zm0 12v1.5l5-5h5.5c.83 0 1.5-.67 1.5-1.5V6H4v11h1z"/>
            </svg>
            <!-- Message Count Badge (Visible only when there are pending messages) -->
            <span class="message-count" id="messageCount"><?php echo $unread_count; ?></span>

        </button>
    </a>
</div>

<!-- Add your website footer here -->




    <!-- Preloader -->
    <div id="overlayer"></div>
    <div class="loader">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/tiny-slider.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/navbar.js"></script>
    <script src="js/counter.js"></script>
    <script src="js/custom.js"></script>
  </body>
</html>
