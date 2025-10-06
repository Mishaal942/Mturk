<?php
// FILE: worker_dashboard.php
session_start();
require_once 'db.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'worker'){
    echo "<script>alert('Please login as worker');window.location='login.php'</script>";
    exit;
}

$uid = $_SESSION['user']['id'];

// fetch applications for this worker
$stmt = $pdo->prepare("SELECT a.*, t.title, t.payment, u.name as requester_name FROM applications a JOIN tasks t ON a.task_id = t.id JOIN users u ON t.requester_id = u.id WHERE a.worker_id = ? ORDER BY a.created_at DESC");
$stmt->execute([$uid]);
$apps = $stmt->fetchAll();

// earnings summary (sum of credits)
$sum = $pdo->prepare("SELECT COALESCE(SUM(amount),0) as total FROM transactions WHERE user_id = ? AND type = 'credit'");
$sum->execute([$uid]);
$total = $sum->fetchColumn();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Dashboard - MiniTurk</title>
<style>
body{font-family:Inter,Arial;background:#f8fafc;margin:0;color:#0f172a}
.container{max-width:980px;margin:24px auto;padding:12px}
.card{background:white;padding:18px;border-radius:12px;box-shadow:0 8px 24px rgba(2,6,23,0.06)}
.row{display:flex;gap:12px;align-items:center}
.small{font-size:13px;color:#64748b}
.table{width:100%;border-collapse:collapse;margin-top:12px}
.table th,.table td{padding:10px;border-bottom:1px solid #eef2ff;text-align:left}
.badge{padding:6px 8px;border-radius:8px;background:#eef2ff}
.btn{padding:8px 10px;border-radius:8px;border:0;cursor:pointer}
.btn-request{background:#0ea5a4;color:white}
</style>
</head>
<body>
<div class="container">
  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center">
      <h2>Your Dashboard</h2>
      <div>
        <strong>Total Earnings: ₹<?=number_format($total,2)?></strong>
      </div>
    </div>

    <div style="margin-top:12px">
      <table class="table">
        <thead>
          <tr><th>Task</th><th>Requester</th><th>Payment</th><th>Status</th><th>Submitted At</th><th>Action</th></tr>
        </thead>
        <tbody>
          <?php if(empty($apps)): ?>
            <tr><td colspan="6" class="small">Koi task nahi mila ya submit nahi kiya.</td></tr>
          <?php endif; ?>
          <?php foreach($apps as $a): ?>
            <tr>
              <td><?=htmlspecialchars($a['title'])?></td>
              <td class="small"><?=htmlspecialchars($a['requester_name'])?></td>
              <td>₹<?=number_format($a['payment'],2)?></td>
              <td><span class="badge"><?=htmlspecialchars($a['status'])?></span></td>
              <td class="small"><?=htmlspecialchars($a['submitted_at'] ?? $a['created_at'])?></td>
              <td>
                <?php if($a['status'] === 'approved'): ?>
                  <span class="small">Paid</span>
                <?php elseif($a['status'] === 'submitted'): ?>
                  <span class="small">Waiting for approval</span>
                <?php else: ?>
                  <span class="small"><?=htmlspecialchars($a['status'])?></span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="margin-top:12px">
        <button class="btn btn-request" onclick="requestWithdraw()">Request Withdrawal</button>
      </div>
    </div>
  </div>
</div>

<script>
function requestWithdraw(){
  alert('Withdrawal request sent (demo). Aap ke transactions admin se handle honge.');
}
</script>
</body>
</html>
