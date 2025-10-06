<?php
session_start();
include 'db.php';

$worker_id = $_SESSION['user_id'] ?? 0;
$task_id = $_GET['task_id'] ?? 0;

if($worker_id==0 || $task_id==0){
    die("Invalid request");
}

// Check if already applied
$stmt = $conn->prepare("SELECT id FROM task_applications WHERE task_id=? AND worker_id=?");
$stmt->bind_param("ii",$task_id,$worker_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows > 0){
    header("Location: dashboard.php"); // Redirect instead of blank page
    exit;
}

// Apply
$stmt2 = $conn->prepare("INSERT INTO task_applications (task_id,worker_id,status,applied_at) VALUES (?,?, 'pending', NOW())");
$stmt2->bind_param("ii",$task_id,$worker_id);
$stmt2->execute();

header("Location: dashboard.php");
exit;
?>
