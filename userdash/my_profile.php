<?php
// Database connection
include('../db_connection.php');

// Initialize profile status and other variables
$profile_complete = false;
$error = '';
$success = '';

// Check if user is logged in and retrieve their profile data
session_start();
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General reset for margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f0f0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 20px;
}

/* Profile container */
.container {
    background-color: #fff;
    width: 100%;
    max-width: 900px;  /* Max width for larger screens */
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    margin-bottom: 20px;
}

/* Header styling */
h1, h2 {
    font-size: 2rem;
    color: #3a3a3a;
    text-align: center;
    margin-bottom: 20px;
    font-weight: bold;
}

/* User profile status section */
.profile-status {
    font-size: 1.2rem;
    font-weight: bold;
    text-align: center;
    margin-top: 20px;
    margin-bottom: 20px;
}

.profile-status.complete {
    color: #2ecc71;  /* Green color for complete */
}

.profile-status.incomplete {
    color: #e74c3c;  /* Red color for incomplete */
}

/* Error and success messages */
div {
    text-align: center;
    margin-bottom: 15px;
}

div[style*="color: red"] {
    color: #e74c3c;
}

div[style*="color: green"] {
    color: #2ecc71;
}

/* Form container */
form {
    display: flex;
    flex-direction: column;
    gap: 0px;
}

/* Label styling */
label {
    font-size: 1rem;
    color: #333;
    text-align: left;
    margin-bottom: 8px;
}

/* Input and textarea fields */
input[type="text"],
input[type="email"],
input[type="password"],
input[type="tel"],
textarea,
select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus,
input[type="tel"]:focus,
textarea:focus,
select:focus {
    border-color: #6a5acd;  /* Purple color on focus */
    outline: none;
}

/* Button styling */
button {
    background-color: #6a5acd;  /* Purple color */
    color: white;
    padding: 12px 20px;
    font-size: 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #5a4bb0;
}

/* Update Email Button */
.update-email-btn {
    background-color: #f39c12;  /* Gold color */
    color: white;
    padding: 10px 20px;
    font-size: 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    margin-bottom: 20px;
}

.update-email-btn:hover {
    background-color: #e67e22;  /* Darker gold */
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        width: 98%;  /* Make it a little wider on mobile */
        padding: 25px;
    }

    h1, h2 {
        font-size: 1.5rem;
    }

    label {
        font-size: 0.9rem;
    }

    button {
        padding: 10px 18px;
        font-size: 1rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="tel"],
    textarea,
    select {
        font-size: 0.9rem;
    }
}

@media (max-width: 480px) {
    h1, h2 {
        font-size: 1.2rem;
    }

    .container {
        width: 95%;
        padding: 10px;
    }

    button {
        padding: 8px 15px;
        font-size: 0.9rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="tel"],
    textarea,
    select {
        font-size: 0.85rem;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <!-- Update Email Button -->
        <a href="update_email.php">
            <button class="update-email-btn">Update Email</button>
        </a>

        <h1>Welcome, <?= $name ?>!</h1>

        <!-- Display error message -->
        <?php if ($error): ?>
            <div style="color: red;"><?= $error ?></div>
        <?php endif; ?>

        <!-- Display success message -->
        <?php if ($success): ?>
            <div style="color: green;"><?= $success ?></div>
        <?php endif; ?>

        <!-- Profile Status -->
        <h2 class="profile-status <?= $profile_complete ? 'complete' : 'incomplete' ?>">
            Profile Status: <?= $profile_complete ? "Complete" : "Incomplete" ?>
        </h2>

        <form action="my_profile.php" method="POST">
            <label for="alt_phone_number">Alternative Phone Number:</label>
            <input type="text" name="alt_phone_number" value="<?= $alt_phone_number ?>" required><br>

            <label for="gender">Gender:</label>
            <select name="gender">
                <option value="Male" <?= $gender == 'Male' ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= $gender == 'Female' ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= $gender == 'Other' ? 'selected' : '' ?>>Other</option>
            </select><br>

            <label for="home_address">Home Address:</label>
            <textarea name="home_address" required><?= $home_address ?></textarea><br>

            <label for="residential_address">Residential Address:</label>
            <textarea name="residential_address" required><?= $residential_address ?></textarea><br>

            <button type="submit">Update Profile</button>
        </form>

    </div>
</body>
</html>
