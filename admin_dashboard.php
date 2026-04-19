<?php
// START SESSION
session_start();

// SECURITY CHECK (ADMIN ONLY)
// check user role, hanya admin boleh masuk page ni
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: login.php");
    exit();
}

// CONNECT DATABASE
include "config.php";

// DASHBOARD STATISTICS

// kira total semua booking dalam sistem
$total_booking = $conn->query("SELECT COUNT(*) as total FROM bookings")
->fetch_assoc()['total'];

// kira booking status Pending
$pending = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE status='Pending'")
->fetch_assoc()['total'];

// kira booking status Confirmed
$confirmed = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE status='Confirmed'")
->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<!-- BOOTSTRAP CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* BACKGROUND DESIGN */
body{
    background: linear-gradient(135deg,#1b5e20,#43a047,#a5d6a7);
    min-height:100vh;
    font-family: Arial;
}

/* MAIN DASHBOARD CARD */
.dashboard-card{
    max-width:800px;
    margin:auto;
    margin-top:60px;
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 5px 20px rgba(0,0,0,0.2);
}

/* TITLE STYLE */
.title{
    text-align:center;
    color:#2e7d32;
    font-weight:bold;
}

/* STAT BOX STYLE */
.stat-box{
    border-radius:15px;
    padding:20px;
    text-align:center;
    color:white;
}

/* BUTTON HOVER EFFECT */
.btn:hover{
    transform:scale(1.05);
    transition:0.2s;
}

</style>

</head>

<body>

<!-- DASHBOARD UI START -->
<div class="dashboard-card">

    <!-- PAGE TITLE -->
    <h2 class="title">🛠 Admin Dashboard</h2>

    <!-- WELCOME MESSAGE -->
    <p class="text-center">
        Welcome, <b><?php echo $_SESSION['fullname']; ?></b> 👋
    </p>

    <hr>

    <!-- STATISTICS SECTION -->
    <div class="row text-center mb-4">

        <!-- TOTAL BOOKING -->
        <div class="col-md-4 mb-2">
            <div class="stat-box bg-success">
                <h4><?php echo $total_booking; ?></h4>
                <p>Total Booking</p>
            </div>
        </div>

        <!-- PENDING BOOKING -->
        <div class="col-md-4 mb-2">
            <div class="stat-box bg-warning text-dark">
                <h4><?php echo $pending; ?></h4>
                <p>Pending</p>
            </div>
        </div>

        <!-- CONFIRMED BOOKING -->
        <div class="col-md-4 mb-2">
            <div class="stat-box bg-primary">
                <h4><?php echo $confirmed; ?></h4>
                <p>Confirmed</p>
            </div>
        </div>

    </div>

<a href="manage_booking.php" class="btn btn-success w-100 mb-2">
    📋 Manage Booking
</a>

<a href="manage_user.php" class="btn btn-primary w-100 mb-2">
    👥 Manage User
</a>

<a href="logout.php" class="btn btn-danger w-100">
    🚪 Logout
</a>

</div>

</body>
</html>
