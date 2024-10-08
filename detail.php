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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contactNo = $_POST['contactno'];
    $neededDate = $_POST['needed-date'];
    $days = $_POST['days'];
    $vehicleType = $_POST['vehicle-type'];
    $vehicleId = $_POST['vehicle-id']; // Get vehicle ID

    // Prepare and bind to prevent SQL injection
    $stmtInsert = $conn->prepare("INSERT INTO booked (user_name, user_add, user_ph, date_needed, days, type) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtInsert->bind_param("ssssss", $name, $address, $contactNo, $neededDate, $days, $vehicleType);

    if ($stmtInsert->execute()) {
        // Update the vehicle status to 'unavailable'
        $stmtUpdate = $conn->prepare("UPDATE form SET status = 'unavailable' WHERE id = ?");
        $stmtUpdate->bind_param("s", $vehicleId);

        if ($stmtUpdate->execute()) {
            echo "<script>
                    alert('Booking successful! Vehicle is now unavailable.');
                    window.location.href = 'home.html'; // Redirect to a different page, if needed
                  </script>";
        } else {
            echo "<script>alert('Error updating vehicle status: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Error: " . $stmtInsert->error . "');</script>";
    }

    $stmtInsert->close();
    $stmtUpdate->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Vehicle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .booking-form {
            width: 100%;
            max-width: 500px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"], input[type="date"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function validateForm() {
            const contactNo = document.getElementById("contactno").value;
            const days = document.getElementById("days").value;
            const neededDate = document.getElementById("needed-date").value;
            const today = new Date().toISOString().split('T')[0];

            if (contactNo.length < 10) {
                alert("Contact number must be at least 10 digits.");
                return false;
            }

            if (days < 1) {
                alert("Days must be at least 1.");
                return false;
            }

            if (neededDate < today) {
                alert("You cannot select a previous date.");
                return false;
            }

            // Confirmation prompt for the user
            return confirm("Are you sure you want to book?");
        }

        function bookVehicle(vehicleId) {
            if (confirm("Are you sure you want to book this vehicle?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "book_vehicle.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert("Successfully booked!");
                        window.location.href = "home.html"; // Redirect to home.html
                    } else {
                        alert("Error booking the vehicle.");
                    }
                };
                xhr.send("id=" + vehicleId);
            }
        }
    </script>
</head>
<body>
    <div class="booking-form">
        <h2>Book Your Vehicle</h2>
        <form action="" method="POST" onsubmit="return validateForm()">
            <input type="hidden" name="vehicle-type" value="<?php echo isset($_GET['type']) ? htmlspecialchars($_GET['type']) : ''; ?>">
            <input type="hidden" name="vehicle-id" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">

            <label for="name">Enter Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="contactno">Contact No:</label>
            <input type="text" id="contactno" name="contactno" required>

            <label for="needed-date">Date of Vehicle Needed:</label>
            <input type="date" id="needed-date" name="needed-date" required>

            <label for="days">For How Many Days:</label>
            <input type="number" id="days" name="days" min="1" required>

            <button type="button" onclick="bookVehicle('<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>')">Book Now</button>
        </form>
    </div>
</body>
</html>
