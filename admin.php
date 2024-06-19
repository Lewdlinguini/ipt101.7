<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header("Location: Loginform.php");
    exit();
}

include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task_id'])) {
    $task_id = intval($_POST['task_id']);
    $sql = "DELETE FROM submitted_tasks WHERE task_id = $task_id";

    if ($conn->query($sql) === TRUE) {
        $message = "Task deleted successfully";
    } else {
        $message = "Error deleting task: " . $conn->error;
    }
}

$sql = "SELECT task_id, task_description, email, submission_time, file_path FROM submitted_tasks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks</title>
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
        <a href="add_assessment_form.php" class="nav-link">Assesment</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="submit_task.php" class="nav-link">Task Submission</a>
      </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="admin.php" class="nav-link">Manage Tasks</a>
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
                        <h1 class="m-0">Manage Tasks</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header" style="background-color: #343a40;">
                                <h3 class="card-title" style="color: #ffffff;">Submitted Tasks</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Task Description</th>
                                            <th>Email</th>
                                            <th>Submission Time</th>
                                            <th>File</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row["task_id"] . "</td>";
                                                echo "<td>" . $row["task_description"] . "</td>";
                                                echo "<td>" . $row["email"] . "</td>";
                                                echo "<td>" . $row["submission_time"] . "</td>";
                                                if (!empty($row["file_path"])) {
                                                    echo "<td><a href='" . $row["file_path"] . "' download>Download File</a></td>";
                                                } else {
                                                    echo "<td>No file uploaded</td>";
                                                }
                                                echo "<td><form method='post' action=''><input type='hidden' name='task_id' value='" . $row["task_id"] . "'><button type='submit' class='btn btn-danger'>Delete</button></form></td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No tasks submitted yet.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="error"><?php if (isset($message)) echo $message; ?></div>
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