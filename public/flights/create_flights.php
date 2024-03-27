<?php
// Include the database connection
require_once "../../config/dbconnect.php";

// Handle POST request to add a flight
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flightnum = $_POST['flightnum'];
    $origin = $_POST['origin'];
    $dest = $_POST['dest'];
    $date = $_POST['date'];
    $arr_time = $_POST['arr_time'];
    $dep_time = $_POST['dep_time'];

    // Prepare SQL and bind parameters
    $stmt = $conn->prepare("INSERT INTO flights (flightnum, origin, dest, date, arr_time, dep_time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $flightnum, $origin, $dest, $date, $arr_time, $dep_time);

    // Execute statement and check for success
    if ($stmt->execute()) {
        echo "<p>New flight added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    // Close statement and connection
    $stmt->close();
}

// Fetch flights for DataTable
$flights = [];
$stmt = $conn->prepare("SELECT flightnum, origin, dest, date, arr_time, dep_time FROM flights");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $flights[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Flights Management</title>
<link rel="stylesheet" href="../css/styles.css">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <aside id="sidebar">
        <nav>
            <ul>
            <li><a href="../passengers/create_passenger.php">Passengers</a></li>
                <li><a href="../flights/create_flights.php" class="active">Flights</a></li>
                <li><a href="../staff/create_staff.php" >Staff</a></li>
                <li><a href="../airplane/create_airplane.php" >Airplanes</a></li>
                <li><a href="../cities/create_city.php">Cities</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <section id="main-content">
        <h1>Flight List</h1>
        <table id="flightsTable" class="display">
            <thead>
                <tr>
                    <th>Flight Number</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Date</th>
                    <th>Arrival Time</th>
                    <th>Departure Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($flights as $flight): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($flight['flightnum']); ?></td>
                        <td><?php echo htmlspecialchars($flight['origin']); ?></td>
                        <td><?php echo htmlspecialchars($flight['dest']); ?></td>
                        <td><?php echo htmlspecialchars($flight['date']); ?></td>
                        <td><?php echo htmlspecialchars($flight['arr_time']); ?></td>
                        <td><?php echo htmlspecialchars($flight['dep_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Form Container -->
    <aside id="form-container">
        <h1>Add New Flight</h1>
        <form action="create_flight.php" method="post">
            <label for="flightnum">Flight Number:</label>
            <input type="text" id="flightnum" name="flightnum" required><br><br>

            <label for="origin">Origin:</label>
            <input type="text" id="origin" name="origin" required><br><br>

            <label for="dest">Destination:</label>
            <input type="text" id="dest" name="dest" required><br><br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required><br><br>

            <label for="arr_time">Arrival Time:</label>
            <input type="time" id="arr_time" name="arr_time" required><br><br>

            <label for="dep_time">Departure Time:</label>
            <input type="time" id="dep_time" name="dep_time" required><br><br>

            <input type="submit" value="Submit">
        </form>
    </aside>
</div>

<script>
    $(document).ready(function() {
        $('#flightsTable').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [5, 10, 15, 20],
            "pageLength": 5
        });
    });
</script>

</body>
</html>
