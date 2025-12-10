<?php
session_start();
require('../conn.php');

if (!isset($_SESSION['admin'])) {
    echo "Unauthorized!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $equipmentName = trim($_POST['equipmentName']);
    $status = $_POST['status'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : '';

    if (empty($equipmentName)) {
        echo "Equipment name cannot be empty.";
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO equipment (equipmentName, Status, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $equipmentName, $status, $quantity);

    if ($stmt->execute()) {
        echo "Equipment added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
