<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve subject name and subject code from the POST request
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];

    // Prepare and execute SQL query to delete the subject
    $stmt = $conn->prepare("DELETE FROM subjects WHERE subject_name = ? AND subject_code = ?");
    $stmt->bind_param("ss", $subject_name, $subject_code);
    $stmt->execute();

    // Close the prepared statement
    $stmt->close();

    // Redirect back to the form page
    header("Location: add_subject_form.php");
    exit(); // Ensure script execution stops after redirection
}

$conn->close();
?>