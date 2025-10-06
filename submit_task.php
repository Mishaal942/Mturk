<?php
// FILE: submit_task.php
session_start();
require_once 'db.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'worker'){
    echo "<script>alert('Login kar ke submit karein');window.location='login.php'</script>";
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $task_id = intval($_POST['task_id'] ?? 0);
    $worker_id = $_SESSION['user']['id'];
    $submission_text = trim($_POST['submission_text'] ?? '');

    if(!$task_id || !$submission_text){
        echo "<script>alert('Submission text chahiye');window.location='marketplace.php';</script>";
        exit;
    }

    // check if application exists; if not create
    $ch = $pdo->prepare("SELECT * FROM applications WHERE task_id=? AND worker_id=?");
    $ch->execute([$task_id,$worker_id]);
    $app = $ch->fetch();
    if(!$app){
        $ins = $pdo->prepare("INSERT INTO applications (task_id,worker_id,status,submission_text,submitted_at) VALUES (?,?,?,?,NOW())");
        $ins->execute([$task_id,$worker_id,'submitted',$submission_text]);
    } else {
        // update submission
        $upd = $pdo->prepare("UPDATE applications SET submission_text=?, status='submitted', submitted_at=NOW() WHERE id=?");
        $upd->execute([$submission_text,$app['id']]);
    }

    // mark task status in_progress/submitted if needed
    $pdo->prepare("UPDATE tasks SET status='submitted' WHERE id=?")->execute([$task_id]);

    echo "<script>alert('Submitted successfully');window.location='worker_dashboard.php';</script>";
    exit;
}
