<?php
session_start();

if (!isset($_SESSION['authenticated'])) {
    header("Location: Loginform.php");
    exit();
}

include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE user_profile SET full_name=?, email=?, phone_number=?, address=?, photo=? WHERE user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $full_name, $email, $phone_number, $address, $photo, $user_id);

    $full_name = $_POST['user_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $user_id = $_SESSION['user_id'];
    $photo = $_SESSION['photo']; // Default photo if not updated

    // Handling file upload
    if (isset($_FILES['user_photo']) && $_FILES['user_photo']['error'] == UPLOAD_ERR_OK) {
        $photo_name = $_FILES['user_photo']['name'];
        $photo_tmp_name = $_FILES['user_photo']['tmp_name'];
        $photo_folder = 'uploads/' . $photo_name;

        if (move_uploaded_file($photo_tmp_name, $photo_folder)) {
            $photo = $photo_name;
            $_SESSION['photo'] = $photo_name; // Update session photo
        } else {
            $error_message = "Error uploading photo.";
        }
    }

    if ($stmt->execute()) {
        $_SESSION['user_name'] = $full_name;
        $_SESSION['email'] = $email;
        $_SESSION['phone_number'] = $phone_number;
        $_SESSION['address'] = $address;
        header("Location: update_profile.php");
        exit();
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }

    $stmt->close();
}

$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$user_email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$user_phone_number = isset($_SESSION['phone_number']) ? $_SESSION['phone_number'] : '';
$user_address = isset($_SESSION['address']) ? $_SESSION['address'] : '';
$user_photo = isset($_SESSION['photo']) ? $_SESSION['photo'] : 'default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body>
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
                            <h1 class="m-0">Update Profile</h1>
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
                              <h3 class="card-title" style="color: #ffffff;">Profile</h3>
                            </div>
                                <div class="card-body d-flex">
                                    <div style="flex: 1;">
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="user_photo">Photo:</label>
                                                <input type="file" id="user_photo" name="user_photo" class="form-control-file">
                                            </div>
                                            <div class="form-group">
                                                <label for="user_name">Name:</label>
                                                <input type="text" id="user_name" name="user_name" class="form-control" value="<?php echo htmlspecialchars($user_name); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="email">Email:</label>
                                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="phone_number">Phone Number:</label>
                                                <input type="text" id="phone_number" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($user_phone_number); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address:</label>
                                                <textarea id="address" name="address" class="form-control"><?php echo htmlspecialchars($user_address); ?></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary" style="background-color: #343a40; border-color: #343a40;">Update Profile</button>
                                        </form>
                                    </div>
                                    <div style="flex: 0 0 150px; margin-left: 20px;">
                                        <div style="width: 150px; height: 150px; border-radius: 50%; overflow: hidden; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);">
                                            <img src="uploads/<?php echo htmlspecialchars($user_photo); ?>" alt="User Photo" style="width: 100%; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>