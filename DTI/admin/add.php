<?php
session_start();
require('../conn.php');

if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo "Unauthorized!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $equipmentName = trim($_POST['equipmentName']);
    $status = $_POST['status'];
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;

    if (empty($equipmentName)) {
        http_response_code(400);
        echo "Equipment name cannot be empty.";
        exit();
    }

    if ($quantity < 0) {
        http_response_code(400);
        echo "Quantity cannot be negative.";
        exit();
    }

    if ($status === "Not Available") {
        $quantity = 0;
    }

    $check = $conn->prepare(
        "SELECT equipmentID FROM equipment WHERE LOWER(equipmentName) = LOWER(?)"
    );
    $check->bind_param("s", $equipmentName);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        http_response_code(409);
        echo "Resource already exists!";
        $check->close();
        exit();
    }
    $check->close();

    $stmt = $conn->prepare(
        "INSERT INTO equipment (equipmentName, Status, quantity)
         VALUES (?, ?, ?)"
    );
    $stmt->bind_param("ssi", $equipmentName, $status, $quantity);

    if ($stmt->execute()) {
        echo "Resource added successfully!";
    } else {
        http_response_code(500);
        echo "Error adding resource.";
    }

    $stmt->close();
    $conn->close();
}
