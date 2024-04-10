<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../../config/dbconnect.php"; // Adjust this path as necessary

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        echo addCity($_POST['name'], $_POST['code']);
        break;
    case 'update':
        echo updateCity($_POST['city_id'], $_POST['name'], $_POST['code']);
        break;
    case 'delete':
        echo deleteCity($_POST['city_id']);
        break;
    case 'read':
        echo json_encode(getAllCities());
        break;
    default:
        http_response_code(400);
        echo "Bad Request: action parameter is missing or invalid.";
}

function getAllCities() {
    global $conn;
    $stmt = $conn->prepare("SELECT city_id, name, code FROM cities");
    $stmt->execute();
    $result = $stmt->get_result();
    $cities = [];
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row;
    }
    $stmt->close();
    return $cities;
}

function addCity($name, $code) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO cities (name, code) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $code);
    if ($stmt->execute()) {
        $stmt->close();
        return "New city added successfully!";
    } else {
        $error = $stmt->error;
        $stmt->close();
        return "Error: " . $error;
    }
}

function updateCity($city_id, $name, $code) {
    global $conn;
    $stmt = $conn->prepare("UPDATE cities SET name = ?, code = ? WHERE city_id = ?");
    $stmt->bind_param("ssi", $name, $code, $city_id);
    if ($stmt->execute()) {
        $stmt->close();
        return "City updated successfully!";
    } else {
        $error = $stmt->error;
        $stmt->close();
        return "Error: " . $error;
    }
}

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

function deleteCity($city_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM cities WHERE city_id = ?");
    $stmt->bind_param("i", $city_id);
    if ($stmt->execute()) {
        $stmt->close();
        return "City deleted successfully!";
    } else {
        $error = $stmt->error;
        $stmt->close();
        return "Error: " . $error;
    }
}
?>