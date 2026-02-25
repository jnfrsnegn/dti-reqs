<?php
session_start();
require('../conn.php');

if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    echo "Unauthorized!";
    exit();
}

if (isset($_POST['id'])) {
    $equipmentID = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM equipment WHERE equipmentID=?");
    if (!$stmt) {
        http_response_code(500);
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param("i", $equipmentID);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Equipment deleted successfully!";
        } else {
            echo "No equipment found with this ID.";
        }
    } else {
        http_response_code(500);
        echo "Delete failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(400);
    echo "Invalid input";
}
?>
