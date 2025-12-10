<?php
require('../conn.php');

if (isset($_POST['equipmentID'], $_POST['equipmentName'], $_POST['status'], $_POST['quantity'])) {

    $equipmentID = $_POST['equipmentID'];
    $equipmentName = $_POST['equipmentName'];
    $status = $_POST['status'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("UPDATE equipment SET equipmentName=?, Status=?, quantity=? WHERE equipmentID=?");
    $stmt->bind_param("ssii", $equipmentName, $status, $quantity, $equipmentID);

    if ($stmt->execute()) {
        echo "Equipment updated successfully!";
    } else {
        http_response_code(500);
        echo "Update failed: " . $stmt->error;
    }
} else {
    http_response_code(400);
    echo "Invalid input";
}
