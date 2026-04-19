<?php
include "config.php";

/* REGISTER PROCESS
   - bila user klik button register
   - data akan masuk dalam table users*/
if(isset($_POST['register'])){

    
      // GET FORM DATA
    $fullname=$_POST['fullname'];
    $username=$_POST['username'];

    /* PASSWORD HASHING
       - encrypt password untuk security
       - tak simpan password plain text*/
    $password=password_hash($_POST['password'],PASSWORD_DEFAULT);

    $phone=$_POST['phone'];

    /*  DEFAULT ROLE
       - semua user register auto jadi "user"*/
    $role="user";

    /*  INSERT INTO DATABASE
       - simpan data user baru */
    $stmt=$conn->prepare("
        INSERT INTO users(fullname,username,password,phone,role) 
        VALUES (?,?,?,?,?)
    ");

    $stmt->bind_param("sssss",$fullname,$username,$password,$phone,$role);
    $stmt->execute();

    /* SUCCESS MESSAGE
       - redirect ke login page */
    echo "<script>
        alert('Register Success');
        window.location='login.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* UI DESIGN (REGISTER PAGE)*/
body{
    background:linear-gradient(135deg,#28a745,#a8e6cf);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* CARD STYLE */
.card{
    width:400px;
    border-radius:15px;
}
</style>

</head>
<body>

<!-- REGISTER FORM UI -->
<div class="card p-4">

<h3 class="text-center">Register</h3>

<form method="POST">

<!-- FULL NAME -->
<input type="text" name="fullname" class="form-control mb-2" placeholder="Full Name" required>

<!-- USERNAME -->
<input type="text" name="username" class="form-control mb-2" placeholder="Username" required>

<!-- PHONE -->
<input type="text" name="phone" class="form-control mb-2" placeholder="Phone" required>

<!-- PASSWORD -->
<input type="password" name="password" class="form-control mb-2" placeholder="Password" required>

<!-- SUBMIT BUTTON -->
<button class="btn btn-success w-100" name="register">
    Register
</button>

</form>

</div>

</body>
</html>