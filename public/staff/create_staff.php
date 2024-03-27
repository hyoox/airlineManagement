<?php
// Include the database connection
require_once "../../config/dbconnect.php";

// Handle POST request to add a staff member
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $salary = $_POST['salary'];

    // Prepare SQL and bind parameters
    $stmt = $conn->prepare("INSERT INTO staff (surname, name, address, phone, salary) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $surname, $name, $address, $phone, $salary);

    // Execute statement and check for success
    if ($stmt->execute()) {
        echo "<p>New staff member added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// Fetch staff for DataTable
$staff_members = [];
$stmt = $conn->prepare("SELECT surname, name, address, phone, salary FROM staff");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $staff_members[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Management</title>
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
                <li><a href="../flights/create_flights.php">Flights</a></li>
                <li><a href="../staff/create_staff.php" class="active">Staff</a></li>
                <li><a href="../airplane/create_airplane.php" >Airplanes</a></li>
                <li><a href="../cities/create_city.php">Cities</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <section id="main-content">
        <h1>Staff List</h1>
        <table id="staffTable" class="display">
            <thead>
                <tr>
                    <th>Surname</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Salary</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($staff_members as $staff): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($staff['surname']); ?></td>
                        <td><?php echo htmlspecialchars($staff['name']); ?></td>
                        <td><?php echo htmlspecialchars($staff['address']); ?></td>
                        <td><?php echo htmlspecialchars($staff['phone']); ?></td>
                        <td><?php echo htmlspecialchars($staff['salary']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Form Container -->
    <aside id="form-container">
        <h1>Add New Staff Member</h1>
        <form action="create_staff.php" method="post">
            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname" required><br><br>

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea><br><br>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required><br><br>

            <label for="salary">Salary:</label>
            <input type="number" id="salary" name="salary" step="0.01" required><br><br>

            <input type="submit" value="Submit">
            </form>
    </aside>
</div>

<script>
    $(document).ready(function() {
        $('#staffTable').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [5, 10, 15, 20],
            "pageLength": 5
        });
    });
</script>

</body>
</html>
