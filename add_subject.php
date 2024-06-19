<?php
// Include the database connection file
include 'db_conn.php';

// Retrieve subject data from the form
$subject_name = $_POST['subject_name'] ?? '';
$subject_code = $_POST['subject_code'] ?? '';

// Check if subject name or subject code is empty
if (empty($subject_name) || empty($subject_code)) {
    echo "Error adding subject: Subject name or code is empty.";
    header("Location: add_subject_form.php"); // Redirect back to the form
    exit();
}

// Check if the subject name already exists in the database
$stmt_name = $conn->prepare("SELECT * FROM subjects WHERE subject_name = ?");
$stmt_name->bind_param("s", $subject_name);
$stmt_name->execute();
$stmt_name->store_result();

// Check if the subject code already exists in the database
$stmt_code = $conn->prepare("SELECT * FROM subjects WHERE subject_code = ?");
$stmt_code->bind_param("s", $subject_code);
$stmt_code->execute();
$stmt_code->store_result();

if ($stmt_name->num_rows > 0) {
    echo "Error adding subject: Subject name already exists.";
    $stmt_name->close();
    $stmt_code->close();
    header("Location: add_subject_form.php"); // Redirect back to the form
    exit();
} elseif ($stmt_code->num_rows > 0) {
    echo "Error adding subject: Subject code already exists.";
    $stmt_name->close();
    $stmt_code->close();
    header("Location: add_subject_form.php"); // Redirect back to the form
    exit();
} else {
    // Prepare and execute the SQL query to insert the subject into the database
    $stmt_insert = $conn->prepare("INSERT INTO subjects (subject_name, subject_code) VALUES (?, ?)");
    $stmt_insert->bind_param("ss", $subject_name, $subject_code);
    $stmt_insert->execute();

    // Check if the insertion was successful
    if ($stmt_insert->affected_rows > 0) {
        echo "Subject added successfully.";
        header("Location: add_subject_form.php"); // Redirect back to the form after successful submission
        exit();
    } else {
        echo "Error adding subject: " . $conn->error;
    }

    // Close the prepared statement
    $stmt_insert->close();
}

// Close the prepared statements
$stmt_name->close();
$stmt_code->close();
?>