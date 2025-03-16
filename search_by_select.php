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

// Step 2: Get the selected state from the URL (via GET method)
$selectedState = isset($_GET['state']) ? $_GET['state'] : '';
$selectedLGA = isset($_GET['lga']) ? $_GET['lga'] : '';

// Step 3: If a state is selected, filter properties by state and LGA
$sql = "SELECT * FROM agent_properties WHERE state LIKE ?";
$params = ["%$selectedState%"];

if (!empty($selectedLGA)) {
    $sql .= " AND lga LIKE ?";
    $params[] = "%$selectedLGA%";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('s', count($params)), ...$params);
$stmt->execute();
$result = $stmt->get_result();
// List of states and LGAs
$statesAndLGAs = [
    "Abia" => [
        "Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North", "Isiala Ngwa South",
        "Isuikwuato", "Ugwunagbo", "Ohafia", "Umuahia North", "Umuahia South", "Ukwa East", "Ukwa West"
    ],
    "Adamawa" => [
        "Demsa", "Fufore", "Ganye", "Girei", "Gombi", "Jada", "Larmurde", "Mayo-Belwa", "Michika", "Mubi North",
        "Mubi South", "Numan", "Shelleng", "Song", "Toungo", "Yola North", "Yola South"
    ],
    "Akwa Ibom" => [
        "Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Ibeno", "Ibesikpo Asutan",
        "Ikono", "Ikot Abasi", "Ini", "Itu", "Mbo", "Mkpat Enin", "Nsit Atai", "Nsit Ibom", "Nsit Ubuim",
        "Obot Akara", "Okobo", "Oron", "Oruk Anam", "Udung Uko", "Uruan", "Uyo"
    ],
    "Anambra" => [
        "Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum", "Dunukofia",
        "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North", "Nnewi South", "Ogbaru",
        "Onitsha North", "Onitsha South", "Oyi"
    ],
    "Bauchi" => [
        "Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade", "Itas Gadau",
        "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro", "Zaki"
    ],
    "Bayelsa" => [
        "Brass", "Ekeremor", "Kolokuma/Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw", "Yenagoa"
    ],
    "Benue" => [
        "Ado", "Agatu", "Apa", "Buruku", "Guma", "Gwer-East", "Gwer-West", "Katsina-Ala", "Konshisha", "Kwande",
        "Logo", "Makurdi", "Mbala", "Obi", "Ogbadibo", "Ohimini", "Okpokwu", "Oturkpo", "Tarka", "Ukum", "Vandeikya"
    ],
    "Borno" => [
        "Abadam", "Askira/Uba", "Bama", "Bayo", "Damboa", "Dikwa", "Gubio", "Guzamala", "Jere", "Kaga", "Kala/Balge",
        "Konduga", "Mafa", "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"
    ],
    "Cross River" => [
        "Akpabuyo", "Bakassi", "Calabar Municipal", "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Odukpani",
        "Ogoja", "Yakurr", "Yala"
    ],
    "Delta" => [
        "Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ika North East", "Ika South", "Isoko North", "Isoko South",
        "Ndokwa East", "Ndokwa West", "Okpe", "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North",
        "Ughelli South", "Ukwuani", "Warri North", "Warri South", "Warri South West"
    ],
    "Ebonyi" => [
        "Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo", "Ishielu", "Ivo",
        "Izzi", "Ohaozara", "Ohaukwu", "Onicha"
    ],
    "Edo" => [
        "Akoko-Edo", "Esan Central", "Esan North-East", "Esan South-East", "Esan West", "Egor", "Ikpoba-Okha", "Orhionmwon",
        "Oredo", "Ovia North-East", "Ovia South-West", "Uhunmwonde"
    ],
    "Ekiti" => [
        "Ado-Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Ido Osi", "Ijero", "Ikere", "Ilejemeje",
        "Irepodun/Ifelodun", "Ise/Orun", "Moba", "Oye"
    ],
    "Enugu" => [
        "Aninri", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo Etiti", "Igbo-Eze North", "Igbo-Eze South",
        "Isi-Uzo", "Nkanu East", "Nkanu West", "Oji River", "Udenu", "Udi", "Uzo-Uwani"
    ],
    "Gombe" => [
        "Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada", "Shongom", "Yamaltu/Deba"
    ],
    "Imo" => [
        "Aboh-Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South", "Ihitte/Uboma",
        "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor-Okpala", "Njaba", "Njirimogha", "Nkwerre", "Obowo", "Oguta",
        "Ohaji/Egbema", "Orlu", "Orsu", "Oru East", "Oru West", "Owerri Municipal", "Owerri North", "Owerri West"
    ],
    "Jigawa" => [
        "Auyo", "Babura", "Birnin Kudu", "Buji", "Dutse", "Garki", "Gumel", "Guri", "Gwadabawa", "Hadejia", "Kafin Hausa",
        "Kaugama", "Kazaure", "Kiri Kasama", "Maigatari", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura", "Yankwashi"
    ],
    "Kaduna" => [
        "Birnin Gwari", "Chikun", "Giwa", "Igabi", "Jaba", "Jama'a", "Kachia", "Kaduna North", "Kaduna South", "Kagarko",
        "Kajuru", "Kano", "Kauru", "Kaura", "Kubau", "Kudan", "Lere", "Makarfi", "Sabon Gari", "Sanga", "Soba", "Zangon Kataf"
    ],
    "Kano" => [
        "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Doguwa", "Fagge", "Gaya", "Garko", "Gwale",
        "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Madobi", "Makoda", "Minjibir", "Nasarawa",
        "Rano", "Rimin Gado", "Shanono", "Sumaila", "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo"
    ],
    "Katsina" => [
        "Bakori", "Batagarawa", "Batsari", "Bwari", "Dandume", "Danja", "Daura", "Dutsin-Ma", "Funtua", "Ingawa", "Jibia",
        "Kaita", "Kankara", "Katsina", "Katsina North", "Kurfi", "Kusada", "Maiadua", "Malumfashi", "Mani", "Mashi",
        "Munhaina", "Rimi", "Sabuwa", "Safana", "Zango"
    ],
    "Kebbi" => [
        "Aleiro", "Arewa", "Augie", "Bagudo", "Birnin Kebbi", "Dandi", "Danko-Wasagu", "Gwandu", "Jega", "Kalgo",
        "Koko-Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru", "Wasagu", "Zuru"
    ],
    "Kogi" => [
        "Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela-Odolu", "Ijumu", "Kabba/Bunu",
        "Kogi", "Lokoja", "Mopa-Muro", "Ofu", "Okehi", "Okene", "Olamaboro", "Omala", "Yagba East", "Yagba West"
    ],
    "Kwara" => [
        "Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West", "Irepodun", "Isin",
        "Kaiama", "Moro", "Offa", "Oke-Ero", "Oyun", "Pategi"
    ],
    "Lagos" => [
        "Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Bangbo", "Ibeju-Lekki", "Ifako-Ijaiye",
        "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland", "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"
    ],
    "Nasarawa" => [
        "Akwanga", "Alushi", "Doma", "Keffi", "Kokona", "Lafia", "Nasarawa", "Nasarawa Eggon", "Obi", "Toto", "Wamba"
    ],
    "Niger" => [
        "Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha", "Kontagora",
        "Mokwa", "Mashegu", "Muya", "Paikoro", "Rafi", "Shiroro", "Suleja", "Tafa", "Wushishi"
    ],
    "Ogun" => [
        "Abeokuta North", "Abeokuta South", "Ado-Odo/Ota", "Ewekoro", "Ifo", "Ijebu East", "Ijebu North", "Ijebu North-East",
        "Ijebu Ode", "Ikenne", "Imeko-Afon", "Ipokia", "Obafemi-Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North",
        "Shagamu"
    ],
    "Ondo" => [
        "Akure North", "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile-Oluji/Okeigbo", "Irele", "Odigbo",
        "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"
    ],
    "Osun" => [
        "Aiyedire", "Atakunmosa East", "Atakunmosa West", "Boluwaduro", "Boripe", "Ede North", "Ede South", "Egbedore",
        "Ife Central", "Ife East", "Ife North", "Ife South", "Ilesa East", "Ilesa West", "Irepodun", "Iwo", "Obokun",
        "Ola Oluwa", "Olorunda", "Osogbo"
    ],
    "Oyo" => [
        "Akinyele", "Atiba", "Atigun", "Egbeda", "Ibadan North", "Ibadan North-East", "Ibadan North-West", "Ibadan South-East",
        "Ibadan South-West", "Ibarapa Central", "Ibarapa East", "Ibarapa North", "Ido", "Irepo", "Iskire", "Ogbomosho North",
        "Ogbomosho South", "Olorunsogo", "Oluyole", "Ona-Ara", "Saki East", "Saki West"
    ],
    "Plateau" => [
        "Barkin Ladi", "Bassa", "Bokkos", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke", "Langtang North",
        "Langtang South", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam", "Wase"
    ],
    "Rivers" => [
        "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Bonny", "Emohua", "Gokana", "Ikwerre", "Khana", "Obio-Akpor",
        "Ogba/Egbema/Ndoni", "Ogu/Bolo", "Okrika", "Omumma", "Opobo/Nkoro", "Port Harcourt", "Tai"
    ],
    "Sokoto" => [
        "Binji", "Bodinga", "Dange/Shuni", "Gada", "Goronyo", "Gudu", "Illela", "Kebbe", "Kware", "Rabah", "Shagari", "Sokoto North",
        "Sokoto South", "Tambuwal", "Tangaza", "Tureta", "Wamakko", "Wurno", "Yabo"
    ],
    "Taraba" => [
        "Ardo-Kola", "Bali", "Donga", "Gashaka", "Gumti", "Jalingo", "Karim Lamido", "Kumi", "Lau", "Sunkani", "Takum",
        "Ussa", "Wukari", "Yorro"
    ],
    "Yobe" => [
        "Bade", "Bursari", "Damaturu", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa", "Machina", "Nangere",
        "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yobe North"
    ],
    "Zamfara" => [
        "Anka", "Bakura", "Birnin Magaji", "Bukkuyum", "Gummi", "Gusau", "Kaura Namoda", "Maru", "Shinkafi", "Talata Mafara",
        "Tsafe", "Zamfara North"
    ]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
    <link rel="stylesheet" href="style.css">  
<style>
    .search-section {
    margin: 20px auto;
    padding: 15px;
    max-width: 500px;
    background-color: #f9f9f9;
    border-radius: 6px;
    border: 1px solid #ddd;
}

.search-section form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.search-section label {
    font-size: 0.95rem;
    color: #333;
}

.search-section select,
.search-section button {
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
}

.search-section select:focus,
.search-section button:focus {
    outline: none;
    border-color: purple;
}

.search-section button {
    background-color: purple;
    color: #fff;
    cursor: pointer;
}

.search-section button:hover {
    background-color: purple;
}

</style>
</head>
<body>

<!-- State and LGA Filter Form -->
<section class="search-section">
    <form action="search_by_select.php" method="GET">
        <label for="state">Select State:</label>
        <select id="state" name="state">
            <option value="">Select State</option>
        </select>

        <label for="lga">Select LGA:</label>
        <select id="lga" name="lga">
            <option value="">Select LGA</option>
        </select>
        
        <button type="submit">Filter</button>
    </form>
</section>

<!-- Property Listings Section -->
<section class="properties-listings">
    <center><h1>Property Listings</h1></center>

    <?php
    if ($result && $result->num_rows > 0) {
        echo '<div class="property-card-container">';  // Wrapper for all the cards

        // Loop through each result and display as a property card
        while ($row = $result->fetch_assoc()) {
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
                    <p class="price">$<?php echo number_format($price, 2); ?></p>
                    <p class="property-type"><?php echo ucfirst($property_type); ?></p>
                    <p class="bedrooms"><?php echo $bedrooms; ?> Bedrooms</p>
                    <a href="property_details.php?id=<?php echo $property_id; ?>" class="view-details">View Details</a>
                </div>
            </div>
            <?php
        }
        echo '</div>'; // Close the property card container
    } else {
        echo "<p>No properties found for the selected filters.</p>";  // Message when no properties are found
    }

    $conn->close();  // Close the database connection
    ?>
</section>

<!-- Back to Search Button -->
<button class="styled-button" onclick="window.location.href='index.php'">Back to Search</button>

<script>
// All Nigerian States with their Local Government Areas (LGAs)
const statesAndLGAs = <?php echo json_encode($statesAndLGAs); ?>;

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

<style>
    /* Your existing CSS for styling */
    .styled-button {
        background-color: purple;
        color: white;
        font-size: 16px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .styled-button:hover {
        background-color: #45a049;
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
        color: purple;
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
        background-color: purple;
        color: white;
        text-decoration: none;
        border-radius: 3px;
        text-align: center;
    }

    .property-card .property-details .view-details:hover {
        background-color: #0056b3;
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

</body>
</html>
