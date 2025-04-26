<?php
// Start the session to access session variables
session_start();

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

// Initialize variables
$error_message = '';
$success_message = '';

// Retrieve the admin_id from the session
$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

// Ensure admin_id is valid
if ($admin_id === null) {
    $error_message = "You must be logged in to submit the form.";
}

// Check if the user is an admin with ID 1, 2, or 3
$is_special_admin = in_array($admin_id, [1, 2, 3]);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error_message) {
    // Sanitize and validate form inputs
    $property_name = mysqli_real_escape_string($conn, $_POST['property_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $dimensions = mysqli_real_escape_string($conn, $_POST['dimensions']);
    $property_type = mysqli_real_escape_string($conn, $_POST['property_type']);
    $bedrooms = mysqli_real_escape_string($conn, $_POST['bedrooms']);
    $bathrooms = mysqli_real_escape_string($conn, $_POST['bathrooms']);
    $toilets = mysqli_real_escape_string($conn, $_POST['toilets']);
    $parking_space = mysqli_real_escape_string($conn, $_POST['parking_space']);
    $market_status = mysqli_real_escape_string($conn, $_POST['market_status']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $lga = isset($_POST['lga']) ? mysqli_real_escape_string($conn, $_POST['lga']) : null;
    $property_details = mysqli_real_escape_string($conn, $_POST['property_details']);

    // Determine agent_id and approval status
    if ($is_special_admin) {
        // For admins with IDs 1, 2, or 3, use their admin_id as agent_id and auto-approve
        $agent_id = $admin_id;
        $approved = 1; // Auto-approved
    } else {
        // For other users (agents), use the agent_id from the form and set to pending approval
        $agent_id = isset($_POST['agent_id']) ? mysqli_real_escape_string($conn, $_POST['agent_id']) : null;
        $approved = 0; // Pending approval
    }

    // Validate agent_id
    if (empty($agent_id)) {
        $error_message = "Agent ID is required.";
    }

    // File upload for post_image
    $post_image = $_FILES['post_image']['name'];
    $target_dir = "../agent/agent_dashboard/uploads/";
    $target_file = $target_dir . basename($post_image);

    // Check if the post_image is uploaded
    if (!empty($post_image)) {
        // Check if the file is a valid image
        if (getimagesize($_FILES["post_image"]["tmp_name"]) === false) {
            $error_message = "Post image is not a valid image.";
        } else {
            // Move the uploaded file to the target directory
            if (!move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
                $error_message = "Sorry, there was an error uploading the post image.";
            }
        }
    } else {
        $error_message = "Post image is required.";
    }

    // Handle multiple images upload for other_images
    $other_images = [];
    if (isset($_FILES['other_images']) && count($_FILES['other_images']['name']) > 0) {
        // Loop through the uploaded files
        for ($i = 0; $i < count($_FILES['other_images']['name']); $i++) {
            $other_image_name = $_FILES['other_images']['name'][$i];
            $other_image_tmp_name = $_FILES['other_images']['tmp_name'][$i];
            $target_other_image_file = $target_dir . basename($other_image_name);

            // Check if the file is a valid image
            if (!empty($other_image_name) && getimagesize($other_image_tmp_name) !== false) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($other_image_tmp_name, $target_other_image_file)) {
                    $other_images[] = $other_image_name;
                } else {
                    $error_message = "Error uploading file: " . $other_image_name;
                    break;
                }
            } elseif (!empty($other_image_name)) {
                $error_message = "File " . $other_image_name . " is not a valid image.";
                break;
            }
        }
    }

    // Convert the array of other images to a comma-separated string
    $other_images_str = implode(',', $other_images);

    // If no errors, proceed to insert data into the database
    if (empty($error_message)) {
        // Insert the form data into the database, including agent_id and approved status
        $sql = "INSERT INTO agent_properties 
            (property_name, price, address, dimensions, property_type, bedrooms, bathrooms, toilets, parking_space, post_image, other_images, market_status, state, lga, property_details, agent_id, status)
            VALUES ('$property_name', '$price', '$address', '$dimensions', '$property_type', '$bedrooms', '$bathrooms', '$toilets', '$parking_space', '$post_image', '$other_images_str', '$market_status', '$state', '$lga', '$property_details', '$agent_id', '$approved')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Property added successfully!";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Property Form</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 9 0%;
        max-width: 800px;
        /* Maximum width for larger screens */
        margin: 0 auto;
        padding: 20px;
        background-color: white;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        /* Add some space below the heading */
    }

    label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    textarea,
    select {
        width: 100%;
        padding: 10px;
        margin: 8px 0;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 14px;
        box-sizing: border-box;
    }

    button {
        background-color: purple;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
        margin-top: 20px;
    }

    button:hover {
        background-color: #6a1b9a;
        /* Darker shade of purple */
    }

    .error {
        color: red;
        font-size: 14px;
    }

    .success {
        color: green;
        font-size: 14px;
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 550px) {
        .container {
            width: 90%;
            /* Adjust width for mobile screens */
            padding: 15px;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea,
        select {
            font-size: 16px;
            /* Increase font size for mobile */
        }

        button {
            font-size: 18px;
            /* Increase button font size on mobile */
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Agent Property Form</h2>

        <!-- Display success or error message -->
        <?php if ($error_message) { ?>
        <div class="error"><?php echo $error_message; ?></div>
        <?php } elseif ($success_message) { ?>
        <div class="success"><?php echo $success_message; ?></div>
        <?php } ?>

        <!-- Form -->
        <form action="make_property.php" method="POST" enctype="multipart/form-data">
            <!-- Agent ID input field (shown only for non-special admins) -->
            <?php if (!$is_special_admin) { ?>
            <label for="agent_id">Agent ID:</label>
            <input type="text" id="agent_id" name="agent_id" required>
            <?php } ?>

            <!-- Property Name -->
            <label for="property_name">Property Name:</label>
            <input type="text" id="property_name" name="property_name" required>

            <!-- Price -->
            <label for="price">Price ($):</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <!-- Address -->
            <label for="address">Address:</label>
            <textarea id="address" name="address" rows="4" required></textarea>

            <!-- Dimensions -->
            <label for="dimensions">Dimensions (e.g., 2000 sqft):</label>
            <input type="text" id="dimensions" name="dimensions">

            <!-- Property Type (Rent/Sell) -->
            <label for="property_type">Property Type:</label>
            <select id="property_type" name="property_type" required>
                <option value="rent">Rent</option>
                <option value="sell">Sell</option>
            </select>

            <!-- Bedrooms -->
            <label for="bedrooms">Bedrooms:</label>
            <input type="number" id="bedrooms" name="bedrooms" required>

            <!-- Bathrooms -->
            <label for="bathrooms">Bathrooms:</label>
            <input type="number" id="bathrooms" name="bathrooms" required>

            <!-- Toilets -->
            <label for="toilets">Toilets:</label>
            <input type="number" id="toilets" name="toilets" required>

            <!-- Parking Spaces -->
            <label for="parking_space">Parking Space:</label>
            <input type="number" id="parking_space" name="parking_space" required>

            <!-- Post Image -->
            <label for="post_image">Post Image:</label>
            <input type="file" id="post_image" name="post_image" required>

            <!-- Other Images (optional) -->
            <label for="other_images">Other Images:</label>
            <input type="file" id="other_images" name="other_images[]" accept="image/*" multiple>

            <!-- Market Status -->
            <label for="market_status">Market Status:</label>
            <select id="market_status" name="market_status" required>
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>

            <!-- State -->
            <label for="state">State:</label>
            <select id="state" name="state" required>
                <option value="">Select State</option>
            </select>

            <!-- LGA -->
            <label for="lga">Local Government Area:</label>
            <select id="lga" name="lga" required>
                <option value="">Select LGA</option>
            </select>

            <!-- Property Details -->
            <label for="property_details">Property Details:</label>
            <textarea id="property_details" name="property_details" rows="4"></textarea>

            <!-- Submit Button -->
            <button type="submit">Submit Property</button>
        </form>
    </div>

    <script>
    // All Nigerian States with their Local Government Areas (LGAs)
    const statesAndLGAs = {
        "Abia": [
            "Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North", "Isiala Ngwa South",
            "Isuikwuato", "Ugwunagbo", "Ohafia", "Umuahia North", "Umuahia South", "Ukwa East", "Ukwa West"
        ],
        "Adamawa": [
            "Demsa", "Fufore", "Ganye", "Girei", "Gombi", "Jada", "Larmurde", "Mayo-Belwa", "Michika",
            "Mubi North",
            "Mubi South", "Numan", "Shelleng", "Song", "Toungo", "Yola North", "Yola South"
        ],
        "Akwa Ibom": [
            "Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Ibeno",
            "Ibesikpo Asutan",
            "Ikono", "Ikot Abasi", "Ini", "Itu", "Mbo", "Mkpat Enin", "Nsit Atai", "Nsit Ibom", "Nsit Ubuim",
            "Obot Akara", "Okobo", "Oron", "Oruk Anam", "Udung Uko", "Uruan", "Uyo"
        ],
        "Anambra": [
            "Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum",
            "Dunukofia",
            "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North", "Nnewi South",
            "Ogbaru",
            "Onitsha North", "Onitsha South", "Oyi"
        ],
        "Bauchi": [
            "Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade",
            "Itas Gadau",
            "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro", "Zaki"
        ],
        "Bayelsa": [
            "Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw", "Yenagoa"
        ],
        "Benue": [
            "Ado", "Agatu", "Apa", "Buruku", "Guma", "Gwer-East", "Gwer-West", "Katsina-Ala", "Konshisha",
            "Kwande",
            "Logo", "Makurdi", "Mbala", "Obi", "Ogbadibo", "Ohimini", "Okpokwu", "Oturkpo", "Tarka", "Ukum",
            "Vandeikya"
        ],
        "Borno": [
            "Abadam", "Askira/Uba", "Bama", "Bayo", "Damboa", "Dikwa", "Gubio", "Guzamala", "Jere", "Kaga",
            "Kala/Balge",
            "Konduga", "Mafa", "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai",
            "Shani"
        ],
        "Cross River": [
            "Akpabuyo", "Bakassi", "Calabar Municipal", "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra",
            "Odukpani",
            "Ogoja", "Yakurr", "Yala"
        ],
        "Delta": [
            "Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ika North East", "Ika South", "Isoko North",
            "Isoko South",
            "Ndokwa East", "Ndokwa West", "Okpe", "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu",
            "Ughelli North",
            "Ughelli South", "Ukwuani", "Warri North", "Warri South", "Warri South West"
        ],
        "Ebonyi": [
            "Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo",
            "Ishielu", "Ivo",
            "Izzi", "Ohaozara", "Ohaukwu", "Onicha"
        ],
        "Edo": [
            "Akoko-Edo", "Esan Central", "Esan North-East", "Esan South-East", "Esan West", "Egor",
            "Ikpoba-Okha", "Orhionmwon",
            "Oredo", "Ovia North-East", "Ovia South-West", "Uhunmwonde"
        ],
        "Ekiti": [
            "Ado-Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Ido Osi", "Ijero", "Ikere",
            "Ilejemeje",
            "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"
        ],
        "Enugu": [
            "Aninri", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo Etiti", "Igbo-Eze North",
            "Igbo-Eze South",
            "Isi-Uzo", "Nkanu East", "Nkanu West", "Oji River", "Udenu", "Udi", "Uzo-Uwani"
        ],
        "Gombe": [
            "Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada",
            "Shongom", "Yamaltu/Deba"
        ],
        "Imo": [
            "Aboh-Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South",
            "Ihitte/Uboma",
            "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor-Okpala", "Njaba", "Njirimogha", "Nkwerre",
            "Obowo", "Oguta",
            "Ohaji/Egbema", "Orlu", "Orsu", "Oru East", "Oru West", "Owerri Municipal", "Owerri North",
            "Owerri West"
        ],
        "Jigawa": [
            "Auyo", "Babura", "Birnin Kudu", "Buji", "Dutse", "Garki", "Gumel", "Guri", "Gwadabawa", "Hadejia",
            "Kafin Hausa",
            "Kaugama", "Kazaure", "Kiri Kasama", "Maigatari", "Miga", "Ringim", "Roni", "Sule Tankarkar",
            "Taura", "Yankwashi"
        ],
        "Kaduna": [
            "Birnin Gwari", "Chikun", "Giwa", "Igabi", "Jaba", "Jama'a", "Kachia", "Kaduna North",
            "Kaduna South", "Kagarko",
            "Kajuru", "Kano", "Kauru", "Kaura", "Kubau", "Kudan", "Lere", "Makarfi", "Sabon Gari", "Sanga",
            "Soba", "Zangon Kataf"
        ],
        "Kano": [
            "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Doguwa", "Fagge", "Gaya",
            "Garko", "Gwale",
            "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Madobi", "Makoda",
            "Minjibir", "Nasarawa",
            "Rano", "Rimin Gado", "Shanono", "Sumaila", "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada",
            "Ungogo"
        ],
        "Katsina": [
            "Bakori", "Batagarawa", "Batsari", "Bwari", "Dandume", "Danja", "Daura", "Dutsin-Ma", "Funtua",
            "Ingawa", "Jibia",
            "Kaita", "Kankara", "Katsina", "Katsina North", "Kurfi", "Kusada", "Maiadua", "Malumfashi", "Mani",
            "Mashi",
            "Munhaina", "Rimi", "Sabuwa", "Safana", "Zango"
        ],
        "Kebbi": [
            "Aleiro", "Arewa", "Augie", "Bagudo", "Birnin Kebbi", "Dandi", "Danko-Wasagu", "Gwandu", "Jega",
            "Kalgo",
            "Koko-Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru", "Wasagu", "Zuru"
        ],
        "Kogi": [
            "Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela-Odolu", "Ijumu",
            "Kabba/Bunu",
            "Kogi", "Lokoja", "Mopa-Muro", "Ofu", "Okehi", "Okene", "Olamaboro", "Omala", "Yagba East",
            "Yagba West"
        ],
        "Kwara": [
            "Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West",
            "Irepodun", "Isin",
            "Kaiama", "Moro", "Offa", "Oke-Ero", "Oyun", "Pategi"
        ],
        "Lagos": [
            "Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Bangbo",
            "Ibeju-Lekki", "Ifako-Ijaiye",
            "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland", "Mushin", "Ojo", "Oshodi-Isolo",
            "Shomolu", "Surulere"
        ],
        "Nasarawa": [
            "Akwanga", "Alushi", "Doma", "Keffi", "Kokona", "Lafia", "Nasarawa", "Nasarawa Eggon", "Obi",
            "Toto", "Wamba"
        ],
        "Niger": [
            "Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha",
            "Kontagora",
            "Mokwa", "Mashegu", "Muya", "Paikoro", "Rafi", "Shiroro", "Suleja", "Tafa", "Wushishi"
        ],
        "Ogun": [
            "Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Ewekoro", "Ifo", "Ijebu East", "Ijebu North",
            "Ijebu North-East",
            "Ijebu Ode", "Ikenne", "Imeko-Afon", "Ipokia", "Obafemi-Owode", "Odeda", "Odogbolu",
            "Ogun Waterside", "Remo North",
            "Shagamu"
        ],
        "Ondo": [
            "Akure North", "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile-Oluji/Okeigbo", "Irele",
            "Odigbo",
            "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"
        ],
        "Osun": [
            "Aiyedire", "Atakunmosa East", "Atakunmosa West", "Boluwaduro", "Boripe", "Ede North", "Ede South",
            "Egbedore",
            "Ife Central", "Ife East", "Ife North", "Ife South", "Ilesa East", "Ilesa West", "Irepodun", "Iwo",
            "Obokun",
            "Ola Oluwa", "Olorunda", "Osogbo"
        ],
        "Oyo": [
            "Akinyele", "Atiba", "Atigun", "Egbeda", "Ibadan North", "Ibadan North-East", "Ibadan North-West",
            "Ibadan South-East",
            "Ibadan South-West", "Ibarapa Central", "Ibarapa East", "Ibarapa North", "Ido", "Irepo", "Iskire",
            "Ogbomosho North",
            "Ogbomosho South", "Olorunsogo", "Oluyole", "Ona-Ara", "Saki East", "Saki West"
        ],
        "Plateau": [
            "Barkin Ladi", "Bassa", "Bokkos", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke",
            "Langtang North",
            "Langtang South", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam", "Wase"
        ],
        "Rivers": [
            "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Bonny", "Emohua", "Gokana", "Ikwerre",
            "Khana", "Obio-Akpor",
            "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omumma", "Opobo/Nkoro", "Port Harcourt", "Tai"
        ],
        "Sokoto": [
            "Binji", "Bodinga", "Dange/Shuni", "Gada", "Goronyo", "Gudu", "Illela", "Kebbe", "Kware", "Rabah",
            "Shagari", "Sokoto North",
            "Sokoto South", "Tambuwal", "Tangaza", "Tureta", "Wamakko", "Wurno", "Yabo"
        ],
        "Taraba": [
            "Ardo-Kola", "Bali", "Donga", "Gashaka", "Gumti", "Jalingo", "Karim Lamido", "Kumi", "Lau",
            "Sunkani", "Takum",
            "Ussa", "Wukari", "Yorro"
        ],
        "Yobe": [
            "Bade", "Bursari", "Damaturu", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa",
            "Machina", "Nangere",
            "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yobe North"
        ],
        "Zamfara": [
            "Anka", "Bakura", "Birnin Magaji", "Bukkuyum", "Gummi", "Gusau", "Kaura Namoda", "Maru", "Shinkafi",
            "Talata Mafara",
            "Tsafe", "Zamfara North"
        ]
    };

    // Populate the state dropdown
    function populateStates() {
        const stateDropdown = document.getElementById('state');
        for (let state in statesAndLGAs) {
            const option = document.createElement('option');
            option.value = state;
            option.textContent = state;
            stateDropdown.appendChild(option);
        }
    }

    // Populate the LGA dropdown based on selected state
    function populateLGAs() {
        const stateDropdown = document.getElementById('state');
        const lgaDropdown = document.getElementById('lga');
        const selectedState = stateDropdown.value;

        // Clear the current options
        lgaDropdown.innerHTML = '<option value="">Select LGA</option>';

        // Populate LGAs based on selected state
        if (selectedState && statesAndLGAs[selectedState]) {
            const lgas = statesAndLGAs[selectedState];
            lgas.forEach(lga => {
                const option = document.createElement('option');
                option.value = lga;
                option.textContent = lga;
                lgaDropdown.appendChild(option);
            });
        }
    }

    // Call populateStates on page load
    window.onload = function() {
        populateStates();
    };

    // Event listener to update LGAs when state is selected
    document.getElementById('state').addEventListener('change', populateLGAs);
    </script>


</body>

</html>