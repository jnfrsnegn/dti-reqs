<?php
require('../conn.php');

$sql = "SELECT 
            r.transactionID,
            r.requestorName,
            r.emailAddress,
            GROUP_CONCAT(e.equipmentName SEPARATOR ', ') AS equipmentNames,
            GROUP_CONCAT(r.quantity SEPARATOR ', ') AS quantities,
            r.dateRequested,
            r.dateOfUse,
            r.dateOfReturn,
            r.purpose,
            r.status
        FROM requests r
        LEFT JOIN equipment e ON r.equipmentID = e.equipmentID
        GROUP BY r.transactionID
        ORDER BY r.dateRequested DESC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'transactionID' => $row['transactionID'],
        'requestorName' => $row['requestorName'],
        'emailAddress' => $row['emailAddress'],
        'equipmentName' => $row['equipmentNames'], // all equipment for this transaction
        'quantity' => $row['quantities'], // all quantities
        'dateRequested' => $row['dateRequested'],
        'dateOfUse' => $row['dateOfUse'],
        'dateOfReturn' => $row['dateOfReturn'],
        'purpose' => $row['purpose'],
        'status' => $row['status']
    ];
}

echo json_encode(['data' => $data]);
?>