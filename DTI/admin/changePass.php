<?php
session_start();
require('../conn.php');

if (!isset($_SESSION['admin'])) {
    header("Location: adminlogin.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = trim($_POST['current_password']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "New password and confirmation do not match.";
    } else {
        $admin_id = $_SESSION['admin'];

        $stmt = $conn->prepare("SELECT password FROM admin WHERE AdminID = ?");
        if (!$stmt) {
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("i", $admin_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $user = $result->fetch_assoc();
                $stored_password = $user['password'];

                if ($current_password === $stored_password) {
                    $update_stmt = $conn->prepare("UPDATE admin SET password = ? WHERE AdminID = ?");
                    if (!$update_stmt) {
                        $error = "Database error: " . $conn->error;
                    } else {
                        $update_stmt->bind_param("si", $new_password, $admin_id);
                        if ($update_stmt->execute() && $update_stmt->affected_rows > 0) {
                            $success = "Password changed successfully.";
                        } else {
                            $error = "Failed to update password: " . $conn->error;
                        }
                        $update_stmt->close();
                    }
                } else {
                    $error = "Current password is incorrect.";
                }
            } else {
                $error = "Admin not found.";
            }

            $stmt->close();
        }
    }

    $conn->close();

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo $success ?: $error;
        exit();
    }
}


?>

