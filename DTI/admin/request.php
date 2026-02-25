<?php
require('../conn.php');

$sql = "SELECT r.requestID, r.requestorName, r.emailAddress, r.quantity,
               r.dateRequested, r.dateOfUse, r.dateOfReturn, r.purpose, r.status,
               e.equipmentName
        FROM requests r
        JOIN equipment e ON r.equipmentID = e.equipmentID
        ORDER BY r.requestID DESC";

$result = $conn->query($sql);
$data = [];

while($row = $result->fetch_assoc()) {
    $purpose = $row['purpose'];
    if (is_array($purpose)) {
        $purpose = implode(' | ', $purpose);
    }

    $data[] = [
        "requestID"     => $row['requestID'],
        "requestorName" => $row['requestorName'],
        "emailAddress"  => $row['emailAddress'],
        "equipmentName" => $row['equipmentName'], 
        "quantity"      => $row['quantity'],
        "dateRequested" => $row['dateRequested'],
        "dateOfUse"     => $row['dateOfUse'],
        "dateOfReturn"  => $row['dateOfReturn'],
        "purpose"       => $row['purpose'],
        "status"        => $row['status']
    ];
}

echo json_encode(['data' => $data]);
