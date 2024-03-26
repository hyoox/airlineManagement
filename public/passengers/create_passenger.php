<!-- passengers/create_passenger.php -->
<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection
    require_once "../config/dbconnect.php";

    // Get form data
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO passengers (surname, name, address, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $surname, $name, $address, $phone);

    // Execute statement and check for success
    if ($stmt->execute()) {
        echo "New passenger added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Passenger</title>
    <link rel="stylesheet" href="../css/styles.css">
<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

<!-- DataTables CSS and JS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>

    <!-- You can include your stylesheet here -->
</head>
<body>
    <h2>Add New Passenger</h2>
    <form action="create_passenger.php" method="post">
        <label for="surname">Surname:</label>
        <input type="text" id="surname" name="surname" required><br><br>

        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address"></textarea><br><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
