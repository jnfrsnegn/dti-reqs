<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('../conn.php');

$month = $_GET['month'] ?? '';
$status = $_GET['status'] ?? '';

$sql = "SELECT r.*, e.equipmentName, r.requestorName, r.emailAddress
        FROM requests r
        LEFT JOIN equipment e ON r.equipmentID = e.equipmentID
        WHERE 1"; 

$params = [];
$types = '';

if ($month) {
    $sql .= " AND DATE_FORMAT(r.dateRequested, '%Y-%m') = ?";
    $params[] = $month;
    $types .= 's';
}

if ($status) {
    $sql .= " AND r.status = ?";
    $params[] = $status;
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode(['data' => $data]);
exit();
