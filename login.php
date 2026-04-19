<?php

// LOGIN SYSTEM (USER + ADMIN)
session_start();
include "config.php";

/*
Function:
- Handle login user dan admin
- Check password dalam database
- Support "Remember Me" (auto login 7 hari)
*/

//AUTO LOGIN (REMEMBER ME)

if(isset($_COOKIE['remember_token']) && !isset($_SESSION['user_id'])){

    $token = $_COOKIE['remember_token'];

    // Cari user berdasarkan token cookie
    $stmt = $conn->prepare("SELECT * FROM users WHERE remember_token=? LIMIT 1");
    $stmt->bind_param("s",$token);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){

        $row = $result->fetch_assoc();

        // Set session user
        $_SESSION['user_id']=$row['id'];
        $_SESSION['fullname']=$row['fullname'];
        $_SESSION['role']=$row['role'];

        /*
        Redirect berdasarkan role:
        - admin → admin_dashboard.php
        - user → dashboard.php
        */
        if($row['role']=="admin"){
            header("Location: admin_dashboard.php");
        }else{
            header("Location: dashboard.php");
        }
        exit();
    }
}

//LOGIN PROCESS

$msg=""; // untuk paparkan error message

if(isset($_POST['login'])){

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check user dalam database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $row = $result->fetch_assoc();

        // Verify password (hashed password)
        if(password_verify($password,$row['password'])){

            // Set session
            $_SESSION['user_id']=$row['id'];
            $_SESSION['fullname']=$row['fullname'];
            $_SESSION['role']=$row['role'];

            //REMEMBER ME FEATURE
            if(isset($_POST['remember'])){

                // generate token random
                $token = bin2hex(random_bytes(32));

                // simpan token dalam database
                $update = $conn->prepare("UPDATE users SET remember_token=? WHERE id=?");
                $update->bind_param("si",$token,$row['id']);
                $update->execute();

                // simpan dalam cookie (7 hari)
                setcookie("remember_token",$token,time() + (7*24*60*60),"/");

            } else {

                // delete token dari database
                $update = $conn->prepare("UPDATE users SET remember_token=NULL WHERE id=?");
                $update->bind_param("i",$row['id']);
                $update->execute();

                // delete cookie
                setcookie("remember_token","",time()-3600,"/");
            }
            
              // REDIRECT ROLE
            if($row['role']=="admin"){
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();

        } else {
            $msg="Wrong Password";
        }

    } else {
        $msg="User Not Found";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Zoo Login Pro</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background: linear-gradient(135deg,#1e7e34,#28a745,#a8e6cf);
height:100vh;
display:flex;
justify-content:center;
align-items:center;
}

.card{
width:360px;
border-radius:20px;
background: rgba(255,255,255,0.95);
}

.btn:hover{
transform:scale(1.03);
transition:0.2s;
}
</style>

</head>

<body>

<div class="card p-4 shadow">

<h3 class="text-center mb-3">🐘 Zoo Login Pro</h3>

<?php if($msg!=""){ ?>
<div class="alert alert-danger text-center">
<?= $msg ?>
</div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<div class="form-check mb-3">
<input type="checkbox" name="remember" class="form-check-input">
<label class="form-check-label">Remember Me (7 days auto login)</label>
</div>

<button class="btn btn-success w-100" name="login">
Login
</button>

</form>

<br>

<p class="text-center mt-3">
    Don't have an account? 
    <a href="register.php" style="text-decoration:none; color:#28a745; font-weight:bold;">
        Register here
    </a>
</p>

</div>

</body>
</html>