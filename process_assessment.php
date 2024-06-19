<?php
// process_assessment.php

include 'db_conn.php';
header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assessment_name = trim($_POST['assessment_name']);
    $assessment_date = trim($_POST['assessment_date']);
    $subject_id = trim($_POST['subject_id']);

    if (empty($assessment_name) || empty($assessment_date) || empty($subject_id)) {
        $response['success'] = false;
        $response['message'] = "All fields are required.";
        echo json_encode($response);
        exit;
    }

    // Check if the subject is already assigned to the assessment
    $check_sql = "SELECT * FROM subject_assessments WHERE assessment_name = ? AND subject_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("ss", $assessment_name, $subject_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $response['success'] = false;
        $response['message'] = "This subject is already assigned to the assessment.";
    } else {
        // Insert the new assessment
        $insert_sql = "INSERT INTO subject_assessments (assessment_name, assessment_date, subject_id) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($insert_sql);
        $stmt_insert->bind_param("sss", $assessment_name, $assessment_date, $subject_id);

        if ($stmt_insert->execute()) {
            $response['success'] = true;
            $response['assessment_name'] = $assessment_name;
            $response['assessment_date'] = $assessment_date;
            $response['subject_id'] = $subject_id;
        } else {
            $response['success'] = false;
            $response['message'] = "Failed to add assessment.";
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request.";
}

$conn->close();
echo json_encode($response);
?>