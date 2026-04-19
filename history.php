<?php
session_start();
include "config.php";

/* SECURITY CHECK
   - pastikan user login dulu
   - kalau tak login, redirect ke login page */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

/* GET USER ID
   - ambil id user dari session */
$user = $_SESSION['user_id'];

/* FETCH BOOKING DATA
   - ambil semua booking user dari database
   - susun ikut tarikh terbaru*/
$stmt = $conn->prepare("
    SELECT * FROM bookings 
    WHERE user_id=? 
    ORDER BY booking_date DESC
");
$stmt->bind_param("i",$user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking History</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
/* BACKGROUND DESIGN */
body{
    background: linear-gradient(to right, #e8f5e9, #ffffff);
    font-family: Arial;
}

/* CARD STYLE */
.card{
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* TABLE HEADER STYLE */
th{
    background:#2e7d32;
    color:white;
}

/* ROW HOVER EFFECT */
tr:hover{
    background:#f1f1f1;
}

/* BUTTON EFFECT */
.btn:hover{
    transform:scale(1.05);
    transition:0.2s;
}
</style>

</head>

<body>

<div class="container mt-5">
<div class="card p-4">

<h3>📜 My Booking History</h3>

<!-- BACK BUTTON -->
<a href="dashboard.php" class="btn btn-secondary mb-2">
    ⬅ Back
</a>

<!-- logout button -->
<a href="logout.php" class="btn btn-danger mb-3">Logout</a>
<table class="table table-bordered text-center">

<tr>
    <th>Date</th>
    <th>Category</th>
    <th>Qty</th>
    <th>Total</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php 
/* IF NO DATA */
if($result->num_rows == 0){ ?>
<tr><td colspan="6">No bookings yet</td></tr>

<?php } else { ?>

<?php 
/* LOOP DATA BOOKING*/
while($row = $result->fetch_assoc()){ ?>

<tr>

<td><?= htmlspecialchars($row['booking_date']) ?></td>

<!-- TICKET CATEGORY -->
<td><?= htmlspecialchars($row['ticket_category']) ?></td>

<!-- QUANTITY -->
<td><?= htmlspecialchars($row['quantity']) ?></td>

<!-- TOTAL PRICE -->
<td>RM <?= number_format($row['total_price'],2) ?></td>

<!-- STATUS DISPLAY -->
<td>
<?php if($row['status']=="Pending"){ ?>
    <span class="badge bg-warning text-dark">Pending</span>

<?php } elseif($row['status']=="Paid"){ ?>
    <span class="badge bg-info">Paid</span>

<?php } else { ?>
    <span class="badge bg-success">Confirmed</span>
<?php } ?>
</td>

<!-- ACTION BUTTON -->
<td>

<a href="delete_booking.php?id=<?= $row['id']; ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure you want to delete this booking?')">
   Delete
</a>

<a href="edit_booking.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

<?php if($row['status']=="Pending"){ ?>
    <a href="upload_receipt.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm">Upload</a>
<?php } ?>

<?php if($row['status']=="Confirmed"){ ?>
    <a href="print_ticket.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">Print</a>
<?php } ?>

</td>

</tr>

<?php } } ?>

</table>

</div>
</div>

</body>
</html>