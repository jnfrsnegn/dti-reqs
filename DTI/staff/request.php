<?php
require('../conn.php');
header('Content-Type: application/json');

$equipmentIDs   = $_POST['equipmentID'] ?? [];
$quantity       = $_POST['quantity'] ?? [];
$requestorName = $_POST['requestorName'] ?? '';
$emailAddress  = $_POST['emailAddress'] ?? '';
$dateRequested = $_POST['dateRequested'] ?? '';
$dateOfUse     = $_POST['dateOfUse'] ?? '';
$dateOfReturn  = $_POST['dateOfReturn'] ?? '';
$purposes       = $_POST['purpose'] ?? [];

$success = true;

$stmt = $conn->prepare("
    INSERT INTO requests 
    (equipmentID, quantity, dateRequested, dateOfUse, dateOfReturn, requestorName, emailAddress, purpose, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
");

foreach ($equipmentIDs as $i => $eqID) {
    $qty = $quantity[$i] ?? 1;
     $purpose = $purposes[$i] ?? ''; 

    $stmt->bind_param(
        "iissssss",
        $eqID,
        $qty,
        $dateRequested,
        $dateOfUse,
        $dateOfReturn,
        $requestorName,
        $emailAddress,
        $purpose
    );

    if (!$stmt->execute()) {
        $success = false;
        break;
    }
}

echo json_encode([
    'success' => $success
]);
