<?php
require('../conn.php');

$sql = "SELECT requestID, equipmentID, equipmentName, requestorName, dateRequested, dateOfUse, purpose, status FROM requests ORDER BY dateRequested DESC";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode(['data' => $data]);

$conn->close();
?>