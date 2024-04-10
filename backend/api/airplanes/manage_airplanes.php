<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../../config/dbconnect.php'; // Ensure this path is correct

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'add':
        echo addAirplane($_POST['manufacturer'], $_POST['model'], $_POST['sernum']);
        break;
    case 'update':
        echo updateAirplane($_POST['airplane_id'], $_POST['manufacturer'], $_POST['model'], $_POST['sernum']);
        break;
    case 'delete':
        echo deleteAirplane($_POST['airplane_id']);
        break;
    case 'read':
        echo json_encode(getAllAirplanes());
        break;
    default:
        echo "No valid action specified";
}

function getAllAirplanes() {
    global $conn;
    $query = "SELECT airplane_id, manufacturer, model, sernum FROM airplanes";
    $result = $conn->query($query);
    $airplanes = [];
    while ($row = $result->fetch_assoc()) {
        $airplanes[] = $row;
    }
    return $airplanes;
}

function addAirplane($manufacturer, $model, $sernum) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO airplanes (manufacturer, model, sernum) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $manufacturer, $model, $sernum);
    if ($stmt->execute()) {
        return "New airplane added successfully!";
    } else {
        return "Error: " . $stmt->error;
    }
}

function updateAirplane($airplane_id, $manufacturer, $model, $sernum) {
    global $conn;
    $stmt = $conn->prepare("UPDATE airplanes SET manufacturer = ?, model = ?, sernum = ? WHERE airplane_id = ?");
    $stmt->bind_param("sssi", $manufacturer, $model, $sernum, $airplane_id);
    if ($stmt->execute()) {
        return "Airplane updated successfully!";
    } else {
        return "Error: " . $stmt->error;
    }
}

function deleteAirplane($airplane_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM airplanes WHERE airplane_id = ?");
    $stmt->bind_param("i", $airplane_id);
    if ($stmt->execute()) {
        return "Airplane deleted successfully!";
    } else {
        return "Error: " . $stmt->error;
    }
}
?>
