<?php
// CONNECT DATABASE
include "config.php";

// START SESSION
session_start();

// ADMIN SECURITY CHECK
// hanya admin dibenarkan access page ini
if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: login.php");
    exit();
}

// DELETE USER FUNCTION
// jika admin klik button delete user
if(isset($_GET['delete'])){

    // ambil user id dari URL
    $id = intval($_GET['delete']);

    // SQL delete user berdasarkan id
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i",$id);

    // execute delete query
    if($stmt->execute()){
        echo "<script>
        alert('User Deleted');
        window.location='manage_user.php';
        </script>";
    }else{
        echo "<script>
        alert('Delete Failed');
        window.location='manage_user.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage User</title>

<!-- BOOTSTRAP -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* PAGE STYLE */
body{
    background:#f5f5f5;
    font-family:Arial;
}

/* CARD STYLE */
.card{
    border-radius:15px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

</style>
</head>

<body>

<!-- MAIN CONTAINER -->
<div class="container mt-4">

    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">
        ⬅ Back to Dashboard
    </a>

    <!-- PAGE HEADER -->
    <div class="card p-3 text-center mb-3">
        <h3>👥 Manage User</h3>
    </div>

    <!-- USER TABLE CARD -->
    <div class="card p-3">

        <table class="table table-bordered text-center">

            <!-- TABLE HEADER -->
            <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>

            <?php
            // GET ALL USER (ONLY ROLE USER, NOT ADMIN)
            $result = $conn->query("SELECT * FROM users WHERE role='user'");

            // LOOP DISPLAY USER DATA
            while($row = $result->fetch_assoc()){
            ?>

            <tr>

                <!-- USER FULL NAME -->
                <td><?= htmlspecialchars($row['fullname']) ?></td>

                <!-- USERNAME -->
                <td><?= htmlspecialchars($row['username']) ?></td>

                <!-- PHONE NUMBER -->
                <td><?= htmlspecialchars($row['phone']) ?></td>

                <!-- ACTION BUTTON -->
                <td>
                    <a href="manage_user.php?delete=<?= $row['id'] ?>"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Delete user?')">
                        Delete
                    </a>
                </td>

            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>
