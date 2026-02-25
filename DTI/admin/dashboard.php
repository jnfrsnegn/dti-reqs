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

        .modal-body input {
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

        .main {
            background: linear-gradient(130deg, #8aa3fcff, #f4f9d4ff, #ffd6d6ff);
            min-height: 100vh;
            margin-top: 0;
            padding-bottom: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
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

            .custom-header {
                margin-top: 20px;
                padding: 15px;
                border-radius: 8px;
            }

            .main {
                min-height: 100vh;
                margin-top: 0;
                padding-bottom: 0;
                display: flex;
                flex-direction: column;
                align-items: stretch;
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

            .modal-body input {
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

            #equipmentSection,
            #requestSection,
            #reportSection {
                overflow-x: auto;
            }
        }

        @media (max-width: 480px) {
            .main {
                min-height: 100vh;
                margin-top: 0;
                padding-bottom: 0;
            }

            .custom-header {
                margin-top: 20px;
                padding: 10px;
            }

            .modal-dialog {
                margin: 5px;
                width: 100%;
                height: 100vh;
                max-width: none;
            }

            .modal-content {
                height: 100%;
                border-radius: 0;
                display: flex;
                flex-direction: column;
            }

            .modal-body {
                flex: 1;
                overflow-y: auto;
            }

            #equipmentSection,
            #requestSection,
            #reportSection {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="container py-4 custom-header" id="mainContainer">
            <button class="btn btn-primary mb-3" id="addEquipmentBtn" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
                Add Resource
            </button>
            <button id="toggleRequests" class="btn btn-warning mb-3">
                Show Requests
            </button>
            <button id="toggleReport" class="btn btn-success mb-3">Reports</button>

            <div id="equipmentSection" class="table-responsive">
                <table id="equipTable" class="table table-striped table-bordered w-100">
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

            <div id="requestSection" class="table-responsive" style="display: none;">
                <table id="requestTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Requestor</th>
                            <th>Email</th>
                            <th>Resources</th>
                            <th>Quantity</th>
                            <th>Date Requested</th>
                            <th>Date of Use</th>
                            <th>Date of Return</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div id="reportSection" class="table-responsive" style="display: none;">
                <div class="mb-3 d-flex align-items-center gap-2">
                    <label for="monthFilter" class="mb-0">Filter by Month:</label>
                    <input type="month" id="monthFilter" class="form-control w-auto">
                    <label for="statusReportFilter" class="mb-0">Filter by Status:</label>
                    <select id="statusReportFilter" class="form-select w-auto">
                        <option value="">All</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Denied">Denied</option>
                    </select>
                  
                    <button id="printReport" class="btn btn-primary">Print Report</button>
                </div>

                <table id="reportTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Requestor</th>
                            <th>Email</th>
                            <th>Resources</th>
                            <th>Quantity</th>
                            <th>Date Requested</th>
                            <th>Date of Use</th>
                            <th>Date of Return</th>
                            <th>Purpose</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="mt-4">
                <button id="logoutBtn" class="btn btn-danger px-4">Logout</button>
                <button id="changePass" class="btn btn-primary px-4">Change Password</button>
            </div>
        </div>

        <div class="modal fade" id="addEquipmentModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Resources</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addForm">
                        <div class="modal-body">
                            <label>Resources</label>
                            <input type="text" name="equipmentName" class="form-control" required>
                            <label>Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Available">Available</option>
                                <option value="Not Available">Not Available</option>
                            </select>
                            <label class="mt-2">Quantity</label>
                            <input type="number" name="quantity" class="form-control" required>
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
                        <h5 class="modal-title">Edit Resources</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editForm">
                        <div class="modal-body">
                            <input type="hidden" name="equipmentID" id="editID">
                            <label>Resources</label>
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
       <!-- Change Password Modal -->
<div class="modal fade" id="changePassModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePassForm">
                <div class="modal-body">
                    <label>Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                    <label class="mt-2">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                    <label class="mt-2">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="form-check px-4">
                    <input class="form-check-input" type="checkbox" id="showPasswordCheck">
                    <label class="form-check-label" for="showPasswordCheck">Show Passwords</label>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Request Modal (completely outside changePassModal) -->
<div class="modal fade" id="viewRequestModal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Details</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="requestItemsContainer"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="approveSelected">Approve Selected</button>
            </div>
        </div>
    </div>
</div>
        

        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        <script>
            $("#showPasswordCheck").on("change", function() {
                let isChecked = $(this).is(":checked");

                $("#changePassForm input[type='password'], #changePassForm input[type='text']").each(function() {
                    let currentType = $(this).attr("type");
                    if (currentType === "password" || currentType === "text") {
                        $(this).attr("type", isChecked ? "text" : "password");
                    }
                });
            });

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
                    "pageLength": 4,
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
                $("#printReport").click(function () {
    let month = $('#monthFilter').val();
    let status = $('#statusReportFilter').val();

    window.open(`print_reports.php?month=${month}&status=${status}`, '_blank');
});

                $("#changePass").click(function(e) {
                    e.preventDefault();
                    $("#changePassModal").modal("show");
                });
                $("#changePassForm").submit(function(e) {
                    e.preventDefault();

                    let newPass = $("input[name='new_password']").val();
                    let confirmPass = $("input[name='confirm_password']").val();

                    if (newPass !== confirmPass) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Password Mismatch',
                            text: 'New password and confirmation do not match!'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Confirm Change',
                        text: 'Are you sure you want to change your password?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1f3586',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("changePass.php", $(this).serialize())
                                .done(function(response) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: response
                                    });

                                    $("#changePassModal").modal("hide");
                                    $("#changePassForm")[0].reset();
                                })
                                .fail(function() {
                                    Swal.fire('Error', 'Failed to change password.', 'error');
                                });
                        }
                    });
                });


                let requestTable = $('#requestTable').DataTable({
    "ajax": "request.php",
    "pageLength": 6,
    "lengthChange": false,
    "autoWidth": false,
    "columns": [
        { data: "requestorName" },
        { data: "emailAddress" },
        { 
            data: "equipmentName",
            render: function(data, type, row) {
                if (!data) return '';
                if (data.includes(',')) {
                    return `<button class="btn btn-primary btn-sm viewRequestBtn">View Request</button>`;
                }
                return data || ''; 
            }
        },
        { data: "quantity" },
        { data: "dateRequested" },
        { data: "dateOfUse" },
        { data: "dateOfReturn" },
        { data: "purpose" },
        { data: "status" },
        { 
            data: null,
            render: function(data, type, row) {
                let status = row.status;
                if (status === "Pending") {
                    return `<button class="btn btn-primary btn-sm viewRequestBtn">View Request</button>`;
                }
                if (status === "Approved") {
                    return `<span class="badge bg-success me-2">Approved</span>
                            <button class="btn btn-primary btn-sm viewSlipBtn">View Slip</button>`;
                }
                return `<span class="badge bg-danger">Denied</span>`;
            }
        }
    ]
});
                $('#requestTable').on('click', '.viewSlipBtn', function() {
                    let data = requestTable.row($(this).closest('tr')).data();

                    window.open(
                        'view_slip.php?id=' + data.requestID,
                        '_blank'
                    );
                });
