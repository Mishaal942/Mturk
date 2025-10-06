<?php
session_start();
include 'db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])){
    die("Please login first.");
}

$worker_id = $_SESSION['user_id'];

// Mark task as completed if requested
if(isset($_GET['complete_task'])){
    $app_id = intval($_GET['complete_task']);
    $stmt = $conn->prepare("UPDATE task_applications SET status='completed' WHERE id=? AND worker_id=?");
    $stmt->bind_param("ii",$app_id,$worker_id);
    $stmt->execute();
}

// Fetch all tasks applied by this worker
$stmt = $conn->prepare("
SELECT ta.id as app_id, t.title, t.description, t.payment, ta.status, ta.applied_at
FROM task_applications ta
JOIN tasks t ON t.id = ta.task_id
WHERE ta.worker_id=?
ORDER BY ta.applied_at DESC
");
$stmt->bind_param("i",$worker_id);
$stmt->execute();
$result = $stmt->get_result();

// Calculate total earnings for completed tasks
$stmt2 = $conn->prepare("
SELECT SUM(t.payment) as total_earned
FROM task_applications ta
JOIN tasks t ON t.id = ta.task_id
WHERE ta.worker_id=? AND ta.status='completed'
");
$stmt2->bind_param("i",$worker_id);
$stmt2->execute();
$total_earned = $stmt2->get_result()->fetch_assoc()['total_earned'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Worker Dashboard</title>
<style>
body{background:#0a0f14;color:white;font-family:Arial;margin:0;padding:0;}
.container{width:90%;max-width:900px;margin:40px auto;}
h1{text-align:center;color:#00eaff;text-shadow:0 0 10px #00eaff;}
.earnings{text-align:center;color:#ff00ff;font-size:18px;text-shadow:0 0 8px #ff00ff;margin-bottom:20px;}
.task-card{background:#11161c;padding:20px;margin-bottom:15px;border-radius:10px;box-shadow:0 0 15px rgba(0,255,255,0.2);}
.task-card h3{color:#00eaff;margin:0 0 10px;}
.task-card p{margin:5px 0;}
.status.pending{color:#ffff00;}
.status.completed{color:#00ff00;}
.btn-complete{padding:8px 12px;background:#00eaff;color:#000;border:none;border-radius:6px;text-decoration:none;margin-top:8px;display:inline-block;}
.btn-complete:hover{background:#ff00ff;color:white;box-shadow:0 0 10px rgba(255,0,255,0.4);}
</style>
</head>
<body>
<div class="container">
<h1>Worker Dashboard</h1>
<div class="earnings">Total Earnings: ₹<?= number_format($total_earned,2) ?></div>

<?php if($result->num_rows>0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="task-card">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <p>Payment: ₹<?= number_format($row['payment'],2) ?></p>
            <p>Status: <span class="status <?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></p>
            <p>Applied At: <?= $row['applied_at'] ?></p>
            <?php if($row['status']=='pending'): ?>
                <a href="?complete_task=<?= $row['app_id'] ?>" class="btn-complete">Mark as Completed</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No tasks applied yet.</p>
<?php endif; ?>
</div>
</body>
</html>
