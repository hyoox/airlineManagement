<?php
// Include the database connection
require_once "../../config/dbconnect.php";

// Handle POST request to add a passenger
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO passengers (surname, name, address, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $surname, $name, $address, $phone);

    if ($stmt->execute()) {
        echo "<p>New passenger added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airline Management System</title>
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
            <li><a href="../passengers/create_passenger.php" class="active">Passengers</a></li>
                <li><a href="../flights/create_flights.php" >Flights</a></li>
                <li><a href="../staff/create_staff.php" >Staff</a></li>
                <li><a href="../airplane/create_airplane.php" >Airplanes</a></li>
                <li><a href="../cities/create_city.php">Cities</a></li>
            
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <section id="main-content">
        <h1>Passenger List</h1>
        <table id="passengersTable" class="display">
            <thead>
                <tr>
                    <th>Surname</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT surname, name, address, phone FROM passengers");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['surname']) . "</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['address']) . "</td>
                                <td>" . htmlspecialchars($row['phone']) . "</td>
                            </tr>";
                    }
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </section>

    <!-- Form Container -->
    <aside id="form-container">
        <h1>Add New Passenger</h1>
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
    </aside>
</div>

<script>
    $(document).ready(function() {
        $('#passengersTable').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [5, 10, 15, 20],
            "pageLength": 5
        });
    });
</script>

</body>
</html>
