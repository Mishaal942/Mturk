<?php
session_start();
include 'db.php';

$message = '';

if(isset($_POST['signup'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows>0){
        $message = "Email already registered! Please login.";
    } else {
        $stmt2 = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        $stmt2->bind_param("ssss",$name,$email,$password,$role);
        $stmt2->execute();
        // Signup successful, redirect to login
        $_SESSION['user_id'] = $stmt2->insert_id;
        $_SESSION['role'] = $role;
        header("Location: login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Signup - MTurk Clone</title>
<style>
body{margin:0;padding:0;font-family:Arial;background:#0a0f14;display:flex;justify-content:center;align-items:center;height:100vh;color:white;}
.container{background: rgba(10,15,20,0.9);padding:40px;border-radius:15px;box-shadow:0 0 20px rgba(0,255,255,0.5);width:350px;text-align:center;}
h1{color:#00eaff;text-shadow:0 0 10px #00eaff;margin-bottom:30px;}
input[type=text], input[type=email], input[type=password], select{width:100%;padding:12px;margin:10px 0;border:none;border-radius:8px;background:#11161c;color:white;box-shadow:0 0 10px rgba(0,255,255,0.3);}
input[type=submit]{background:#00eaff;color:#000;padding:12px;width:100%;border:none;border-radius:10px;font-weight:bold;cursor:pointer;margin-top:15px;text-shadow:0 0 5px #000;}
input[type=submit]:hover{background:#ff00ff;color:white;box-shadow:0 0 15px #ff00ff;}
select{cursor:pointer;}
a{color:#00eaff;text-decoration:none;}
a:hover{text-decoration:underline;}
.message{color:#00ff00;margin-bottom:15px;text-shadow:0 0 8px #00ff00;}
</style>
</head>
<body>
<div class="container">
<h1>Sign Up</h1>

<?php if($message != ''): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<form method="post">
<input type="text" name="name" placeholder="Enter your name" required>
<input type="email" name="email" placeholder="Enter your email" required>
<input type="password" name="password" placeholder="Enter your password" required>
<select name="role" required>
    <option value="worker">Worker</option>
    <option value="requester">Requester</option>
</select>
<input type="submit" name="signup" value="Sign Up">
<p style="margin-top:15px;">Already have an account? <a href="login.php">Login</a></p>
</form>
</div>
</body>
</html>
