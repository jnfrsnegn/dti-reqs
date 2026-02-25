<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="dti.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>DTI R2</title>
<style>
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        font-family: 'Gilroy', 'sans-serif';
        text-align: center;
        overflow-x: hidden; 
    }

    .container {
        background: linear-gradient(130deg, #8aa3fcff, #f4f9d4ff, #ffd6d6ff);
        min-height: 100vh; 
        margin-top: 0; 
        padding-bottom: 0; 
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center; 
    }

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

    .region, .subtext {
        font-size: 25px;
        color: black;
        font-family: 'Gilroy', sans-serif;
        font-weight: bold;
    }

    .btn {
        background-color: #1f3586;
        color: white;
        padding: 12px 40px;
        border-radius: 10px;
        border: none;
        font-size: 16px;
        cursor: pointer;
        display: block;
        margin: 10px auto;
        width: 170px;
        max-width: 90%;
        text-decoration: none;
        text-align: center;
    }

    .btn:hover {
        background-color: #162a63;
    }

    .role-container {
        width: 350px;
        margin: 25px auto;
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 15px 15px rgba(0,0,0,0.15);
        text-align: center;
    }

    .role {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
        color: black;
        font-family: 'Gilroy', 'sans-serif';
    }

    a.fa-facebook {
        text-decoration: none;
        color: gray;
        font-size: 25px;
    }

    a.fa-facebook:hover {
        color: #4060d3ff;
        transition: ease-in-out;
    }


    @media (max-width: 768px) {
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .container {
            min-height: 100vh;
            margin-top: 0;
            padding-bottom: 0;
            flex-direction: column;
            align-items: stretch; 
        }

        .logo-container img {
            height: 80px;
            margin: 0 10px;
        }

        .region, .subtext {
            font-size: 16px;
        }

        .btn {
            width: 150px;
            padding: 10px 30px;
            font-size: 14px;
        }

        .role-container {
            width: 90%; 
            margin: 20px auto;
            padding: 20px;
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

        .btn {
            width: 80%;
        }

        .container {
            min-height: 100vh;
            margin-top: 0;
            padding-bottom: 0;
        }

        .role-container {
            width: 95%;
            padding: 15px;
        }
    }
</style>
</head>

<body>
    <div class="container">
        <div class="logo-container">
            <img src="dti.png" alt="DTI Logo">
            <img src="bp.png" alt="BP Logo">
          
        </div>

        <div class="region">Region 2</div>
        <div class="subtext">Cagayan Provincial Office</div>
        <div class="role-container">
            <div class="role">Select Role:</div>
            <a href="admin/adminlogin.php" class="btn">Admin</a>
            <a href="staff/staffdashboard.php" class="btn">Staff</a>
        </div>
        <a href="https://www.facebook.com/DTIR2.Cagayan2023" target="_blank"   class="fa fa-facebook"> DTI R2 CAGAYAN</a>
    </div>
</body>

</html>
