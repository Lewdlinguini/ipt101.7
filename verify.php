<?php
include "db_conn.php";

if(isset($_GET['email'])) {
    $email = $_GET['email'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $verification_code = $_POST['verification_code'];

        // prepared statement 
        $stmt = $conn->prepare("SELECT * FROM user WHERE Email = ? AND verification_code = ?");
        $stmt->bind_param("ss", $email, $verification_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $update_stmt = $conn->prepare("UPDATE user SET Status = 'active' WHERE Email = ?");
            $update_stmt->bind_param("s", $email);
            if ($update_stmt->execute()) {
                header("Location: Loginform.php");
                exit();
            } else {
                echo '<p class="error">Error updating status.</p>';
            }
            $update_stmt->close();
        } else {
            echo '<p class="error-message">Invalid verification code. Please try again.</p>';
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Verification</h3>
                        </div>
                        <form role="form" action="verify.php?email=<?php echo htmlspecialchars($email); ?>" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="verification_code">Verification Code</label>
                                    <input type="text" class="form-control" id="verification_code" name="verification_code" placeholder="Enter verification code" required>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Verify</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>