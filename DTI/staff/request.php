<?php
require('../conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $equipmentID = intval($_POST['equipmentID']);
    $dateRequested = $conn->real_escape_string($_POST['dateRequested']);
    $dateOfUse = $conn->real_escape_string($_POST['dateOfUse']);
    $requestorName = $conn->real_escape_string($_POST['requestorName']);
    $purpose = $conn->real_escape_string($_POST['purpose']);

   
    $check = $conn->query("SELECT equipmentName, quantity, Status FROM equipment WHERE equipmentID = $equipmentID LIMIT 1");
    if ($check->num_rows === 0) {
        http_response_code(400);
        echo "Equipment not found.";
        exit;
    }

    $row = $check->fetch_assoc();
    $equipmentName = $row['equipmentName']; 

    if ($row['Status'] !== 'Available' || $row['quantity'] <= 0) {
        http_response_code(400);
        echo "This equipment is not available.";
        exit;
    }

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO requests (equipmentID, equipmentName, requestorName, dateRequested, dateOfUse, purpose, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->bind_param("isssss", $equipmentID, $equipmentName, $requestorName, $dateRequested, $dateOfUse, $purpose);
        $stmt->execute();
        $stmt->close();

        $newQuantity = $row['quantity'] - 1;
        $newStatus = $newQuantity <= 0 ? 'Not Available' : 'Available';
        $update = $conn->prepare("UPDATE equipment SET quantity = ?, Status = ? WHERE equipmentID = ?");
        $update->bind_param("isi", $newQuantity, $newStatus, $equipmentID);
        $update->execute();
        $update->close();

        $conn->commit();
        echo "Your request for '$equipmentName' has been submitted!";
    } catch (Exception $e) {
        $conn->rollback();
        http_response_code(500);
        echo "Error submitting request: " . $e->getMessage();
    }

    $conn->close();
}
?>
