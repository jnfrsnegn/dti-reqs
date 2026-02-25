<?php
require('../conn.php');

if (!isset($_GET['id'])) {
    die('Invalid request');
}

$id = intval($_GET['id']);

$sql = "SELECT r.*, e.equipmentName 
        FROM requests r
        LEFT JOIN equipment e ON r.equipmentID = e.equipmentID
        WHERE r.requestID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data || $data['status'] !== 'Approved') {
    die('Unauthorized');
}

$controlNo = date('Ymd', strtotime($data['dateRequested'])) . '-' . $data['requestID'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DTI R2</title>
    <link rel="icon" href="../dti.png">

    <style>
        body {
            font-family: Calibri, Arial, sans-serif;
            font-size: 11px;
            margin: 0;
        }

        .slip-container {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            padding: 10px;
        }

        .doc-content {
            width: 48%;
            border: 1px solid #000;
            padding: 15px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo-wrapper {
            display: flex;
            
        }

        .logo-wrapper img {
            width: 50px;
        }

        .header-text {
           flex: 0.9;
            text-align: center;
            line-height: 1.3;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .signature-space {
            height: 60px;
        }

        @media print {
            button {
                display: none;
            }

            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

<div class="slip-container">

<?php for ($i = 0; $i < 2; $i++): ?>

<div class="doc-content">

    <div class="header">
        <div class="logo-wrapper">
            <img src="../dti.png">
            <img src="../bp.png">
        </div>

        <div class="header-text">
            <div class="bold">DEPARTMENT OF TRADE AND INDUSTRY</div>
            <div>Cagayan Provincial Office</div>
            <div>Regional Government Center - Carig Sur, Tuguegarao City</div>
            <div class="bold" style="margin-top:3px;">
                PROPERTY PASS SLIP (ICT EQUIPMENT)
            </div>
        </div>
    </div>

  <table>
                <tr>
                    <td class="c1"><b>Control No:</b>
<?= date('Ymd', strtotime($data['dateRequested'])) . '-' . $data['requestID'] ?>
</td>
                    <td class="c1"><b>Date Requested:</b> <?= $data['dateRequested'] ?></td>
                    <td class="c1"><b>Date of Use:</b> <?= $data['dateOfUse'] ?></td>
                </tr>

                <tr>
                    <td class="c17" colspan="3">
                        This is to authorize <b><u><?= htmlspecialchars($data['requestorName']) ?></u></b> whose signature appears below,
                        to take/pull out of the premises of this office the following ICT Equipment/property/materials.
                    </td>
                </tr>

                <tr>
                    <td class="c1" rowspan="3">
                        <b>Description:</b><br>
                        <?= htmlspecialchars($data['quantity']) ?> - <?= htmlspecialchars($data['equipmentName']) ?>
                        <br><br><br><br><br><br><br><br><br><br><br><br>
                    </td>

                    <td class="c6" colspan="2">
                        <b>Purpose:</b><br>
                        <?= htmlspecialchars($data['purpose']) ?>
                    </td>
                </tr>

                <tr>
                    <td class="c6" colspan="2">
                        <b>Date to be returned:</b><br><br>
                        <?= $data['dateOfReturn'] ?? '' ?>
                    </td>
                </tr>

                <tr>
                    <td class="c6 center" colspan="2"><br>Signature of authorized person to take/pull out ICT equipment/property/materials out of the premises of this office. <br><br><br>
                        <b><u><?= htmlspecialchars($data['requestorName']) ?></u></b>
                        <br>
                        Authorized Person
                    </td>
                </tr>

                <tr>
                    <td class="c17 center" colspan="3">
                        <br>
                        <b>Approved by:</b><br><br>
                        <u>MARY ANN CORPUZ-DY</u><br>
                        Provincial Director
                    </td>
                </tr>

                <tr>
                    <td class="c1 center">
                        <b>Inspected and issued by:</b><br><br>
                        <u>JERARD R. QUE</u><br>
                        Designated IT Officer
                    </td>

                    <td class="c6 center" colspan="2">
                        <b>Status of Inspection (Remarks prior to pull out):</b><br><br>
                        _________________________________________________________<br>
                        _________________<br><br>
                        Guard on Duty
                    </td>
                </tr>

                <tr>
                    <td class="c17 center" colspan="3">
                        <b>Return Details</b><br>
                        (To be filled out when the ICT equipment/property/materials will be returned)
                    </td>
                </tr>

                <tr>
                    <td class="c17 center" colspan="3">
                        This is to certify that the above-mentioned ICT equipment/property/materials
                        were returned in good and working condition.
                    </td>
                </tr>

                <tr>
                    <td class="c17" colspan="3">
                        Other remarks: _______________________________________________________________________
                    </td>
                </tr>

                <tr>
                    <td class="c1"><b>Inspected by:</b></td>

                    <td class="c1 center"><br><br>
                        __________________ <br>
                        Guard on Duty
                    </td>

                    <td class="c1 center"><br><br>
                        <u>JERARD R. QUE</u><br>
                        Designated IT Officer
                    </td>
                </tr>

            </table>

</div>

<?php endfor; ?>

</div>

<div style="text-align:center; margin:15px;">
    <button onclick="window.print()">Print Slip</button>
</div>

</body>
</html>