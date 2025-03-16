<?php
// Include your database connection file (this assumes $db_connection is already set up)
require_once '../db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the form data
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $birth_date = $_POST['birth_date'];
    $gender = $_POST['gender'];
    $address_line1 = $_POST['address_line1'];
    $address_line2 = $_POST['address_line2'];
    $id_type = $_POST['id_type'];
    $id_number = $_POST['id_number'];
    $id_front_image = $_FILES['id_front']['name']; // Assuming a file input with name 'id_front'
    $id_back_image = $_FILES['id_back']['name'];   // Assuming a file input with name 'id_back'
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Move uploaded files to a specific folder (ensure folder exists and has write permissions)
    move_uploaded_file($_FILES['id_front']['tmp_name'], 'uploads/' . $id_front_image);
    move_uploaded_file($_FILES['id_back']['tmp_name'], 'uploads/' . $id_back_image);

    // Prepare the SQL query
    $sql = "INSERT INTO agent (full_name, email, phone_number, birth_date, gender, address_line1, address_line2, id_type, id_number, id_front_image, id_back_image, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    if ($stmt = $db_connection->prepare($sql)) {
        $stmt->bind_param("ssssssssssss", $full_name, $email, $phone_number, $birth_date, $gender, $address_line1, $address_line2, $id_type, $id_number, $id_front_image, $id_back_image, $password);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the same form with a success message
            header("Location: register.php?success=true");
            exit(); // Ensure no further code is executed
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: " . $db_connection->error;
    }
}
?>

<!DOCTYPE html>
<!---Coding By CodingLab | www.codinglabweb.com--->
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!--<title>Registration Form in HTML CSS</title>-->
    <!---Custom CSS File--->
  <style>
    /* Import Google font - Poppins */
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: "Poppins", sans-serif;
}
body {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  background: rgb(130, 106, 251);
}
.container {
  position: relative;
  max-width: 700px;
  width: 100%;
  background: #fff;
  padding: 25px;
  border-radius: 8px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}
.container header {
  font-size: 1.5rem;
  color: #333;
  font-weight: 500;
  text-align: center;
}
.container .form {
  margin-top: 30px;
}
.form .input-box {
  width: 100%;
  margin-top: 20px;
}
.input-box label {
  color: #333;
}
.form :where(.input-box input, .select-box) {
  position: relative;
  height: 50px;
  width: 100%;
  outline: none;
  font-size: 1rem;
  color: #707070;
  margin-top: 8px;
  border: 1px solid #ddd;
  border-radius: 6px;
  padding: 0 15px;
}
.input-box input:focus {
  box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
}
.form .column {
  display: flex;
  column-gap: 15px;
}
.form .gender-box {
  margin-top: 20px;
}
.gender-box h3 {
  color: #333;
  font-size: 1rem;
  font-weight: 400;
  margin-bottom: 8px;
}
.form :where(.gender-option, .gender) {
  display: flex;
  align-items: center;
  column-gap: 50px;
  flex-wrap: wrap;
}
.form .gender {
  column-gap: 5px;
}
.gender input {
  accent-color: rgb(130, 106, 251);
}
.form :where(.gender input, .gender label) {
  cursor: pointer;
}
.gender label {
  color: #707070;
}
.address :where(input, .select-box) {
  margin-top: 15px;
}
.select-box select {
  height: 100%;
  width: 100%;
  outline: none;
  border: none;
  color: #707070;
  font-size: 1rem;
}
.form button {
  height: 55px;
  width: 100%;
  color: #fff;
  font-size: 1rem;
  font-weight: 400;
  margin-top: 30px;
  border: none;
  cursor: pointer;
  transition: all 0.2s ease;
  background: rgb(130, 106, 251);
}
.form button:hover {
  background: rgb(88, 56, 250);
}
/*Responsive*/
@media screen and (max-width: 500px) {
  .form .column {
    flex-wrap: wrap;
  }
  .form :where(.gender-option, .gender) {
    row-gap: 15px;
  }
}
  </style>
  </head>
  <body>
    <section class="container">
      <header>Registration Form</header>
      <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Registration Form</title>
    <style>
        /* Add your existing styles here */
        /* ... */
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
 
 

        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            <div class="success-message">
                Your registration has been sent. Please wait for admin approval.
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST" enctype="multipart/form-data" class="form">
            <div class="input-box">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Enter full name" required />
            </div>
            <div class="input-box">
                <label>Email Address</label>
                <input type="text" name="email" placeholder="Enter email address" required />
            </div>
            <div class="column">
                <div class="input-box">
                    <label>Phone Number</label>
                    <input type="number" name="phone_number" placeholder="Enter phone number" required />
                </div>
                <div class="input-box">
                    <label>Birth Date</label>
                    <input type="date" name="birth_date" placeholder="Enter birth date" required />
                </div>
            </div>
            <div class="gender-box">
                <h3>Gender</h3>
                <div class="gender-option">
                    <div class="gender">
                        <input type="radio" name="gender" value="male" checked />
                        <label for="check-male">male</label>
                    </div>
                    <div class="gender">
                        <input type="radio" name="gender" value="female" />
                        <label for="check-female">Female</label>
                    </div>
                    <div class="gender">
                        <input type="radio" name="gender" value="prefer not to say" />
                        <label for="check-other">prefer not to say</label>
                    </div>
                </div>
            </div>
            <div class="input-box address">
                <label>Address</label>
                <input type="text" name="address_line1" placeholder="Enter street address" required />
                <input type="text" name="address_line2" placeholder="Enter street address line 2" required />
                <div class="column">
                    <div class="select-box">
                        <select name="id_type" required>
                            <option hidden>Type of Id</option>
                            <option>NIN</option>
                            <option>Passport</option>
                            <option>Drivers Licence</option>
                            <option>Voter's card</option>
                        </select>
                    </div>
                    <input type="number" name="id_number" placeholder="ID Number" required />
                </div> <br>
                <label for="">Front of Id</label>
                <input type="file" name="id_front" required />
                <br><br>
                <label for="">Back Of ID</label>
                <input type="file" name="id_back" required />
                <input type="password" name="password" placeholder="Enter Your password" required />
            </div>
            <br>
            <label for="">I agree with all <a href="terms.php">terms</a></label>
            <input type="checkbox" required />
            <button type="submit">Submit</button>

         <p>Already Have An Account?  <a href="login.php">Login</a></p>
        </form>
    </section>
</body>
</html>

