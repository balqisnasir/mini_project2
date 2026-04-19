<?php
// CONNECT DATABASE
include "config.php";

// SESSION PROTECTION
// ensure user must login before accessing this page
include "session_protect.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>User Dashboard</title>

<!-- BOOTSTRAP CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/*PAGE BACKGROUND STYLE */
body{
    background: linear-gradient(135deg,#e8f5e9,#ffffff);
    font-family: Arial;
}

/* NAVBAR STYLE */
.navbar{
    background: linear-gradient(to right,#1b5e20,#43a047);
}

/* CARD STYLE */
.card{
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* BUTTON HOVER EFFECT*/
.btn:hover{
    transform:scale(1.05);
    transition:0.2s;
}

/* HERO SECTION STYLE */
.hero-box{
    background:white;
    padding:30px;
    border-radius:20px;
    margin-top:20px;
}

</style>

</head>

<body>

<!-- NAVIGATION BAR -->
<nav class="navbar navbar-dark">
<div class="container">

    <!-- SYSTEM NAME -->
    <span class="navbar-brand fw-bold">🐘 Zoo System</span>

    <!-- NAVIGATION BUTTONS -->
    <div>
        <a href="booking.php" class="btn btn-light btn-sm">📅 Book</a>
        <a href="history.php" class="btn btn-light btn-sm">📜 History</a>
        <a href="logout.php" class="btn btn-danger btn-sm">🚪 Logout</a>
    </div>

</div>
</nav>

<!-- MAIN DASHBOARD CONTENT -->
<div class="container mt-4">

    <!-- WELCOME MESSAGE -->
    <h3>👋 Welcome, <?= $_SESSION['fullname']; ?></h3>

    <!-- HERO SECTION -->
    <div class="hero-box shadow">

        <!-- SHORT INTRO -->
        <p class="mb-3">
            🎫 You can book zoo tickets online easily through this system.
        </p>

        <p style="font-size:14px;color:gray;">
            This dashboard allows users to access booking and history features.
        </p>

        <!-- START BOOKING BUTTON -->
        <a href="booking.php" class="btn btn-success">
            🚀 Start Booking
        </a>

    </div>

    <!-- SYSTEM FEATURES SECTION -->
    <div class="card p-3 mt-4">

        <h5>📌 System Features</h5>

        <ul>
            <li>Easy online ticket booking</li>
            <li>View booking history</li>
            <li>Upload payment receipt</li>
            <li>Print ticket after approval</li>
        </ul>

    </div>

</div>

</body>
</html>