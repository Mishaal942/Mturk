<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MTURK Clone - Home</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: radial-gradient(circle at top, #0f172a, #000000);
            color: #ffffff;
        }

        .header {
            padding: 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #00eaff;
            text-shadow: 0 0 15px #00eaff;
            background: #0a0f14;
            box-shadow: 0 0 15px rgba(0, 234, 255, 0.2);
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 50px auto;
            text-align: center;
        }

        .intro {
            font-size: 20px;
            margin-bottom: 30px;
            line-height: 1.5;
            color: #d1d5db;
        }

        .btn-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .btn {
            background: #00eaff;
            color: #000;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
            box-shadow: 0 0 12px rgba(0, 234, 255, 0.4);
        }

        .btn:hover {
            background: #ff00ff;
            color: #fff;
            box-shadow: 0 0 15px rgba(255, 0, 255, 0.5);
        }

        .features {
            margin-top: 50px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .card {
            background: #11161c;
            padding: 20px;
            border-radius: 12px;
            text-align: left;
            box-shadow: 0 0 10px rgba(0, 234, 255, 0.1);
            transition: 0.3s;
        }

        .card:hover {
            box-shadow: 0 0 15px rgba(0, 234, 255, 0.4);
            transform: scale(1.02);
        }

        .card h3 {
            color: #00eaff;
            margin-top: 0;
            text-shadow: 0 0 10px #00eaff;
        }

        .footer {
            text-align: center;
            padding: 20px;
            margin-top: 50px;
            font-size: 14px;
            color: #888;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>

    <div class="header">MTURK CLONE PLATFORM</div>

    <div class="container">
        <div class="intro">
            Earn money by completing micro-tasks or post tasks to get work done.  
            Choose your role and get started with a seamless experience.
        </div>

        <div class="btn-group">
            <a href="signup.php" class="btn">Sign Up</a>
            <a href="login.php" class="btn">Login</a>
            <a href="marketplace.php" class="btn">Browse Tasks</a>
        </div>

        <div class="features">
            <div class="card">
                <h3>For Workers</h3>
                <p>Choose from a variety of paid micro-jobs like surveys, transcription, and data entry.</p>
            </div>
            <div class="card">
                <h3>For Requesters</h3>
                <p>Post tasks easily and get them completed quickly by qualified workers.</p>
            </div>
            <div class="card">
                <h3>Earnings Dashboard</h3>
                <p>Track your completed tasks, progress, and available balance in one place.</p>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?= date("Y") ?> MTURK Clone. All Rights Reserved.
    </div>

</body>
</html>
