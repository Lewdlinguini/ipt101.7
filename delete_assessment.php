<?php
// delete_assessment.php

include 'db_conn.php';
header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $assessment_name = trim($_POST['assessment_name']);
    $subject_id = trim($_POST['subject_id']);

    if (empty($assessment_name) || empty($subject_id)) {
        $response['success'] = false;
        $response['message'] = "Invalid request data.";
        echo json_encode($response);
        exit;
    }

    $sql = "DELETE FROM subject_assessments WHERE assessment_name = ? AND subject_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $assessment_name, $subject_id);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to delete assessment.";
    }
    $stmt->close();
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request.";
}

$conn->close();
echo json_encode($response);
?>