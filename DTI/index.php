<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="dti.png" type="image/x-icon">
    <title>DTI R2</title>

    <style>
        body {
            margin: 0;
            font-family: 'Gilroy', 'sans-serif';
            text-align: center;
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
            font-family: 'Gilroy', sans-serif;
            font-weight: bold
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


        @media (max-width: 768px) {
            .topbar {
                font-size: 16px;
                padding: 8px 0;
            }

            .logo-container img {
                height: 80px;
                margin: 0 10px;
            }

            .region,
            .subtext {
                font-size: 16px;
            }

            .btn {
                width: 150px;
                padding: 10px 30px;
                font-size: 14px;
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
        }

        .container {
            background: linear-gradient(130deg, #8aa3fcff, #f4f9d4ff, #ffd6d6ff);
            height: 102vh;
            margin-top: -20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        
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
        a.hyper {
    text-decoration: none;
    color: gray;
    font-size: 25px;
}

a.hyper:hover {
    text-decoration: underline;
    color: #4060d3ff;
    font-size: 26px;
    transition: ease-in-out;
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
        <a href="http://www.investincagayanprovince.com.ph/" target="_blank"  class="hyper">Invest in Cagayan Province</a>
    </div>
</body>

</html>