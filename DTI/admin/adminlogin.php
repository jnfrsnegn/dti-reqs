<?php
session_start();
require('../conn.php');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT AdminID, username, password FROM admin WHERE username = ? LIMIT 1");
    if (!$stmt) {
        $error = "Database error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $stored_password = $user['password'];

            if (empty($stored_password)) {
                $default_password = "admin123";
                if ($password === $default_password) {
                    $update = $conn->prepare("UPDATE admin SET password = ? WHERE AdminID = ?");
                    $update->bind_param("si", $password, $user['AdminID']);
                    if ($update->execute() && $update->affected_rows > 0) {
                        $_SESSION['admin'] = $user['AdminID'];
                        $_SESSION['username'] = $user['username'];
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $error = "Failed to update password: " . $conn->error;
                    }
                    $update->close();
                } else {
                    $error = "Invalid password.";
                }
            } else {
                if ($password === $stored_password) {
                    $_SESSION['admin'] = $user['AdminID'];
                    $_SESSION['username'] = $user['username'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $error = "Invalid password.";
                }
            }
        } else {
            $error = "Username not found.";
        }

        $stmt->close();
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../dti.png" type="image/x-icon">
    <title>DTI R2 </title>
    <style>
        body {
            margin: 0;
            text-align: center;
            font-family: 'Gilroy', 'sans-serif';
        }

        /*.topbar {
    width: 100%;
    margin: 0;
    padding: 16px 0;
    background: linear-gradient(80deg, #1f3586, #f1d600);
    border-bottom: 5px solid #f1d600;
    font-family: "Poppins", sans-serif;
    font-weight: 700;
    font-size: 22px;
    color: white;
    text-align: center;
    text-shadow: 0px 2px 4px rgba(0,0,0,0.4);
}*/



        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .logo-container img {
            height: 110px;
            margin: 10px;
        }

        .region,
        .subtext {
            font-size: 25px;
            color: black;
           font-family: 'Gilroy',sans-serif;
           font-weight:bold
        }

        .card {
            width: 350px;
            margin: 25px auto;
            background: white;
            padding: 25px;
            border-radius: 15px;
             box-shadow: 0 15px 15px rgba(0,0,0,0.15);
            text-align: left;
            font-family: 'Gilroy', 'sans-serif';    
        }

        label {
            font-size: 15px;
             font-family:'Gilroy','sans-serif';
        }

        input[type="text"],
        input[type="password"] {
            width: 95%;
            padding: 12px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 20px;
            outline: none;
        }

        .show-pass {
            margin-bottom: 15px;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: #1f3586;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-size: 16px;
        }

        .login-btn:hover {
            background: #162a63;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .logo-container img {
                height: 80px;
            }

            .region,
            .subtext {
                font-size: 16px;
            }

            .card {
                width: 90%;
            }
        }

        @media (max-width: 480px) {
            .logo-container {
                flex-direction: column;
            }

            .logo-container img {
                height: 70px;
                margin: 5px 0;
            }
        }

        .container {
            background: linear-gradient(130deg, #8aa3fcff, #f4f9d4ff, #ffd6d6ff);
            height: 102vh;
            margin-top: -20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @keyframes gradient-animation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="logo-container">
            <img src="../dti.png" alt="DTI Logo">
            <img src="../bp.png" alt="Region 2 Logo">
        </div>
        <div class="region">Region 2</div>
        <div class="subtext">Cagayan Provincial Office</div>

        <form method="POST">
            <div class="card">
                <?php if ($error !== ''): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <div class="admin" style="text-align:center; font-family: 'Gilroy', sans-serif; font-size:20px;">
                    Admin Login
                </div>

                <label>Username</label>
                <input type="text" name="username" required>

                <label>Password</label>
                <input type="password" id="password" name="password" required>

                <div class="show-pass">
                    <input type="checkbox" onclick="togglePassword()"> Show Password
                </div>

                <button type="submit" class="login-btn">Login</button>
            </div>
        </form>

        <script>
            function togglePassword() {
                const pass = document.getElementById("password");
                pass.type = pass.type === "password" ? "text" : "password";
            }
        </script>
    </div>
</body>

</html>