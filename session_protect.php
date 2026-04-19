<?php
session_start();

/*
Function:
- Check sama ada user dah login atau belum
- Kalau belum login, akan redirect ke login page
- Ini untuk protect page daripada akses tanpa kebenaran
*/

if(!isset($_SESSION['user_id'])){
    // Redirect ke login page jika session tiada
    header("Location: login.php");
    exit();
}
?>