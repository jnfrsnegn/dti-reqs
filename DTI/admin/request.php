<?php
require('../conn.php');

$result = $conn->query("SELECT * FROM requests ORDER BY requestID DESC");
$data = [];

while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode(['data' => $data]);
?>
