<?php
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header("Location: Loginform.php");
    exit();
}

include 'db_conn.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader (if installed via Composer)
require 'vendor/autoload.php';

// Function to send an email confirmation using PHPMailer
function sendConfirmationEmail($email, $task_description) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Set your SMTP server here
        $mail->SMTPAuth   = true;
        $mail->Username   = 'markcussy032@gmail.com'; // SMTP username
        $mail->Password   = 'njay ifhp sbhm zoli'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('markcussy032@gmail.com', 'Mark Cussy');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Task Submission Confirmation';
        $mail->Body    = "<p>Thank you for submitting your task.</p><p><b>Task Description:</b> $task_description</p><p>Best Regards,<br>Task Management System</p>";

        // Send the email
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $task_description = mysqli_real_escape_string($conn, $_POST['task_description']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Validate inputs
    if (empty($task_description) || empty($email)) {
        $message = "Both fields are required.";
    } else {
        // Handle file upload
        $target_dir = "uploads/";
        $file_name = basename($_FILES["file_upload"]["name"]);
        $target_file = $target_dir . $file_name;
        $uploadOk = 1;

        // Check if file already exists
        if (file_exists($target_file)) {
            $message = "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["file_upload"]["size"] > 5000000) { // Adjust file size limit if needed
            $message = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $message = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["file_upload"]["tmp_name"], $target_file)) {
                // Insert data into the database
                $sql = "INSERT INTO submitted_tasks (task_description, email, file_path) VALUES ('$task_description', '$email', '$target_file')";
                if (mysqli_query($conn, $sql)) {
                    $message = "Task submitted successfully.";
                    sendConfirmationEmail($email, $task_description);
                } else {
                    $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Task</title>
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
                        <h1 class="m-0">Submit Task</h1>
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
                                <h3 class="card-title" style="color: #ffffff;">Task Details</h3>
                            </div>
                            <div class="card-body">
                                <form method="post" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="task_description">Task Description:</label>
                                        <input type="text" id="task_description" name="task_description" class="form-control" placeholder="Enter task description" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="file_upload">Upload File:</label>
                                        <input type="file" id="file_upload" name="file_upload" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                                <div class="error"><?php if (isset($message)) echo $message; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- You can add more content here or leave it empty -->
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
</body>
</html>