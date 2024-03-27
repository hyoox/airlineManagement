<?php
require_once "../../config/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $sernum = $_POST['sernum'];

    $stmt = $conn->prepare("INSERT INTO airplanes (manufacturer, model, sernum) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $manufacturer, $model, $sernum);

    if ($stmt->execute()) {
        $message = "New airplane added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

$airplanes = [];
$stmt = $conn->prepare("SELECT airplane_id, manufacturer, model, sernum FROM airplanes");
$stmt->execute();
$result = $stmt->get_result();

while($row = $result->fetch_assoc()) {
    $airplanes[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Airplanes Management</title>
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
                <li><a href="../airplane/create_airplane.php" class="active">Airplanes</a></li>
                <li><a href="../cities/create_city.php">Cities</a></li>
            </ul>
        </nav>
    </aside>

    <section id="main-content">
        <h1>Airplane List</h1>
        <table id="airplanesTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Manufacturer</th>
                    <th>Model</th>
                    <th>Serial Number</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($airplanes as $airplane): ?>
                    <tr>
                        <td><?= htmlspecialchars($airplane['airplane_id']) ?></td>
                        <td><?= htmlspecialchars($airplane['manufacturer']) ?></td>
                        <td><?= htmlspecialchars($airplane['model']) ?></td>
                        <td><?= htmlspecialchars($airplane['sernum']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <aside id="form-container">
        <h1>Add New Airplane</h1>
        <?php if (!empty($message)): ?>
            <p><?= $message ?></p>
        <?php endif; ?>
        <form action="create_airplane.php" method="post">
            <label for="manufacturer">Manufacturer:</label>
            <input type="text" id="manufacturer" name="manufacturer" required><br><br>
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" required><br><br>
            <label for="sernum">Serial Number:</label>
            <input type="text" id="sernum" name="sernum" required><br><br>
            <input type="submit" value="Submit">
        </form>
    </aside>
</div>

<script>
$(document).ready(function() {
    $('#airplanesTable').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [5, 10, 15, 20],
        "pageLength": 5
    });
});
</script>

</body>
</html>
