<?php
include 'db_conn.php';

if (isset($_GET['id'])) {
    $task_id = $_GET['id'];
    $sql = "DELETE FROM submitted_tasks WHERE task_id='$task_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Task deleted successfully";
    } else {
        echo "Error deleting task: " . $conn->error;
    }
} else {
    echo "No task ID provided.";
}

$conn->close();
header("Location: admin.php");
exit;
?>
