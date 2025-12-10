<?php
require('../conn.php');

if(isset($_POST['id'], $_POST['action'])){
    $id = intval($_POST['id']);
    $action = $_POST['action'] === 'Approved' ? 'Approved' : 'Denied';

    $stmt = $conn->prepare("UPDATE requests SET status=? WHERE requestID=?");
    $stmt->bind_param("si", $action, $id);

    if($stmt->execute()){
        echo "Request has been $action.";
    } else {
        http_response_code(500);
        echo "Failed to update request.";
    }

    $stmt->close();
    $conn->close();
}
?>
