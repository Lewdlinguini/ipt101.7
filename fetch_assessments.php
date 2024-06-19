<?php
include 'db_conn.php';

// Output headers to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=assessments.csv');

// Open the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('Assessment Name', 'Assessment Date', 'Subject ID'));

// Fetch the data
$sql = "SELECT assessment_name, assessment_date, subject_id FROM subject_assessments";
$result = $conn->query($sql);

// Loop through the data and output each row to the CSV file
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
} else {
    fputcsv($output, array('No assessments found.'));
}

// Close the output stream
fclose($output);
$conn->close();
?>