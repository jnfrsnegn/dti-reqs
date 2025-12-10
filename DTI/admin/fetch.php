<?php
session_start();
require('../conn.php');

if (!isset($_SESSION['admin'])) {
    echo json_encode([]);
    exit();
}

$sql = "SELECT * FROM equipment";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);

$conn->close();
?>
