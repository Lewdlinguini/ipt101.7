<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header("Location: Loginform.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>
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
    </style>
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
        <a href="add_assessment_form.php" class="nav-link">Assesment</a>
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
                        <h1 class="m-0">Add Subject</h1>
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
                                <h3 class="card-title" style="color: #ffffff;">Subject Details</h3>
                            </div>
                            <div class="card-body">
                                <form action="add_subject.php" method="post">
                                    <div class="form-group">
                                        <label for="subject_name">Subject Name:</label>
                                        <input type="text" id="subject_name" name="subject_name" class="form-control" placeholder="Enter subject name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="subject_code">Subject Code:</label>
                                        <input type="text" id="subject_code" name="subject_code" class="form-control" placeholder="Enter subject code" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Add Subject</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header" style="background-color: #343a40;">
                                <h3 class="card-title" style="color: #ffffff;">Added Subjects</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- PHP code to fetch and display subjects from the database -->
                                        <?php
                                        include 'db_conn.php';

                                        $sql = "SELECT subject_name, subject_code FROM subjects";
                                        $result = $conn->query($sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr><td>" . $row["subject_name"] . "</td><td>" . $row["subject_code"] . "</td><td><form action='delete_subject.php' method='post'><input type='hidden' name='subject_name' value='" . $row["subject_name"] . "'><input type='hidden' name='subject_code' value='" . $row["subject_code"] . "'><button type='submit' class='btn btn-danger'>Delete</button></form></td></tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='3'>No subjects added yet.</td></tr>";
                                        }
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
</html>