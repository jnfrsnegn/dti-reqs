<?php
require('../conn.php');
header('Content-Type: application/json');

$sql = "
    SELECT 
        r.requestID,
        r.requestorName,
        r.emailAddress,
        e.equipmentName,
        r.quantity,
        r.dateRequested,
        r.dateOfUse,
        r.dateOfReturn,
        r.status
    FROM requests r
    INNER JOIN equipment e ON r.equipmentID = e.equipmentID
    ORDER BY r.dateRequested DESC
";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);
$conn->close();
