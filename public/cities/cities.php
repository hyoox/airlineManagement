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

<?php
require_once '../../config/dbconnect.php';

function fetchAllCities() {
    global $conn;
    $stmt = $conn->prepare("SELECT city_id, name, code FROM cities");
    $stmt->execute();
    $result = $stmt->get_result();
    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }
    $stmt->close();
    $conn->close();
    return $cities;
}

$cities = fetchAllCities();
?>

<div class="wrapper">
    <aside id="sidebar">
        <nav>
            <ul>
                <li><a href="../passengers/create_passenger.php">Passengers</a></li>
                <li><a href="../flights/create_flights.php">Flights</a></li>
                <li><a href="../staff/create_staff.php">Staff</a></li>
                <li><a href="../airplane/create_airplane.php">Airplanes</a></li>
                <li><a href="cities.php" class="active">Cities</a></li>
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
                    <th>Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cities as $city): ?>
                    <tr>
                        <td><?= htmlspecialchars($city['city_id']) ?></td>
                        <td><?= htmlspecialchars($city['name']) ?></td>
                        <td><?= htmlspecialchars($city['code']) ?></td>
                        <td><button onclick="editCity(<?= htmlspecialchars(json_encode($city)) ?>)">Edit</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <aside id="form-container">
        <h1 id="form-title">Add New City</h1>
        <p id="message"></p>
        <form id="cityForm">
            <input type="hidden" id="city_id" name="city_id">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            <label for="code">Code:</label>
            <input type="text" id="code" name="code" required><br><br>
            <button type="button" onclick="submitForm()">Submit</button>
            <button type="button" id="deleteButton" onclick="deleteCity()" style="display: none;">Delete</button>
        </form>
    </aside>
</div>

<script>
let selectedCityId = null;

function editCity(city) {
    $('#city_id').val(city.city_id);
    $('#name').val(city.name);
    $('#code').val(city.code);
    $('#form-title').text('Edit City');
    $('#deleteButton').show();
}

function submitForm() {
    const cityId = $('#city_id').val();
    const cityName = $('#name').val();
    const cityCode = $('#code').val();
    const action = cityId ? 'update' : 'add';

    console.log("Submitting:", { city_id: cityId, name: cityName, code: cityCode }); // Debugging output

    $.post('../../backend/api/cities/manage_cities.php?action=' + action, {
        city_id: cityId,
        name: cityName,
        code: cityCode
    }, function(data) {
        alert("Response: " + data); // Alert to show what the server responded
        $('#message').text(data);
        if (!cityId) { // Assuming adding a new city doesn't provide an ID
            location.reload(); // Reload the page to update the table data
        }
    });
}


function deleteCity() {
    const cityId = $('#city_id').val();
    if (!cityId) return;
    $.post('../../backend/api/cities/manage_cities.php?action=delete', { city_id: cityId }, function(data) {
        $('#message').text(data);
        location.reload();  // Reload the page to update the table data
    });
}

$(document).ready(function() {
    $('#citiesTable').DataTable({
        "columnDefs": [{ "targets": 3, "orderable": false }]
    });
});
</script>

</body>
</html>

<!-- TODO: Change the code to not reload when something happens to datatable -->
<!-- function submitForm() {
    // ... existing code ...

    $.post('../../backend/api/cities/manage_cities.php?action=' + action, {
        city_id: cityId,
        name: cityName,
        code: cityCode
    }, function(data) {
        // ... existing code ...

        refreshTable();
    });
}

function deleteCity() {
    // ... existing code ...

    $.post('../../backend/api/cities/manage_cities.php?action=delete', { city_id: cityId }, function(data) {
        // ... existing code ...

        refreshTable();
    });
}

function refreshTable() {
    $.get('../../backend/api/cities/fetch_all_cities.php', function(data) {
        table.clear();
        table.rows.add(data); // assuming the data is an array of arrays, where each inner array represents a row
        table.draw();
    });
} -->
