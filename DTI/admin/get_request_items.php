<?php
require('../conn.php');

if (!isset($_GET['id'])) {
    echo json_encode([]);
    exit;
}

$requestID = intval($_GET['id']);

// Fetch items for this specific request
$sql = "SELECT r.requestID, e.equipmentName, r.quantity
        FROM requests r
        JOIN equipment e ON r.equipmentID = e.equipmentID
        WHERE r.requestID = $requestID";

$result = $conn->query($sql);
$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = [
        "id" => $row['requestID'],       // could also be a unique item ID if you have one
        "equipmentName" => $row['equipmentName'],
        "quantity" => $row['quantity']
    ];
}

echo json_encode($items);
?>