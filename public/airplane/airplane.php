<?php
require_once "../../config/dbconnect.php";

$airplanes = [];
$query = "SELECT airplane_id, manufacturer, model, sernum FROM airplanes";
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $airplanes[] = $row;
    }
} else {
    echo "Error: " . $conn->error;  // Display errors if query fails
}
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
                <li><a href="airplane.php" class="active">Airplanes</a></li>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($airplanes as $airplane): ?>
                    <tr>
                        <td><?= htmlspecialchars($airplane['airplane_id']) ?></td>
                        <td><?= htmlspecialchars($airplane['manufacturer']) ?></td>
                        <td><?= htmlspecialchars($airplane['model']) ?></td>
                        <td><?= htmlspecialchars($airplane['sernum']) ?></td>
                        <td>
                            <button onclick="editAirplane(<?= htmlspecialchars(json_encode($airplane)) ?>)">Edit</button>
                        
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <aside id="form-container">
        <h1 id="form-title">Add New Airplane</h1>
        <form id="airplaneForm">
            <input type="hidden" id="airplane_id" name="airplane_id">
            <label for="manufacturer">Manufacturer:</label>
            <input type="text" id="manufacturer" name="manufacturer" required><br><br>
            <label for="model">Model:</label>
            <input type="text" id="model" name="model" required><br><br>
            <label for="sernum">Serial Number:</label>
            <input type="text" id="sernum" name="sernum" required><br><br>
            <button type="button" onclick="submitForm()">Submit</button>
            <button type="button" id="deleteButton" onclick="deleteAirplane($('#airplane_id').val())" style="display: none;">Delete</button>
        </form>
    </aside>
</div>

<script>
function submitForm() {
    const airplaneId = $('#airplane_id').val();
    const manufacturer = $('#manufacturer').val();
    const model = $('#model').val();
    const sernum = $('#sernum').val();
    const action = airplaneId ? 'update' : 'add';
    $.post('../../backend/api/airplanes/manage_airplanes.php?action=' + action, {
        airplane_id: airplaneId,
        manufacturer: manufacturer,
        model: model,
        sernum: sernum
    }, function(data) {
        alert(data);  // Display server response
        location.reload();  // Reload the page to update the table
    });
}

function editAirplane(airplane) {
    $('#airplane_id').val(airplane.airplane_id);
    $('#manufacturer').val(airplane.manufacturer);
    $('#model').val(airplane.model);
    $('#sernum').val(airplane.sernum);
    $('#form-title').text('Edit Airplane');
    $('#deleteButton').show();  // Show the delete button when editing
}

function deleteAirplane(airplaneId) {
    if (!airplaneId) return;
    if (!confirm("Are you sure you want to delete this airplane?")) return;
    $.post('../../backend/api/airplanes/manage_airplanes.php?action=delete', { airplane_id: airplaneId }, function(data) {
        alert(data);  // Display server response
        location.reload();  // Reload the page to update the table
    });
}

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
