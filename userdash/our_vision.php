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
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Untree.co" />
    <link rel="shortcut icon" href="favicon.png" />

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
      Property &mdash; Free Bootstrap 5 Website Template by Untree.co
    </title>
    <style>
  .menu-bg-wrap {
    background-color: black;
    padding: 20px 30px;
    position: relative;
    float: left;
    width: 100%;
    border-radius: 7px;
    -webkit-box-shadow: 0 15px 30px -15px rgba(0, 0, 0, 0.1);
    box-shadow: 0 15px 30px -15px rgba(0, 0, 0, 0.1);
    color: goldenrod;
}
.site-nav .site-navigation .site-menu > li > a {
    font-size: 14px;
    padding: 10px 15px;
    display: inline-block;
    text-decoration: none !important;
    color:goldenrod;
}
.site-nav .site-navigation .site-menu > li.active > a {
    color:goldenrod;
}
.logo {
    font-size: 24px;
    color: goldenrod!important;
    font-weight: 500;
}
.btn.btn-primary {
    background: black;
    color: goldenrod;
}
</style>
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

    <nav class="site-nav">
      <div class="container">
        <div class="menu-bg-wrap">
          <div class="site-navigation">
          <img src="newlogo.png" alt="Truszed Properties" style="height: 40px; width: 65px;">

            <ul
              class="js-clone-nav d-none d-lg-inline-block text-start site-menu float-end"
            >
              <li><a href="index.html">Home</a></li>
              <li class="has-children">
                <a href="properties.html">Properties</a>
                <ul class="dropdown">
                  <li><a href="#">Buy Property</a></li>
                  <li><a href="#">Sell Property</a></li>
                  <li class="has-children">
                    <a href="#">Dropdown</a>
                    <ul class="dropdown">
                      <li><a href="#">Sub Menu One</a></li>
                      <li><a href="#">Sub Menu Two</a></li>
                      <li><a href="#">Sub Menu Three</a></li>
                    </ul>
                  </li>
                </ul>
              </li>
              <li><a href="services.html">Services</a></li>
              <!-- <li class="active"><a href="about.html">About</a></li> -->
              <li><a href="contact.html">Contact Us</a></li>
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

    <div
      class="hero page-inner overlay"
      style="background-image: url('images/hero_bg_3.jpg')"
    >
      <div class="container">
        <div class="row justify-content-center align-items-center">
          <div class="col-lg-9 text-center mt-5">
            <h1 class="heading" data-aos="fade-up">Our Vision</h1>

            <nav
              aria-label="breadcrumb"
              data-aos="fade-up"
              data-aos-delay="200"
            >
              <ol class="breadcrumb text-center justify-content-center">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li
                  class="breadcrumb-item active text-white-50"
                  aria-current="page"
                >
                  Our Vision
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="container">
        <div class="row text-left mb-5">
          <div class="col-12">
            <!-- <h2 class="font-weight-bold heading text-primary mb-4">Our Vision</h2> -->
          </div>
          <div class="col-lg-6">
            <!-- <p class="text-black-50">
            Truszed Properties (TPL) is a Limited Liability Company with its head office located in Abuja, the Federal Capital Territory, Nigeria. TPL is a supply company dedicated to providing top-quality products and exceptional services to our clients. We specialize in creating exquisite and premium residential homes and real estate suitable for investment purposes through a combination of acquisition, renovation, and leasing. The company provides ready-to-move-in properties for home renters, sales, and overnight stays.
   
            </p> -->
            <style>
                h2{
                    background-color: goldenrod;
                    color: #fff;
                    padding-left: 10px;
                    border-top-right-radius: 40px;
                    border: black;


                }
            </style>
            <p class="text-black-50">
             <h2>OUR VISION</h2>
             To be the leading supplier in Nigeria, recognized for our commitment to excellence, quality, and customer satisfaction. We aim to nurture trusting relationships that set the standard for quality and comfortable living spaces beyond the average. Our goal is to optimize the business scope for sustainable wealth creation, while providing economic opportunities that guarantee financial freedom and a quality lifestyle for all stakeholders.
            </p>
            <!-- <p class="text-black-50">
              <h2>OUR MISSION</h2>
            <p>Our mission is to deliver innovative solutions, unparalleled customer service, and unmatched value to our clients. We strive to build long-lasting relationships, foster growth, and contribute to the success of our customers. We aim to connect individuals to comfortable and excellent living conditions at considerable rates and provide high-yield investment outcomes.</p>
    
            </p> -->
          </div>
          <div class="col-lg-6">
            <p class="text-black-50">
             <!-- <h2>VALUE STATEMENT</h2>
             <p>We prioritize trust, integrity, quality, hospitality, and service to all. At Truszed Properties, we are committed to building long-lasting relationships with our clients, providing excellent service, and ensuring the highest level of satisfaction with every project we undertake.</p>
    
            </p> -->
            <!-- <p class="text-black-50">
              Enim, nisi labore exercitationem facere cupiditate nobis quod
              autem veritatis quis minima expedita. Cumque odio illo iusto
              reiciendis, labore impedit omnis, nihil aut atque, facilis
              necessitatibus asperiores porro qui nam.
            </p> -->
          </div>
        </div>
      </div>
    </div>

    <div class="section pt-0">
      <div class="container">
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
                <h3 class="heading">Quality properties</h3>
                <p class="text-black-50">
                Explore our collection of unique properties, each offering something special. Whether you're looking for a modern apartment, a cozy home, or an investment opportunity, you'll find it here.
                </p>
              </div>
            </div>

            <div class="d-flex feature-h">
              <span class="wrap-icon me-3">
                <span class="icon-person"></span>
              </span>
              <div class="feature-text">
                <h3 class="heading">Top rated agents</h3>
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
                <h3 class="heading">Easy and safe</h3>
                <p class="text-black-50">
                All our properties are verified and legit, offering you peace of mind. With trusted listings and thorough background checks, we ensure every property meets high standards.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
            <img src="images/img_1.jpg" alt="Image" class="img-fluid" />
          </div>
          <div class="col-md-4 mt-lg-5" data-aos="fade-up" data-aos-delay="100">
            <img src="images/img_3.jpg" alt="Image" class="img-fluid" />
          </div>
          <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <img src="images/img_2.jpg" alt="Image" class="img-fluid" />
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
                ><span class="countup text-primary"><?php echo $rentCount; ?></span></span
              >
              <span class="caption text-black-50">Rentals</span>
            </div>
          </div>
          <div
            class="col-6 col-sm-6 col-md-6 col-lg-3"
            data-aos="fade-up"
            data-aos-delay="400"
          >
            <div class="counter-wrap mb-5 mb-lg-0">
              <span class="number"
                ><span class="countup text-primary"><?php echo $sellCount; ?></span></span
              >
              <span class="caption text-black-50">Sales</span>
            </div>
          </div>
          <div
            class="col-6 col-sm-6 col-md-6 col-lg-3"
            data-aos="fade-up"
            data-aos-delay="500"
          >
            <div class="counter-wrap mb-5 mb-lg-0">
              <span class="number"
                ><span class="countup text-primary"><?php echo $totalProperties; ?></span></span
              >
              <span class="caption text-black-50">Total Properties</span>
            </div>
          </div>
          <div
            class="col-6 col-sm-6 col-md-6 col-lg-3"
            data-aos="fade-up"
            data-aos-delay="600"
          >
            <div class="counter-wrap mb-5 mb-lg-0">
              <span class="number"
                ><span class="countup text-primary"><?php echo $agentCount; ?></span></span
              >
              <span class="caption text-black-50">Total Agent</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="section sec-testimonials bg-light">
      <div class="container">
       

      
    <div class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="widget">
              <h3>Contact</h3>
              <address>address here</address>
              <ul class="list-unstyled links">
                <li><a href="tel://11234567890">+1(123)-456-7890</a></li>
                <li><a href="tel://11234567890">+1(123)-456-7890</a></li>
                <li>
                  <a href="mailto:info@truszedproperties.com">info@truszedproperties.com</a>
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
                <li><a href="#">Our Vision</a></li>
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
                <li><a href="#">Our Vision</a></li>
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
              . All Rights Reserved. &mdash; Truszed Properties
              
            </p>
            
          </div>
        </div>
      </div>
      <!-- /.container -->
    </div>
    <!-- /.site-footer -->

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