$('#approveSelected').click(function() {
    let selected = [];
    $('.itemCheck:checked').each(function() {
        selected.push($(this).val());
    });

    if (selected.length === 0) {
        Swal.fire('No selection', 'Please select at least one item to approve.', 'warning');
        return;
    }

    Swal.fire({
        title: 'Approve Selected?',
        text: `Are you sure you want to approve ${selected.length} item(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, approve',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('update_request.php', { 
                items: selected,
                action: 'Approved'
            }, function(res) {
                Swal.fire('Approved!', res, 'success');
                // Reload the request table to update statuses
                requestTable.ajax.reload();
                // Hide modal
                let viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRequestModal'));
                viewModal.hide();
            }).fail(function() {
                Swal.fire('Error', 'Failed to approve selected items.', 'error');
            });
        }
    });
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
                                title: 'Success',
                                text: response,
                                confirmButtonColor: '#1f3586'
                            });

                            $("#addEquipmentModal").modal("hide");
                            table.ajax.reload();
                            $("#addForm")[0].reset();
                        })
                        .fail(function(xhr) {
                            let msg = xhr.responseText || "Error adding resource.";

                            Swal.fire({
                                icon: 'error',
                                title: 'Duplicate Resource',
                                text: msg,
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
  $('#requestTable').on('click', '.viewRequestBtn', function() {
    let data = requestTable.row($(this).closest('tr')).data();
    if (!data) return;

    $.get("get_request_items.php", { id: data.requestID }, function(response) {
        let items = JSON.parse(response); 

        let html = "";
        items.forEach(function(item) {
            html += `
                <div class="form-check mb-2">
                    <input class="form-check-input itemCheck" type="checkbox" value="${item.id}">
                    <label class="form-check-label">
                        ${item.equipmentName} (Requested: ${item.quantity})
                    </label>
                </div>
            `;
        });

        $("#requestItemsContainer").html(html);

        // Bootstrap 5 modal API
        let viewModal = new bootstrap.Modal(document.getElementById('viewRequestModal'));
        viewModal.show();
    }).fail(function() {
        Swal.fire('Error', 'Failed to load request items.', 'error');
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
                let reportTable = $('#reportTable').DataTable({
                    "ajax": {
                        "url": "reports.php",
                        "data": function(d) {
                            d.month = $('#monthFilter').val();
                            d.status = $('#statusReportFilter').val();
                        }
                    },
                    "pageLength": 6,
                    "lengthChange": false,
                    "autoWidth": false,
                    "columns": [{
                            data: "requestorName"
                        },
                        {
                            data: "emailAddress"
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
                            data: "purpose"
                        },
                        {
                            data: "status"
                        }
                    ]
                });


                $('#toggleReport').click(function() {
                    if ($('#reportSection').is(":visible")) {
                        $('#reportSection').hide();
                        $("#equipmentSection").show();
                        $("#requestSection").hide();
                        $("#addEquipmentBtn").show();
                        $(this).removeClass("btn-secondary").addClass("btn-success").text("Reports");
                    } else {
                        $('#reportSection').show();
                        $("#equipmentSection").hide();
                        $("#requestSection").hide();
                        $("#addEquipmentBtn").hide();
                        $(this).removeClass("btn-success").addClass("btn-secondary").text("Hide Reports");
                        reportTable.ajax.reload();
                    }
                });

                $('#monthFilter, #statusReportFilter').change(function() {
                    reportTable.ajax.reload();
                });

                $('#resetMonthFilter,#statusReportFilter').click(function() {
                    $('#monthFilter').val('');
                    $('#statusFilter').val('');
                    reportTable.ajax.reload();
                });


                $("#statusFilter").on("change", function() {
                    let currentTable = $("#equipmentSection").is(":visible") ? table : requestTable;
                    let columnIndex = $("#equipmentSection").is(":visible") ? 1 : 6;
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
                        $("input[name='quantity']").val(0).prop("readonly", true);
                    } else {
                        $("input[name='quantity']").prop("readonly", false);
                    }
                });

            });
        </script>
        
    </div>
</body>

</html>