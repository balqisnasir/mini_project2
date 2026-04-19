<?php
include "config.php";
session_start();

/* SECURITY CHECK */
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

/* GET BOOKING DATA (HARGA + DETAILS) */
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if(!$booking){
    die("Booking not found");
}

/* UPLOAD PROCESS */
if(isset($_POST['upload'])){

    $file = $_FILES['receipt']['name'];
    $tmp = $_FILES['receipt']['tmp_name'];
    $size = $_FILES['receipt']['size'];

    /* FILE EXTENSION */
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $allowed = ['jpg','png','pdf'];

    if(!in_array($ext,$allowed)){
        echo "<script>
            alert('❌ Invalid file type! Only JPG, PNG, PDF allowed.');
            window.location='upload_receipt.php?id=$id';
        </script>";
        exit();
    }

    /* SIZE CHECK (2MB) */
    if($size > 2000000){
        echo "<script>
            alert('❌ File too large! Maximum 2MB only.');
            window.location='upload_receipt.php?id=$id';
        </script>";
        exit();
    }

    /* CREATE UPLOAD FOLDER */
    if(!is_dir("uploads")){
        mkdir("uploads");
    }

    /* UNIQUE FILE NAME */
    $newfile = time()."_".uniqid().".".$ext;
    $uploadPath = "uploads/".$newfile;

    /* MOVE FILE */
    if(move_uploaded_file($tmp,$uploadPath)){

        $stmt = $conn->prepare("
            UPDATE bookings 
            SET receipt_file=?, status='Paid' 
            WHERE id=? AND user_id=? AND status='Pending'
        ");

        $stmt->bind_param("sii",$newfile,$id,$user_id);
        $stmt->execute();

        echo "<script>
            alert('✅ Upload Successful!');
            window.location='history.php';
        </script>";

    } else {
        echo "<script>
            alert('❌ Upload Failed!');
            window.location='upload_receipt.php?id=$id';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Receipt</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(to right, #e8f5e9, #ffffff);
    font-family: Arial;
}

.card{
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    max-width:500px;
    margin:auto;
    margin-top:80px;
    padding:30px;
}

.btn:hover{
    transform:scale(1.05);
    transition:0.2s;
}
</style>

</head>

<body>

<div class="card">

<h3 class="text-center mb-3">📤 Upload Receipt</h3>

<!-- BOOKING INFO -->
<div class="alert alert-success">
    💰 Total Price: <b>RM <?= $booking['total_price'] ?></b><br>
    📅 Booking Date: <b><?= $booking['booking_date'] ?></b>
</div>

<form method="POST" enctype="multipart/form-data">

    <!-- FILE INPUT -->
    <input type="file" name="receipt" class="form-control mb-3" required>

    <!-- INFO -->
    <div class="alert alert-info">
        📌 Allowed file types: JPG, PNG, PDF<br>
        📌 Maximum file size: 2MB
    </div>

    <!-- BUTTON -->
    <button name="upload" class="btn btn-success w-100">
        Upload Receipt
    </button>

    <!-- BACK -->
    <a href="history.php" class="btn btn-secondary w-100 mt-2">
        ⬅ Back
    </a>

</form>

</div>

</body>
</html>