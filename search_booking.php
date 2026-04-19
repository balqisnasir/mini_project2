<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

/* ADMIN CAN DELETE ALL */
if($role == "admin"){

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();

    header("Location: manage_booking.php");
    exit();

/* USER ONLY DELETE OWN DATA */
}else{

    $stmt = $conn->prepare("DELETE FROM bookings WHERE id=? AND user_id=?");
    $stmt->bind_param("ii",$id,$user_id);
    $stmt->execute();

    header("Location: history.php");
    exit();
}
?>
