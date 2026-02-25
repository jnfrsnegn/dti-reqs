<?php
require('../conn.php');
$today = date('Y-m-d');
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
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            font-family: 'Gilroy', 'sans-serif';
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            min-height: calc(100% - 1rem);
        }

        .modal-content {
              background: linear-gradient(135deg, #1f3586, #e4e0a0);
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

        .modal-body input,
        .modal-body textarea {
            border-radius: 8px;
        }

        .custom-header {
            background-color: aliceblue;
            color: black;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-top: 130px;
        }

        .btn-request {
            background-color: #0d6efd;
            color: white;
        }

        .btn-request:hover {
            background-color: #f1d600;
            color: #000;
        }

        .main {
            background: linear-gradient(130deg, #8aa3fcff, #f4f9d4ff, #ffd6d6ff);
            min-height: 100vh;
            margin-top: 0;
            padding-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            margin-top: 30px;
        }

        @media (max-width: 768px) {

            html,
            body {
                height: 100%;
                margin: 0;
                padding: 0;
            }

            body {
                font-size: 14px;
            }

            .main {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                min-height: 100vh;
                margin-top: 0;
                padding-bottom: 0;
            }

            .custom-header {
                margin-top: 20px;
                padding: 15px;
                border-radius: 8px;
            }

            .container {
                padding-left: 10px;
                padding-right: 10px;
                margin-bottom: 0;
            }

            .table-responsive {
                width: 100%;
                overflow-x: auto;
            }

            table.dataTable {
                width: 100% !important;
                white-space: normal;
            }

            table.dataTable thead th,
            table.dataTable tbody td {
                font-size: 13px;
                padding: 8px;
            }

            .btn {
                font-size: 13px;
                padding: 6px 10px;
            }

            .btn-request {
                width: 100%;
            }

            .modal-dialog {
                margin: 10px;
                max-width: 100%;
            }

            .modal-content {
                border-radius: 12px;
            }

            .modal-title {
                font-size: 16px;
            }

            .modal-body label {
                font-size: 13px;
                margin-top: 8px;
            }

            .modal-body input,
            .modal-body textarea {
                font-size: 14px;
                padding: 8px;
            }

            .modal-footer {
                flex-direction: column;
                gap: 10px;
            }

            .modal-footer .btn {
                width: 100%;
            }

            .dataTables_filter input {
                width: 100%;
                margin-top: 5px;
            }

            .dataTables_wrapper .dataTables_paginate {
                margin-top: 10px;
                text-align: center;
            }
        }
    </style>
</head>

<body class="bg-light">
    <div class="main">

        <div class="container py-4 custom-header">

            <div class="row mb-4">
                <div class="col-12">
                    <div class="table-responsive request-scroll">
                        <table id="requestTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Requestor</th>
                                    <th>Resources</th>
                                    <th>Quantity</th>
                                    <th>Date Requested</th>
                                    <th>Date of Use</th>
                                    <th>Date of Return</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table id="equipTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Resources</th>
                            <th>Status</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="modal fade" id="requestModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Request Resources</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form id="requestForm">
                        <div class="modal-body">
                            <table class="table table-sm" id="multiRequestTable">
                                <thead>
                                    <tr>
                                        <th>Resources</th>
                                        <th>Quantity</th>
                                        <th>Purpose</th>
                                        <th><button type="button" class="btn btn-sm btn-primary" id="addRowBtn">Add</button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="equipmentID[]" class="form-select" required></select>
                                        </td>
                                        <td>
                                            <input type="number" name="quantity[]" class="form-control" min="1" required placeholder="Enter quantity.">
                                        </td>
                                        <td>
                                            <input type="text" name="purpose[]" class="form-control" min="1" required placeholder="Enter purpose.">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger removeRowBtn">Remove</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <label class="mt-2">Date Requested</label>
                            <input type="date" name="dateRequested" class="form-control" value="<?= $today ?>" min="<?= $today ?>" required>

                            <label class="mt-2">Date of Use</label>
                            <input type="date" name="dateOfUse" class="form-control" min="<?= $today ?>" required>

                            <label class="mt-2">Date of Return</label>
                            <input type="date" name="dateOfReturn" class="form-control" min="<?= $today ?>" required>

                            <label class="mt-2">Name of Requestor</label>
                            <input type="text" name="requestorName" class="form-control" required>

                            <label class="mt-2">Email Address</label>
                            <input type="email" name="emailAddress" class="form-control" placeholder="example@domain.com" required>

                          
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
        emailjs.init({
            publicKey: "Py-PphJ0-GQ1CxAuN"
        });
        emailjs.init({
            publicKey:"7wAp_vgRXefd0yvSd"
        })
        $(document).ready(function() {

            let equipTable = $('#equipTable').DataTable({
                ajax: "fetch.php",
                pageLength: 6,
                lengthChange: false,
                columns: [{
                        data: "equipmentName"
                    },
                    {
                        data: "Status"
                    },
                     { data: "quantity" 

                     },

                    {
                        data: null,
                        render: data =>
                            data.Status === "Available" ?
                            `<button class="btn btn-request btn-sm requestBtn">Request</button>` : `<span class="text-muted">Not Available</span>`
                    }
                ]
            });

            $("#equipTable").on("click", ".requestBtn", function() {
                let data = equipTable.row($(this).closest('tr')).data();
                $("#reqEquipmentID").val(data.equipmentID);
                $("#reqEquipmentName").val(data.equipmentName);
                $("#requestModal").modal("show");
            });

            $("#requestForm").submit(function(e) {
    e.preventDefault();

    $.post("request.php", $(this).serialize(), function(res) {
        console.log(res);

        if (res.success) {
            let equipmentNames = [];
            let quantities = [];
            let purposes = [];
            
            $('select[name="equipmentID[]"]').each(function(index) {
                let equipID = $(this).val();
                let equipName = $(this).find('option:selected').text(); 
                if (equipID && equipName !== 'Select Resources') {
                    equipmentNames.push(equipName);
                    quantities.push($('input[name="quantity[]"]').eq(index).val());
                    purposes.push($('input[name="purpose[]"]').eq(index).val());
                }
            });
            
            let equipmentList = equipmentNames.join(', ');
            let quantityList = quantities.join(', ');
            let purposeList = purposes.join(', ');
            
            emailjs.send("service_cny52jd", "template_7iv08kp", {
                requestorName: $('input[name="requestorName"]').val(),
                emailAddress: $('input[name="emailAddress"]').val(),
                equipmentName: equipmentList,  
                quantity: quantityList,        
                purpose: purposeList,         
                dateRequested: $('input[name="dateRequested"]').val(),
                dateOfUse: $('input[name="dateOfUse"]').val(),
                dateOfReturn: $('input[name="dateOfReturn"]').val()
            });
            emailjs.send("service_oryi6bn", "template_xg33fx9", {
  requestorName: $('input[name="requestorName"]').val(),
  emailAddress: $('input[name="emailAddress"]').val()
});

            
            Swal.fire("Requested!", "Request submitted successfully.", "success");
            $("#requestModal").modal("hide");
            equipTable.ajax.reload();
            requestTable.ajax.reload();
            $("#requestForm")[0].reset();
        } else {
            Swal.fire("Error", "Request failed.", "error");
        }
    }).fail(function(xhr) {
        console.error(xhr.responseText);
        Swal.fire("Error", "Failed to submit request.", "error");
    });
});



            let requestTable = $('#requestTable').DataTable({
                ajax: {
                    url: "fetch_request.php",
                    dataSrc: "data" 
                },
                pageLength: 5,
                lengthChange: false,
                columns: [{
                        data: "requestorName"
                    },
                   
                    {
                        data: "equipmentName"
                    },
                   
                    {
                        data: "quantity"
                    },
                    {
                        data: "dateRequested"
                    },
                    {
                        data: "dateOfUse"
                    },
                    {
                        data: "dateOfReturn"
                    },
                    {
                        data: "status"
                    }
                ]
            });


        });
    </script>
    <script>
        $(document).ready(function() {
          function loadEquipmentOptions(selector = '#multiRequestTable select') {
    $.getJSON('fetch.php', function(data) {
        let options = '<option value="">Select Resources</option>';
        data.data.forEach(e => {
            if (e.Status === "Available") {
                options += `<option value="${e.equipmentID}">${e.equipmentName}</option>`;
            }
        });
        $(selector).each(function() {
            $(this).html(options);
        });
    });
}

loadEquipmentOptions();

$('#addRowBtn').click(function() {
    let newRow = `<tr>
        <td>
            <select name="equipmentID[]" class="form-select" required></select>
        </td>
        <td>
            <input type="number" name="quantity[]" class="form-control" min="1" required>
        </td>
        <td>
            <input type="text" name="purpose[]" class="form-control" min="1" required>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger removeRowBtn">Remove</button>
        </td>
    </tr>`;
    $('#multiRequestTable tbody').append(newRow);
    loadEquipmentOptions('#multiRequestTable tbody tr:last select');
});

$(document).on('click', '.removeRowBtn', function() {
    $(this).closest('tr').remove();
});

            const dateOfUse = $('input[name="dateOfUse"]');
            const dateOfReturn = $('input[name="dateOfReturn"]');

            dateOfUse.on('change', function() {
                dateOfReturn.attr('min', $(this).val());
                if (dateOfReturn.val() && dateOfReturn.val() < $(this).val()) {
                    dateOfReturn.val('');
                }
            });

        });
    </script>


</body>

</html>