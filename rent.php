<?php
// Database connection setup
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "rental"; // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicleType = $_POST['vehicle-type'];
    $vehicleName = $_POST['vehicle-name'];
    $vehicleImage = $_FILES['vehicle-image']['name']; // Get the uploaded image file name
    $vehiclePrice = $_POST['vehicle-price'];
    $seating = $_POST['seating'];
    $mileage = $_POST['mileage'];
    $ownerName = $_POST['owner-name'];
    $ownerAddress = $_POST['owner-address'];
    $ownerContact = $_POST['owner-contact'];
    $ownerEmail = $_POST['owner-email'];

    // Upload image file to the server
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($vehicleImage);

    if (move_uploaded_file($_FILES['vehicle-image']['tmp_name'], $targetFile)) {
        // Insert form data into the database and set 'status' to 'available'
        $sql = "INSERT INTO form (type, name, image, price, seatno, milage, owner_name, owner_add, own_ph, own_email, status)
                VALUES ('$vehicleType', '$vehicleName', '$vehicleImage', '$vehiclePrice', '$seating', '$mileage', '$ownerName', '$ownerAddress', '$ownerContact', '$ownerEmail', 'available')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>
                if (confirm('Do you want to submit the form?')) {
                    alert('Submission successful!');
                    window.location.href = 'home.html';
                }
            </script>";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "<script>alert('Error uploading file.');</script>";
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Rental Form</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 20px;
}

.form-container {
    background-color: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    max-width: 600px;
    margin: auto;
    animation: fadeIn 1.5s ease-in-out;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    display: block;
    margin-top: 15px;
    color: #555;
}

input[type="text"],
input[type="number"],
input[type="date"],
select,
input[type="file"],
input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-top: 8px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="date"]:focus,
select:focus,
input[type="file"]:focus,
input[type="email"]:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 8px rgba(76, 175, 80, 0.2);
    outline: none;
}

button.btn {
    background-color: #4CAF50;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
    margin-top: 20px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

button.btn:hover {
    background-color: #45a049;
    transform: scale(1.05);
}

button.btn:active {
    transform: scale(1);
}

.owner-details {
    display: none;
    margin-top: 20px;
    animation: slideDown 0.6s ease;
}

.gap {
    margin-top: 30px;
}

.gap-top {
    margin-top: 10px;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}


    </style>
    <script>
        function showOwnerDetails() {
            document.getElementById("owner-details").style.display = "block";
        }
    </script>
</head>
<body>

    <!-- Form for vehicle details -->
    <div class="form-container">
        <h2>Rent Your Vehicle</h2>
        <form action="rent.php" method="POST" enctype="multipart/form-data">
            <label for="vehicle-type">Select Vehicle Type:</label>
            <select id="vehicle-type" name="vehicle-type" required>
                <option value="car">Car</option>
                <option value="bike">Bike</option>
                <option value="auto">Auto-rickshaw</option>
                <option value="bus">Bus</option>
                <option value="scooter">Scooter</option>
                <option value="pickup">Pickup Truck</option>
                <option value="jeep">Jeep</option>
            </select>

            <label for="vehicle-name">Vehicle Model:</label>
            <input type="text" id="vehicle-name" name="vehicle-name" placeholder="Enter vehicle model" required>

            <label for="vehicle-image">Upload Vehicle Image:</label>
            <input type="file" id="vehicle-image" name="vehicle-image" accept="image/*" required>

            <label for="vehicle-price">Vehicle Price per Day:</label>
            <input type="number" id="vehicle-price" name="vehicle-price" placeholder="Enter price per day" min="0" required>

            <label for="seating">Seating Capacity:</label>
            <input type="number" id="seating" name="seating" placeholder="Enter seating capacity" min="1" max="20" required>

            <label for="mileage">Mileage per Km:</label>
            <input type="number" id="mileage" name="mileage" placeholder="Enter mileage" min="1" required>

            <button type="button" class="btn gap" onclick="showOwnerDetails()">Enter Your Details</button>

            <div id="owner-details" class="owner-details gap-top">
                <label for="owner-name">Name:</label>
                <input type="text" id="owner-name" name="owner-name" placeholder="Enter owner's name" required>

                <label for="owner-address">Address:</label>
                <input type="text" id="owner-address" name="owner-address" placeholder="Enter owner's address" required>

                <label for="owner-contact">Contact Info:</label>
                <input type="text" id="owner-contact" name="owner-contact" placeholder="Enter contact number" pattern="^[0-9]{10}$" title="Please enter a 10-digit phone number" required>

                <label for="owner-email">Email:</label>
                <input type="email" id="owner-email" name="owner-email" placeholder="Enter owner's email" required>
            </div>

            <button type="submit" class="btn">Submit</button>
        </form>
    </div>

</body>
</html>
