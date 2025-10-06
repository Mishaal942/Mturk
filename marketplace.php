<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'worker'; // default worker

// Fetch tasks
$result = $conn->query("
    SELECT t.id, t.title, t.description, t.payment, u.name as requester_name
    FROM tasks t
    JOIN users u ON t.requester_id = u.id
    ORDER BY t.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Marketplace - MTurk Clone</title>
<style>
body{background:#0a0f14;color:white;font-family:Arial;margin:0;padding:0;}
.container{width:90%;max-width:900px;margin:40px auto;}
h1{text-align:center;color:#00eaff;text-shadow:0 0 10px #00eaff;margin-bottom:20px;}
.task-card{background:#11161c;padding:20px;margin-bottom:15px;border-radius:10px;box-shadow:0 0 15px rgba(0,255,255,0.2);}
.task-card h3{color:#00eaff;margin:0 0 10px;}
.task-card p{margin:5px 0;}
.btn-apply, .btn-post{padding:8px 12px;background:#00eaff;color:#000;border:none;border-radius:6px;text-decoration:none;margin-top:8px;display:inline-block;}
.btn-apply:hover, .btn-post:hover{background:#ff00ff;color:white;box-shadow:0 0 10px rgba(255,0,255,0.4);}
.top-bar{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
.top-bar a{color:#00eaff;text-decoration:none;font-weight:bold;}
.top-bar a:hover{text-decoration:underline;}
</style>
</head>
<body>
<div class="container">

<div class="top-bar">
<h1>Marketplace</h1>
<?php if($role==='requester'): ?>
    <a href="post_task.php" class="btn-post">+ Post a Task</a>
<?php endif; ?>
</div>

<?php if($result->num_rows>0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="task-card">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= htmlspecialchars($row['description']) ?></p>
            <p>Payment: ₹<?= number_format($row['payment'],2) ?></p>
            <p>Posted by: <?= htmlspecialchars($row['requester_name']) ?></p>
            <?php if($role==='worker'): ?>
                <a href="apply_task.php?task_id=<?= $row['id'] ?>" class="btn-apply">Apply</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No tasks available yet.</p>
<?php endif; ?>

</div>
</body>
</html>
