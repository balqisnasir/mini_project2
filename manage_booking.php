<?php
include "config.php";
session_start();

/* ADMIN ONLY */
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Booking</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(to right, #e8f5e9, #ffffff);
    font-family: Arial;
}

.card{
    border-radius:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

th{
    background:#2e7d32 !important;
    color:white;
}

.btn:hover{
    transform:scale(1.05);
    transition:0.2s;
}

#suggestions div{
    background:#f1f1f1;
    padding:8px;
    cursor:pointer;
}
#suggestions div:hover{
    background:#ddd;
}
</style>

<script>

/* LIVE SEARCH */
function searchUser(str){
    let xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.getElementById("result").innerHTML = this.responseText;
    }
    xhttp.open("GET","search_booking.php?q="+str,true);
    xhttp.send();
}

/* FILTER STATUS */
function filterStatus(status){
    let xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.getElementById("result").innerHTML = this.responseText;
    }
    xhttp.open("GET","search_booking.php?status="+status,true);
    xhttp.send();
}

/* AUTOCOMPLETE */
function autoComplete(str){

    if(str.length == 0){
        document.getElementById("suggestions").innerHTML = "";
        return;
    }

    let xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.getElementById("suggestions").innerHTML = this.responseText;
    }
    xhttp.open("GET","autocomplete.php?q="+str,true);
    xhttp.send();
}

/* fill input */
function fillInput(val){
    document.getElementById("searchBox").value = val;
    document.getElementById("suggestions").innerHTML = "";
}

</script>

</head>

<body>

<div class="container mt-4">

<div class="card p-3 text-center mb-3">
    <h3>📊 Manage Booking</h3>
</div>

<a href="admin_dashboard.php" class="btn btn-secondary mb-3">⬅ Back</a>

<!-- SEARCH -->
<input type="text" id="searchBox"
class="form-control mb-2"
placeholder="🔍 Search customer name..."
onkeyup="searchUser(this.value); autoComplete(this.value);">

<div id="suggestions"></div>

<!-- FILTER -->
<select class="form-control mb-3" onchange="filterStatus(this.value)">
    <option value="">All Status</option>
    <option value="Pending">Pending</option>
    <option value="Paid">Paid</option>
    <option value="Confirmed">Confirmed</option>
</select>

<!-- TABLE -->
<div class="card p-3">

<table class="table table-bordered text-center">
<thead>
<tr>
    <th>Name</th>
    <th>Date</th>
    <th>Status</th>
    <th>Receipt</th>
    <th>Action</th>
</tr>
</thead>

<tbody id="result">

<?php
$sql = "SELECT bookings.*, users.fullname 
FROM bookings 
JOIN users ON bookings.user_id = users.id
ORDER BY bookings.id DESC";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
?>

<tr>
<td><?= $row['fullname'] ?></td>
<td><?= $row['booking_date'] ?></td>
<td><?= $row['status'] ?></td>

<td>
<?php if($row['receipt_file']){ ?>
<a href="uploads/<?= $row['receipt_file'] ?>">View</a>
<?php } ?>
</td>

<td>
<a href="delete_booking.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
</td>

</tr>

<?php } ?>

</tbody>
</table>

</div>
</div>

</body>
</html>
