<?php
session_start();
include 'db.php';

$message = '';

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id,password,role FROM users WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows>0){
        $user = $result->fetch_assoc();
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: marketplace.php");
            exit;
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - MTurk Clone</title>
<style>
body{margin:0;padding:0;font-family:Arial;background:#0a0f14;display:flex;justify-content:center;align-items:center;height:100vh;color:white;}
.container{background: rgba(10,15,20,0.9);padding:40px;border-radius:15px;box-shadow:0 0 20px rgba(0,255,255,0.5);width:350px;text-align:center;}
h1{color:#00eaff;text-shadow:0 0 10px #00eaff;margin-bottom:30px;}
input[type=email], input[type=password]{width:100%;padding:12px;margin:10px 0;border:none;border-radius:8px;background:#11161c;color:white;box-shadow:0 0 10px rgba(0,255,255,0.3);}
input[type=submit]{background:#00eaff;color:#000;padding:12px;width:100%;border:none;border-radius:10px;font-weight:bold;cursor:pointer;margin-top:15px;text-shadow:0 0 5px #000;}
input[type=submit]:hover{background:#ff00ff;color:white;box-shadow:0 0 15px #ff00ff;}
a{color:#00eaff;text-decoration:none;}
a:hover{text-decoration:underline;}
.message{color:#ff00ff;margin-bottom:15px;text-shadow:0 0 8px #ff00ff;}
</style>
</head>
<body>
<div class="container">
<h1>Login</h1>

<?php if($message != ''): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<form method="post">
<input type="email" name="email" placeholder="Enter your email" required>
<input type="password" name="password" placeholder="Enter your password" required>
<input type="submit" name="login" value="Login">
<p style="margin-top:15px;">Don't have an account? <a href="signup.php">Sign Up</a></p>
</form>
</div>
</body>
</html>
