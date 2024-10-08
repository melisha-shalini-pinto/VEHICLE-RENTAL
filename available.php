<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming 'vehicleType' is sent via GET
$vehicleType = $_GET['type'];
$sql = "SELECT * FROM form WHERE type = '$vehicleType'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Vehicles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .vehicle-container {
            display: flex;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s, box-shadow 0.3s;
            animation: slideIn 0.5s ease;
            flex-direction: column;
        }

        .vehicle-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .vehicle-container:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        }

        .vehicle-image {
            flex: 1;
            margin-right: 20px;
        }

        .vehicle-image img {
            width: 300px;
            height: 300px;
            border-radius: 10px;
        }

        .vehicle-details, .owner-details {
            flex: 2;
            margin-right: 20px;
        }

        .vehicle-details h3 {
            font-size: 1.5em;
            color: #333;
            margin-top: 0;
        }

        .book-button-container {
            display: flex;
            justify-content: center;
            margin-top: 15px;
        }

        .book-button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .book-button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .book-button:active {
            transform: scale(0.95);
        }

        .owner-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            box-shadow: inset 0 1px 5px rgba(0, 0, 0, 0.1);
        }

        .owner-details label {
            font-weight: bold;
            color: #555;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .unavailable-button {
            background-color: #ccc;
            color: #666;
            border: none;
            cursor: not-allowed;
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<h1>Available Vehicles</h1>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="vehicle-container">';
        echo '<div class="vehicle-row">';
        echo '<div class="vehicle-image">';
        echo '<img src="uploads/' . $row['image'] . '" alt="' . $row['name'] . ' Image">';
        echo '</div>';
        echo '<div class="vehicle-details">';
        echo '<h3>' . $row['name'] . '</h3>';
        echo '<p>Model: ' . $row['name'] . '</p>';
        echo '<p>Type: ' . $row['type'] . '</p>';
        echo '<p>Price per day: ' . $row['price'] . '</p>';
        echo '<p>Number of seats: ' . $row['seatno'] . '</p>';
        echo '<p>Mileage per Km: ' . $row['milage'] . '</p>';
        echo '</div>';
        echo '<div class="owner-details">';
        echo '<label>Owner Details:</label>';
        echo '<p>Name: ' . $row['owner_name'] . '</p>';
        echo '<p>Address: ' . $row['owner_add'] . '</p>';
        echo '<p>Phone: ' . $row['own_ph'] . '</p>';
        echo '<p>Email: ' . $row['own_email'] . '</p>';
        echo '</div>';
        echo '</div>'; // End of vehicle-row

        if ($row['status'] === 'available') {
            echo '<div class="book-button-container">';
            echo '<a href="detail.php?id=' . $row['id'] . '" class="book-button">Book</a>';
            echo '</div>';
        } else {
            echo '<div class="book-button-container">';
            echo '<button class="unavailable-button" disabled>Unavailable</button>';
            echo '</div>';
        }

        echo '</div>'; // End of vehicle-container
    }
} else {
    echo '<p>No vehicles available at the moment.</p>';
}
$conn->close();
?>

</body>
</html>
