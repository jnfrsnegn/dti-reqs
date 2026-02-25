<?php
session_start();
require('../conn.php');

if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    exit("Unauthorized");
}

if (isset($_POST['items']) && is_array($_POST['items']) && isset($_POST['action'])) {
    $items = $_POST['items'];
    $action = $_POST['action'];

    if (!in_array($action, ['Approved', 'Denied'])) {
        exit("Invalid action");
    }

    $successCount = 0;

    // Prepare statements outside the loop
    $getInfo = $conn->prepare("
        SELECT r.equipmentID, r.quantity, e.quantity AS equipQty
        FROM requests r
        JOIN equipment e ON r.equipmentID = e.equipmentID
        WHERE r.requestID = ?
    ");

    $updateEquip = $conn->prepare("
        UPDATE equipment
        SET quantity = ?, status = ?
        WHERE equipmentID = ?
    ");

    $updateRequest = $conn->prepare("
        UPDATE requests SET status=? WHERE requestID=?
    ");

    foreach ($items as $requestID) {
        $requestID = (int)$requestID;

        // Execute getInfo
        $getInfo->bind_param("i", $requestID);
        $getInfo->execute();
        $getInfo->store_result(); // important to prevent sync issues
        $getInfo->bind_result($equipmentID, $requestedQty, $currentQty);

        if ($getInfo->fetch()) {
            if ($action === 'Approved') {
                if ($requestedQty > $currentQty) {
                    continue; // skip if not enough stock
                }
                $newQty = $currentQty - $requestedQty;
                $newStatus = ($newQty == 0) ? 'Not Available' : 'Available';

                $updateEquip->bind_param("isi", $newQty, $newStatus, $equipmentID);
                $updateEquip->execute();
            }

            $updateRequest->bind_param("si", $action, $requestID);
            $updateRequest->execute();

            $successCount++;
        }
        $getInfo->free_result(); // free before next iteration
    }

    $getInfo->close();
    $updateEquip->close();
    $updateRequest->close();

    echo "$successCount item(s) have been $action";
    exit;
}

// Single request (legacy)
if (isset($_POST['id'], $_POST['action'])) {
    $requestID = (int) $_POST['id'];
    $action = $_POST['action'];

    if (!in_array($action, ['Approved', 'Denied'])) {
        exit("Invalid action");
    }

    $getInfo = $conn->prepare("
        SELECT r.equipmentID, r.quantity, e.quantity AS equipQty
        FROM requests r
        JOIN equipment e ON r.equipmentID = e.equipmentID
        WHERE r.requestID = ?
    ");
    $getInfo->bind_param("i", $requestID);
    $getInfo->execute();
    $getInfo->bind_result($equipmentID, $requestedQty, $currentQty);
    $getInfo->fetch();
    $getInfo->close();

    if ($action === 'Approved') {
        if ($requestedQty > $currentQty) {
            http_response_code(400);
            exit("Cannot approve: Requested quantity exceeds available stock!");
        }

        $newQty = $currentQty - $requestedQty;
        $newStatus = ($newQty == 0) ? 'Not Available' : 'Available';

        $updateEquip = $conn->prepare("
            UPDATE equipment 
            SET quantity = ?, status = ?
            WHERE equipmentID = ?
        ");
        $updateEquip->bind_param("isi", $newQty, $newStatus, $equipmentID);
        $updateEquip->execute();
        $updateEquip->close();
    }

    $stmt = $conn->prepare("UPDATE requests SET status=? WHERE requestID=?");
    $stmt->bind_param("si", $action, $requestID);
    $stmt->execute();
    $stmt->close();

    echo "Request has been $action";
    exit;
}

exit("Invalid request");
?>