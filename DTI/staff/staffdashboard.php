<?php
require('../conn.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>DTI R2 </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="icon" href="../dti.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Gilroy', 'sans-serif';
        }
        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .modal-content {
            background: linear-gradient(135deg, #1f3586, #f1d600);
            color: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            border-bottom: 2px solid rgba(255, 255, 255, 0.5);
        }

        .modal-title {
            font-weight: 700;
        }

        .modal-footer .btn-success {
            background-color: rgba(0, 123, 255, 0.8);
            border: none;
            transition: 0.3s;
        }

        .modal-footer .btn-success:hover {
            background-color: rgba(241, 214, 0, 0.9);
            color: #000;
        }

        .modal-body input {
            border-radius: 8px;
        }

        /*.topbar {
            width: 100%;
            margin: 0;
            padding: 13px 0;
            background: linear-gradient(80deg, #1f3586, #f1d600);
            border-bottom: 5px solid #f1d600;
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-size: 22px;
            color: white;
            text-align: center;
            text-shadow: 0px 2px 4px rgba(0, 0, 0, 0.4);
        }*/

        .custom-header {
            background: linear-gradient(135deg, #1f3586, #f1d600);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-top: 25px;
        }

        .btn-request {
            background-color: #0d6efd;
            color: white;
        }

        .btn-request:hover {
            background-color: #f1d600;
            color: #000;
        }
        .main{
            background: linear-gradient(130deg, #8aa3fcff, #f4f9d4ff, #ffd6d6ff);
            height: 102vh;
            margin-top: -20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container{
            margin-top:30px;
        }
    </style>
</head>

<body class="bg-light">
<div class="main">

    <div class="container py-4 custom-header">

        <div class="row mb-4">
            <div class="col-12">
                <table id="requestTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Requestor</th>
                            <th>Equipment Name</th>
                            <th>Date Requested</th>
                            <th>Date of Use</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="text-white fw-bold">Filter status:</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All</option>
                    <option value="Available">Available</option>
                    <option value="Not Available">Not Available</option>
                </select>
            </div>
        </div>

        <table id="equipTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Equipment Name</th>
                    <th>Status</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

    </div>

    <div class="modal fade" id="requestModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Equipment</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="requestForm">
                    <div class="modal-body">
                        <input type="hidden" name="equipmentID" id="reqEquipmentID">
                        <label>Equipment</label>
                        <input type="text" id="reqEquipmentName" class="form-control" readonly>
                        <label class="mt-2">Date Requested</label>
                        <input type="date" name="dateRequested" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        <label class="mt-2">Date of Use</label>
                        <input type="date" name="dateOfUse" class="form-control" required>
                        <label class="mt-2">Name of Requestor</label>
                        <input type="text" name="requestorName" class="form-control" required>
                        <label class="mt-2">Purpose</label>
                        <textarea name="purpose" class="form-control" required></textarea>
                        <label class="mt-2">Quantity</label>
                        <input type="text" name="quantity" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Submit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    emailjs.init({ publicKey: "Py-PphJ0-GQ1CxAuN" });
});
</script>

    <script>
        $(document).ready(function() {
            let table = $('#equipTable').DataTable({
                "ajax": "fetch.php",
                "pageLength": 5,
                "lengthChange": false,
                "columns": [
                    { data: "equipmentName" },
                    { data: "Status" },
                    { data: "quantity" },
                    {
                        data: null,
                        render: function(data) {
                            return data.Status === "Available" ?
                                `<button class="btn btn-request btn-sm requestBtn">Request</button>` :
                                `<span class="text-muted">Not Available</span>`;
                        }
                    }
                ]
            });

            $("#equipTable").on("click", ".requestBtn", function() {
                let data = table.row($(this).closest('tr')).data();
                if (!data) return;
                $("#reqEquipmentID").val(data.equipmentID);
                $("#reqEquipmentName").val(data.equipmentName);
                $("#requestModal").modal("show");
            });

            $("#requestForm").submit(function(e) {
                e.preventDefault();
                $.post("request.php", $(this).serialize())
                    .done(function(response) {
                        Swal.fire('Requested!', response, 'success');
                        $("#requestModal").modal("hide");
                        table.ajax.reload();
                        requestTable.ajax.reload();
                        $("#requestForm")[0].reset();
                    })
                    .fail(function(xhr, status, error) {
                        Swal.fire('Error', 'Could not submit request: ' + error, 'error');
                    });
            });

            $("#statusFilter").on("change", function() {
                table.column(1).search($(this).val()).draw();
            });

            let requestTable = $('#requestTable').DataTable({
                "ajax": {
                    "url": "fetch_request.php",
                    "data": function(d) {
                        d.search = $('#trackRequest').val();
                    }
                },
                "columns": [
                    { data: "requestorName" },
                    { data: "equipmentName" },
                    
                    { data: "dateRequested" },
                    { data: "dateOfUse" },
                    { data: "status" }
                ],
                "pageLength": 5,
                "lengthChange": false
            });

            $('#trackRequest').on('keyup', function() {
                requestTable.ajax.reload();
            });
        });
    </script>

</body>
</html>
