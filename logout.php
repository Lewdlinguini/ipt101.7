<?php
session_start();

unset($_SESSION['authenticated']);
unset($_SESSION['user_name']);
unset($_SESSION['name']);
unset($_SESSION['id']);


header("Location: Loginform.php");
exit();
?>