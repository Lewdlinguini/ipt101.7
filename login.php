<?php
session_start();
include "db_conn.php";

if (isset($_POST['uname']) && isset($_POST['password'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);

    if (empty($uname)) {
        header("Location: Loginform.php?error=User Name is required");
        exit();
    } elseif (empty($pass)) {
        header("Location: Loginform.php?error=Password is required");
        exit();
    } else {
        // Check if the user exists and is verified
        $sql = "SELECT * FROM user WHERE username=? AND password=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $uname, $pass);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            if ($row['Status'] === 'active') {
                $_SESSION['authenticated'] = true;
                $_SESSION['user_name'] = $row['username'];
                $_SESSION['name'] = $row['First_name'];
                $_SESSION['id'] = $row['id'];
                header("Location: index.php");
                exit();
            } else {
                header("Location: Loginform.php?error=Please verify your email before logging in");
                exit();
            }
        } else {
            header("Location: Loginform.php?error=Incorrect User name or password");
            exit();
        }
    }
} else {
    header("Location: Loginform.php");
    exit();
}
?>