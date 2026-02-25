<?php
require('../conn.php');

$month = $_GET['month'] ?? '';
$reportTitle = "All Reports";

if (!empty($month)) {
    $dateObj = DateTime::createFromFormat('Y-m', $month);
    if ($dateObj) {
        $reportTitle = "Reports for the Month of " . $dateObj->format('F Y');
    }
}
$status = $_GET['status'] ?? '';

$sql = "SELECT 
            r.requestorName, 
            r.emailAddress, 
            e.equipmentName, 
            r.quantity, 
            r.dateRequested, 
            r.dateOfUse, 
            r.dateOfReturn, 
            r.purpose, 
            r.status 
        FROM requests r
        LEFT JOIN equipment e 
            ON r.equipmentID = e.equipmentID
        WHERE 1";

if (!empty($month)) {
    $sql .= " AND DATE_FORMAT(r.dateRequested, '%Y-%m') = '$month'";
}
if (!empty($status)) {
    $sql .= " AND r.status = '$status'";
}

$result = $conn->query($sql);
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
            font-size: 12pt;
            margin: 0;
        }

        .doc-content {
            max-width: 1100px;
            margin: auto;
            padding: 20px;
            border: 1px solid #000;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .logo-wrapper {
            display: flex;
            gap: 5px;
        }

        .logo-wrapper img {
            width: 60px;
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
            font-size: 10pt;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        @media print {
            button { display: none; }

            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
    </style>
</head>

<body onload="window.print()">

<div class="doc-content">

    <div class="header">
        <div class="logo-wrapper">
            <img src="../dti.png" alt="DTI Logo">
            <img src="../bp.png" alt="Bagong Pilipinas Logo">
        </div>

        <div class="header-text">
            <div class="bold">DEPARTMENT OF TRADE AND INDUSTRY</div>
            <div>Cagayan Provincial Office</div>
            <div>Regional Government Center - Carig Sur, Tuguegarao City, Cagayan</div>
            <div class="bold" style="margin-top:4px;">
                ICT EQUIPMENT REQUESTS REPORT
            </div>
            <div style="margin-top:4px; font-size:11pt;">
    <?= htmlspecialchars($reportTitle) ?>
</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Requestor</th>
                <th>Email</th>
                <th>Equipment</th>
                <th>Qty</th>
                <th>Date Requested</th>
                <th>Date of Use</th>
                <th>Date of Return</th>
                <th>Purpose</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['requestorName']) ?></td>
                    <td><?= htmlspecialchars($row['emailAddress']) ?></td>
                    <td><?= htmlspecialchars($row['equipmentName']) ?></td>
                    <td class="center"><?= (int)$row['quantity'] ?></td>
                    <td><?= htmlspecialchars($row['dateRequested']) ?></td>
                    <td><?= htmlspecialchars($row['dateOfUse']) ?></td>
                    <td><?= htmlspecialchars($row['dateOfReturn']) ?></td>
                    <td><?= htmlspecialchars($row['purpose']) ?></td>
                    <td class="center"><?= htmlspecialchars($row['status']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="center">No records found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>

</body>
</html>