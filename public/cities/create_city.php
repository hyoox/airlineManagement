<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "../../config/dbconnect.php"; // Adjusted for correct path

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    $stmt = $conn->prepare("INSERT INTO cities (name) VALUES (?)");
    $stmt->bind_param("s", $name);

    if ($stmt->execute()) {
        $message = "New city added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$cities = [];
$stmt = $conn->prepare("SELECT city_id, name FROM cities"); // Corrected column name from 'id' to 'city_id'
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $cities[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cities Management</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
</head>
<body>

<div class="wrapper">
    <aside id="sidebar">
        <nav>
            <ul>
                <li><a href="../passengers/create_passenger.php">Passengers</a></li>
                <li><a href="../flights/create_flight.php">Flights</a></li>
                <li><a href="../staff/create_staff.php">Staff</a></li>
                <li><a href="../airplane/create_airplane.php">Airplanes</a></li>
                <li><a href="../cities/create_city.php" class="active">Cities</a></li> <!-- Corrected the link -->
            </ul>
        </nav>
    </aside>

    <section id="main-content">
        <h1>City List</h1>
        <table id="citiesTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cities as $city): ?>
                    <tr>
                        <td><?= htmlspecialchars($city['city_id']) ?></td>
                        <td><?= htmlspecialchars($city['name']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <aside id="form-container">
        <h1>Add New City</h1>
        <?php if (!empty($message)): ?>
            <p><?= $message ?></p>
        <?php endif; ?>
        <form action="create_city.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <input type="submit" value="Submit">
        </form>
    </aside>
</div>

<script>
$(document).ready(function() {
    $('#citiesTable').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [5, 10, 15, 20],
        "pageLength": 5
    });
});
</script>

</body>
</html>
