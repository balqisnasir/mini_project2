<?php
session_start();
include "config.php";

/* SECURITY CHECK
   - user wajib login */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/* CHECK ID BOOKING
   - pastikan ada id dari URL */
if(!isset($_GET['id'])){
    die("Invalid request");
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

/* FETCH BOOKING DATA
   - ambil data booking ikut user
   - elak user edit booking orang lain */
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id=? AND user_id=?");
$stmt->bind_param("ii",$id,$user_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

/* CHECK DATA EXISTS */
if(!$data){
    die("Not found");
}

/* RULE: 1 DAY LIMIT
   - tak boleh edit kalau kurang 1 hari sebelum tarikh booking */
if(strtotime($data['booking_date']) <= strtotime("+1 day")){

    echo "<script>
        alert('❌ Cannot edit booking less than 1 day before the booking date!');
        window.location='history.php';
    </script>";
    exit();
}

/* EXTRACT OLD VALUE
   - ambil jumlah adult & child dari string ticket_category */
preg_match('/Adult:(\d+)/', $data['ticket_category'], $a);
preg_match('/Child:(\d+)/', $data['ticket_category'], $c);

$adult_old = $a[1] ?? 0;
$child_old = $c[1] ?? 0;

/* PRICE SETTING */
$prices = ["Adult"=>30,"Child"=>15];

/* FUNCTION TOTAL PRICE */
function total($a,$c,$p){
    return ($a*$p['Adult']) + ($c*$p['Child']);
}

/* UPDATE BOOKING */
if(isset($_POST['update'])){

    $adult = intval($_POST['adult_qty']);
    $child = intval($_POST['child_qty']);
    $date = $_POST['booking_date'];

    $total = total($adult,$child,$prices);
    $category = "Adult:$adult Child:$child";
    $qty = $adult + $child;

    $update = $conn->prepare("
        UPDATE bookings 
        SET ticket_category=?, quantity=?, booking_date=?, total_price=? 
        WHERE id=? AND user_id=?
    ");

    $update->bind_param("sisiii",
        $category,$qty,$date,$total,$id,$user_id
    );

    $update->execute();

    echo "<script>
        alert('Updated Successfully');
        window.location='history.php';
    </script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Booking</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#e8f5e9,#ffffff);
    font-family: 'Segoe UI', sans-serif;
}

.wrapper{
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.card{
    width:420px;
    border:none;
    border-radius:20px;
    box-shadow:0 8px 25px rgba(0,0,0,0.1);
    padding:30px;
    background:white;
    transition:0.3s;
}

.card:hover{
    transform: translateY(-3px);
}

h3{
    color:#2e7d32;
    font-weight:bold;
}

input{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border-radius:12px;
    border:1px solid #ddd;
    outline:none;
}

input:focus{
    border-color:#28a745;
    box-shadow:0 0 5px rgba(40,167,69,0.3);
}

.btn{
    border-radius:12px;
    padding:10px;
    transition:0.2s;
}

.btn:hover{
    transform:scale(1.05);
}

label{
    font-weight:600;
    margin-top:10px;
}
</style>

</head>

<body>

<div class="wrapper">
<div class="card">

<h3 class="text-center">✏️ Edit Booking</h3>
<p class="text-center text-muted">Update your ticket details</p>

<form method="POST">

<label>Adult Ticket</label>
<!-- FIX: tambah ">" yang hilang -->
<input type="number" name="adult_qty" value="<?= $adult_old ?>" min="0">

<label>Child Ticket</label>
<input type="number" name="child_qty" value="<?= $child_old ?>" min="0">

<label>Booking Date</label>
<input type="date" name="booking_date" value="<?= $data['booking_date'] ?>">

<button class="btn btn-success w-100 mt-2" name="update">
💾 Update Booking
</button>

<a href="history.php" class="btn btn-secondary w-100 mt-2">
⬅ Back
</a>

</form>

</div>
</div>

</body>
</html>