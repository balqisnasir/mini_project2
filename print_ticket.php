<?php
// START SESSION
session_start();

// CONNECT DATABASE
include "config.php";

/* GET BOOKING ID
   - ambil id dari URL */
$id = $_GET['id'];

/* FETCH BOOKING + USER DATA
   - join table bookings & users
   - untuk dapatkan nama user */
$stmt = $conn->prepare("
SELECT bookings.*, users.fullname 
FROM bookings 
JOIN users ON bookings.user_id = users.id 
WHERE bookings.id=?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

/* VALIDATION STATUS
   - hanya booking CONFIRMED boleh print tiket
   - kalau belum confirm, block akses*/
if($row['status'] != "Confirmed"){
    die("⛔ Waiting admin approval");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Zoo Ticket</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* BACKGROUND DESIGN */
body{
    background: linear-gradient(135deg,#e8f5e9,#ffffff);
    font-family: Arial;
}

/* TICKET CARD STYLE */
.ticket-card{
    max-width:500px;
    margin:auto;
    margin-top:60px;
    border-radius:20px;
    box-shadow:0 5px 20px rgba(0,0,0,0.1);
    padding:30px;
    background:white;
}

/* TITLE STYLE*/
.ticket-title{
    text-align:center;
    color:#2e7d32;
    font-weight:bold;
    margin-bottom:20px;
}

/* BUTTON HOVER EFFECT*/
.btn-print:hover{
    transform:scale(1.05);
    transition:0.2s;
}

</style>

</head>

<body>

<!-- TICKET DISPLAY UI-->
<div class="ticket-card">

    <!-- TITLE -->
    <h3 class="ticket-title">🐘 Zoo Ticket</h3>

    <!-- USER NAME -->
    <p><b>Name :</b> <?= $row['fullname'] ?></p>

    <!-- BOOKING DATE -->
    <p><b>Date :</b> <?= $row['booking_date'] ?></p>

    <!-- TOTAL PRICE -->
    <p><b>Total :</b> RM <?= $row['total_price'] ?></p>

    <hr>

    <!-- PRINT BUTTON -->
<!-- PRINT BUTTON -->
<button onclick="window.print()" class="btn btn-success w-100 btn-print">
    🖨 Print Ticket
</button>

<!-- BACK BUTTON -->
<a href="history.php" class="btn btn-secondary w-100 mt-2">
    ⬅ Back
</a>
</div>

</body>
</html>
