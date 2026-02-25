<?php
require('../conn.php');

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode([]);
    exit;
}

$requestID = intval($_GET['id']);

// Fetch all equipment items associated with this request
$sql = "SELECT r.id AS requestItemID, e.equipmentName, r.quantity
        FROM requests_items r
        JOIN equipment e ON r.equipmentID = e.equipmentID
        WHERE r.requestID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $requestID);
$stmt->execute();
$result = $stmt->get_result();

$items = [];

while ($row = $result->fetch_assoc()) {
    $items[] = [
        "id" => $row['requestItemID'],      
        "equipmentName" => $row['equipmentName'],
        "quantity" => $row['quantity']
    ];
}

echo json_encode($items);
?>  