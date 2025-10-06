<?php
include 'db.php';

// ✅ For testing: Hardcoded requester_id
$requester_id = 1; // Make sure user with id=1 exists in users table
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = trim($_POST['category']);
    $payment = trim($_POST['payment']);
    $deadline = trim($_POST['deadline']);

    if (!empty($title) && !empty($description) && !empty($category) && !empty($payment)) {
        $stmt = $conn->prepare("INSERT INTO tasks (requester_id, title, description, category, payment, deadline) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssds", $requester_id, $title, $description, $category, $payment, $deadline);
        if ($stmt->execute()) {
            $message = "✅ Task posted successfully!";
        } else {
            $message = "❌ Error posting task: " . $stmt->error;
        }
    } else {
        $message = "⚠ Please fill all required fields.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Post a Task</title>
    <style>
        body {
            background: #0a0f14;
            font-family: Arial, sans-serif;
            color: white;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            background: #11161c;
            padding: 30px;
            border-radius: 10px;
            width: 420px;
            box-shadow: 0 0 20px rgba(0,255,255,0.3);
        }
        h2 {
            text-align: center;
            color: #00eaff;
            text-shadow: 0 0 10px #00eaff;
            margin-bottom: 20px;
        }
        label { font-size: 14px; margin-bottom: 5px; display: block; }
        input, textarea, select {
            width: 100%; padding: 12px; margin-bottom: 15px;
            border: none; border-radius: 6px; background: #141a20;
            color: white; outline: none; box-shadow: 0 0 10px rgba(0,255,255,0.15);
        }
        button {
            width: 100%; padding: 12px; background: #00eaff;
            border: none; border-radius: 6px; color: #000;
            font-weight: bold; cursor: pointer; transition: 0.3s;
        }
        button:hover {
            background: #ff00ff; color: white;
            box-shadow: 0 0 15px rgba(255,0,255,0.4);
        }
        .message {
            text-align: center; margin-bottom: 10px; font-weight: bold;
        }
        .back-link {
            text-align: center; display: block; margin-top: 10px;
            color: #00eaff; text-decoration: none;
        }
        .back-link:hover { color: #ff00ff; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Post a Task</h2>

    <?php if (!empty($message)): ?>
        <div class="message"><?= $message; ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Title</label>
        <input type="text" name="title" placeholder="Enter task title" required>

        <label>Description</label>
        <textarea name="description" rows="3" placeholder="Enter task details" required></textarea>

        <label>Category</label>
        <select name="category" required>
            <option value="Data Entry">Data Entry</option>
            <option value="Surveys">Surveys</option>
            <option value="Transcription">Transcription</option>
            <option value="Writing">Writing</option>
        </select>

        <label>Payment (₹)</label>
        <input type="number" name="payment" placeholder="Enter amount" required>

        <label>Deadline (Optional)</label>
        <input type="date" name="deadline">

        <button type="submit">Post Task</button>
    </form>

    <a href="marketplace.php" class="back-link">← Back to Marketplace</a>
</div>
</body>
</html>
