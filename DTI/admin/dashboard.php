<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: adminlogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>DTI R2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="icon" href="../dti.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
       
        body {
            font-family: 'Gilroy', 'sans-serif';
            
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

        .custom-header {
            background-color:aliceblue;
            color: black;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-top: 130px;
        }
        
        .main{
            background: linear-gradient(130deg, #8aa3fcff, #f4f9d4ff, #ffd6d6ff);
            height: 102vh;
            margin-top: -20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body>
<div class="main">
    <div class="container py-4 custom-header" id="mainContainer">

        <button class="btn btn-primary mb-3" id="addEquipmentBtn" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
            Add Equipment
        </button>
        <button id="toggleRequests" class="btn btn-warning mb-3">
            Show Requests
        </button>

        <div class="row mb-3" id="filterRow">
            <div class="col-md-3">
                <label class="text-black fw-bold" id="filterLabel">Filter status:</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All</option>
                    <option value="Available">Available</option>
                    <option value="Not Available">Not Available</option>
                </select>
            </div>
        </div>

        <div id="equipmentSection">
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

        <div id="requestSection" style="display: none;" >
            <table id="requestTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Requestor Name</th>
                        <th>Equipment Name</th>
                        <th>Date Requested</th>
                        <th>Date of Use</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="mt-4">
            <button id="logoutBtn" class="btn btn-danger px-4">Logout</button>
        </div>

    </div>

    <div class="modal fade" id="addEquipmentModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Equipment</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addForm">
                    <div class="modal-body">
                        <label>Equipment Name</label>
                        <input type="text" name="equipmentName" class="form-control" required>

                        <label>Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Available">Available</option>
                            <option value="Not Available">Not Available</option>
                        </select>

                        <label class="mt-2">Quantity</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEquipmentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Equipment</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm">
                    <div class="modal-body">
                        <input type="hidden" name="equipmentID" id="editID">
                        <label>Equipment Name</label>
                        <input type="text" name="equipmentName" id="editName" class="form-control" required>
                        <label class="mt-2">Status</label>
                        <select name="status" id="editStatus" class="form-select" required>
                            <option value="Available">Available</option>
                            <option value="Not Available">Not Available</option>
                        </select>

                        <label class="mt-2">Quantity</label>
                        <input type="number" name="quantity" id="editQuantity" class="form-control" min="0" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $("#logoutBtn").click(function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Logout',
                text: "Are you sure you want to logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#1f3586',
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        });

        $(document).ready(function() {
            let table = $('#equipTable').DataTable({
                "ajax": "fetch.php",
                "pageLength": 5,
                "lengthChange": false,
                "autoWidth": false,
                "columns": [{
                        data: "equipmentName"
                    },
                    {
                        data: "Status"
                    },
                    {
                        data: "quantity"
                    },
                    {
                        data: null,
                        render: function(data) {
                            return `
                                <button class="btn btn-warning btn-sm editBtn">Edit</button>
                                <button class="btn btn-danger btn-sm deleteBtn">Delete</button>
                            `;
                        }
                    }
                ]
            });

            let requestTable = $('#requestTable').DataTable({
                "ajax": "request.php",
                "pageLength": 5,
                "lengthChange": false,
                "autoWidth": false, 
                "columns": [{
                        data: "requestorName"
                    },
                    {
                        data: "equipmentName"
                    },
                    {
                        data: "dateRequested"
                    },
                    {
                        data: "dateOfUse"
                    },
                    {
                        data: "purpose"
                    },
                    {
                        data: "status"
                    },
                    {
                        data: null,
                        render: function(data) {
                            if (data.status === "Pending") {
                                return `
                        <button class="btn btn-success btn-sm approveBtn">Approve</button>
                        <button class="btn btn-danger btn-sm denyBtn">Deny</button>
                    `;
                            } else {
                                return `<span class="text-muted">${data.status}</span>`;
                            }
                        }
                    }
                ]
            });

            $("#addForm").submit(function(e) {
                e.preventDefault();
                let equipmentName = $("input[name='equipmentName']").val().trim();
                equipmentName = equipmentName.replace(/\b\w/g, char => char.toUpperCase());
                $("input[name='equipmentName']").val(equipmentName);

                $.post("add.php", $(this).serialize())
                    .done(function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Added!',
                            text: response,
                            confirmButtonColor: '#1f3586'
                        });
                        $("#addEquipmentModal").modal("hide");
                        table.ajax.reload();
                        $("#addForm")[0].reset();
                    })
                    .fail(function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error adding equipment: ' + error,
                            confirmButtonColor: '#d33'
                        });
                    });
            });

            $("#equipTable").on("click", ".editBtn", function() {
                let data = table.row($(this).closest('tr')).data();
                if (!data) return;
                $("#editID").val(data.equipmentID);
                $("#editName").val(data.equipmentName);
                $("#editStatus").val(data.Status);
                $("#editQuantity").val(data.quantity);

                $("#editEquipmentModal").modal("show");
            });

            $("#editForm").submit(function(e) {
                e.preventDefault();
                let equipmentName = $("#editName").val().trim();
                if (!equipmentName) {
                    Swal.fire('Error', 'Equipment name cannot be empty!', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Confirm Update',
                    text: `Are you sure you want to update "${equipmentName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1f3586',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, save changes.'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("update.php", $(this).serialize())
                            .done(function(response) {
                                Swal.fire('Updated!', response, 'success');
                                $("#editEquipmentModal").modal("hide");
                                table.ajax.reload();
                            })
                            .fail(function(xhr, status, error) {
                                Swal.fire('Error', 'Error updating equipment: ' + error, 'error');
                            });
                    }
                });
            });

            $('#requestTable').on('click', '.approveBtn', function() {
                let data = requestTable.row($(this).closest('tr')).data();
                Swal.fire({
                    title: 'Approve Request?',
                    text: `Approve ${data.equipmentName} for ${data.requestorName}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('update_request.php', {
                                id: data.requestID,
                                action: 'Approved'
                            })
                            .done(function(res) {
                                Swal.fire('Approved!', res, 'success');
                                requestTable.ajax.reload();
                            });
                    }
                });
            });

            $('#requestTable').on('click', '.denyBtn', function() {
                let data = requestTable.row($(this).closest('tr')).data();
                Swal.fire({
                    title: 'Deny Request?',
                    text: `Deny ${data.equipmentName} for ${data.requestorName}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('update_request.php', {
                                id: data.requestID,
                                action: 'Denied'
                            })
                            .done(function(res) {
                                Swal.fire('Denied!', res, 'success');
                                requestTable.ajax.reload();
                            });
                    }
                });
            });

            $("#equipTable").on("click", ".deleteBtn", function() {
                let data = table.row($(this).closest('tr')).data();
                if (!data) return;

                Swal.fire({
                    title: 'Are you sure?',
                    text: `Do you want to delete "${data.equipmentName}"? This cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#1f3586',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("delete.php", {
                                id: data.equipmentID
                            })
                            .done(function(response) {
                                Swal.fire('Deleted!', response, 'success');
                                table.ajax.reload();
                            })
                            .fail(function(xhr, status, error) {
                                Swal.fire('Error', 'Error deleting equipment: ' + error, 'error');
                            });
                    }
                });
            });

            $("#statusFilter").on("change", function() {
                let currentTable = $("#equipmentSection").is(":visible") ? table : requestTable;
                let columnIndex = $("#equipmentSection").is(":visible") ? 1 : 5; 
                currentTable.column(columnIndex).search($(this).val()).draw();
            });

            $("#toggleRequests").click(function() {
                if ($("#requestSection").is(":visible")) {
                    $("#requestSection").hide();
                    $("#equipmentSection").show();
                    $("#addEquipmentBtn").show();
                    $("#filterLabel").text("Filter status:");
                    $("#statusFilter").html(`
                        <option value="">All</option>
                        <option value="Available">Available</option>
                        <option value="Not Available">Not Available</option>
                    `);
                    $(this).removeClass("btn-secondary").addClass("btn-warning").text("Show Requests");
                    $("#mainContainer").removeClass("requests-view");
                } else {
                    $("#equipmentSection").hide();
                    $("#requestSection").show();
                    $("#addEquipmentBtn").hide();
                    $("#filterLabel").text("Filter status:");
                    $("#statusFilter").html(`           
                        <option value="">All</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Denied">Denied</option>
                    `);
                    $(this).removeClass("btn-warning").addClass("btn-secondary").text("Show Equipment");
                    $("#mainContainer").addClass("requests-view");
                    requestTable.ajax.reload();
                }
            });

            $("select[name='status']").on("change", function() {
                if ($(this).val() === "Not Available") {
                    $("input[name='quantity']").val(0).prop("disabled", true);
                } else {
                    $("input[name='quantity']").prop("disabled", false);
                }
            });
        });
    </script>
</div>
</body>

</html>