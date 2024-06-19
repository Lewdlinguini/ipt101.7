<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header("Location: Loginform.php");
    exit();
}
include 'db_conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject Assessment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            color: #495057;
        }
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content-wrapper {
            flex: 1;
        }
        .content {
            padding: 20px;
        }
        .card {
            height: 100%;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="update_profile.php" class="nav-link">Edit Profile</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="add_subject_form.php" class="nav-link">Subjects</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="add_assessment_form.php" class="nav-link">Assessment</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="logout.php" class="nav-link">Logout</a>
            </li>
        </ul>
    </nav>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Add Subject Assessment</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header" style="background-color: #343a40;">
                                <h3 class="card-title" style="color: #ffffff;">Assessment Details</h3>
                            </div>
                            <div class="card-body">
                                <form id="assessmentForm">
                                    <div class="form-group">
                                        <label for="assessment_name">Assessment Name:</label>
                                        <input type="text" id="assessment_name" name="assessment_name" class="form-control" placeholder="Enter assessment name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="assessment_date">Assessment Date:</label>
                                        <input type="date" id="assessment_date" name="assessment_date" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject_id">Subject:</label>
                                        <select id="subject_id" name="subject_id" class="form-control" required>
                                            <option value="">Select Subject</option>
                                            <?php
                                            $sql = "SELECT subject_code, subject_name FROM subjects";
                                            $result = $conn->query($sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row['subject_code'] . "'>" . $row['subject_name'] . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Assessment</button>
                                </form>
                                <div class="error" id="formError"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header" style="background-color: #343a40;">
                                <h3 class="card-title" style="color: #ffffff;">Existing Assessments</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Assessment Name</th>
                                            <th>Assessment Date</th>
                                            <th>Subject Code</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="assessmentTable">
                                        <?php
                                        $sql = "SELECT assessment_name, assessment_date, subject_id FROM subject_assessments";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr data-id='" . $row["assessment_name"] . "-" . $row["subject_id"] . "'><td>" . $row["assessment_name"] . "</td><td>" . $row["assessment_date"] . "</td><td>" . $row["subject_id"] . "</td><td><button class='btn btn-danger delete-btn'>Delete</button></td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4'>No assessments added yet.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button id="exportCsvBtn" class="btn btn-success">Export Assessments as CSV</button>
                                <div class="error" id="deleteError"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#assessmentForm').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'process_assessment.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#formError').html('');
                    var newRow = "<tr data-id='" + response.assessment_name + "-" + response.subject_id + "'><td>" + response.assessment_name + "</td><td>" + response.assessment_date + "</td><td>" + response.subject_id + "</td><td><button class='btn btn-danger delete-btn'>Delete</button></td></tr>";
                    $('#assessmentTable').append(newRow);
                } else {
                    $('#formError').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#formError').html('Error: ' + error);
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        var row = $(this).closest('tr');
        var id = row.data('id').split('-');
        var assessment_name = id[0];
        var subject_id = id[1];
        $.ajax({
            url: 'delete_assessment.php',
            method: 'POST',
            data: { assessment_name: assessment_name, subject_id: subject_id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#deleteError').html('');
                    row.remove();
                } else {
                    $('#deleteError').html(response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#deleteError').html('Error: ' + error);
            }
        });
    });

    // Function to fetch assessment data as CSV
    $('#exportCsvBtn').on('click', function(event) {
        event.preventDefault();
        // Direct the browser to the PHP script that generates the CSV
        window.location.href = 'fetch_assessments.php';
    });
});
</script>
</body>
</html>