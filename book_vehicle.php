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

// Get the vehicle ID from the AJAX request
if (isset($_POST['id'])) {
    $vehicleId = $_POST['id'];
    $sql = "UPDATE form SET status='unavailable' WHERE id='$vehicleId'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }
}
$conn->close();
?>
