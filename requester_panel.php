<?php
// FILE: requester_panel.php
session_start();
require_once 'db.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'requester'){
    echo "<script>alert('Please login as requester');window.location='login.php'</script>";
    exit;
}

$rid = $_SESSION['user']['id'];

// handle approve/reject
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['app_id'])){
    $app_id = intval($_POST['app_id']);
    $action = $_POST['action'] === 'approve' ? 'approved' : 'rejected';

    // get application & task info
    $app = $pdo->prepare("SELECT a.*, t.payment, t.requester_id, a.worker_id FROM applications a JOIN tasks t ON a.task_id = t.id WHERE a.id = ?");
    $app->execute([$app_id]);
    $appRow = $app->fetch();
    if($appRow && $appRow['requester_id'] == $rid){
        $pdo->prepare("UPDATE applications SET status=? WHERE id=?")->execute([$action,$app_id]);
        if($action === 'approved'){
            // credit worker
            $pdo->prepare("INSERT INTO transactions (user_id,amount,type,reference) VALUES (?,?, 'credit', ?)")->execute([$appRow['worker_id'],$appRow['payment'],'Task#'.$appRow['task_id']]);
            // mark task completed
            $pdo->prepare("UPDATE tasks SET status='completed' WHERE id=?")->execute([$appRow['task_id']]);
        }
        echo "<script>alert('Updated');window.location='requester_panel.php';</script>";
        exit;
    } else {
        echo "<script>alert('Invalid action');window.location='requester_panel.php';</script>";
        exit;
    }
}

// fetch tasks by this requester
$tasks = $pdo->prepare("SELECT * FROM tasks WHERE requester_id = ? ORDER BY created_at DESC");
$tasks->execute([$rid]);
$tasksList = $tasks->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Requester Panel - MiniTurk</title>
<style>
body{font-family:Inter,Arial;background:#f8fafc;margin:0;color:#0f172a}
.container{max-width:1000px;margin:24px auto;padding:12px}
.card{background:white;padding:18px;border-radius:12px;box-shadow:0 8px 24px rgba(2,6,23,0.06)}
.small{font-size:13px;color:#64748b}
.table{width:100%;border-collapse:collapse;margin-top:12px}
.table th,.table td{padding:8px;border-bottom:1px solid #eef2ff;text-align:left}
.btn{padding:8px 10px;border-radius:8px;border:0;cursor:pointer}
.btn-approve{background:#10b981;color:white}
.btn-reject{background:#f97316;color:white}
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <h2>Your Tasks & Applications</h2>

    <?php if(empty($tasksList)): ?>
      <div class="small">Aap ne abhi tak koi task post nahi kiya.</div>
    <?php endif; ?>

    <?php foreach($tasksList as $t): ?>
      <div style="margin-top:12px;padding:12px;border-radius:10px;border:1px solid #eef2ff">
        <strong><?=htmlspecialchars($t['title'])?></strong> — <span class="small">₹<?=number_format($t['payment'],2)?></span>
        <div class="small">Status: <?=htmlspecialchars($t['status'])?></div>

        <?php
        $apps = $pdo->prepare("SELECT a.*, u.name as worker_name FROM applications a JOIN users u ON a.worker_id = u.id WHERE a.task_id = ? ORDER BY a.created_at DESC");
        $apps->execute([$t['id']]);
        $appsList = $apps->fetchAll();
        ?>
        <?php if(empty($appsList)): ?>
          <div class="small" style="margin-top:8px">Koi application nahi mila.</div>
        <?php else: ?>
          <table class="table" style="margin-top:8px">
            <thead><tr><th>Worker</th><th>Status</th><th>Submitted</th><th>Action</th></tr></thead>
            <tbody>
              <?php foreach($appsList as $a): ?>
                <tr>
                  <td><?=htmlspecialchars($a['worker_name'])?></td>
                  <td class="small"><?=htmlspecialchars($a['status'])?></td>
                  <td class="small"><?=htmlspecialchars($a['submitted_at'] ?? $a['created_at'])?></td>
                  <td>
                    <?php if($a['status'] === 'submitted'): ?>
                      <form method="post" style="display:inline">
                        <input type="hidden" name="app_id" value="<?=$a['id']?>">
                        <button name="action" value="approve" class="btn btn-approve">Approve</button>
                        <button name="action" value="reject" class="btn btn-reject">Reject</button>
                      </form>
                    <?php else: ?>
                      <span class="small"><?=htmlspecialchars($a['status'])?></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
