<?php
$conn = new mysqli("localhost","root","","zoo_ticket");

if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>