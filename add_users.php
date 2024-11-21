<?php
    include "includes/db.php";
    session_start();
    if (isset($_SESSION["is_login"]) == false) {
        header("location: login.php");
        exit();
    }
    $message = "";
    if (isset($_POST['buat'])) {
        $user_nis = $_POST['user_nis'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        try{
            $sql = "INSERT INTO akun_users (user_nis,username,password) VALUES('$user_nis','$username', '$password')";
            if ($db->query($sql)) {
                $message = "Akun berhasil didaftarkan";
            } else{
                $message = "Akun gagal didaftarkan";
            }
        } catch (mysqli_sql_exception) {
            $message = "Username sudah digunakan";
        }
        $db->close();
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Bahrul Ulum Quiz App - Tambah Data Murid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/user_style.css">
</head>
<body class="d-flex align-items-center p-3" style="min-height: 100vh; height: 100%;">
    <div class="container shadow-lg">
        <div class="row py-4 px-3">
            <div class="col-md-6 col-12 d-flex justify-content-center align-items-center">
                <img src="assets/image/img-icon.png" alt="" class="img-form-add-user img-fluid rounded-circle" style="width: 300px;">
            </div>
            <div class="col-md-6 col-12 index-card d-flex flex-column align-items-center justify-content-center p-3">
                <form action="add_users.php" method="POST" class="w-100">
                    <h2 class="text-center mb-3">Tambah User</h2>
                    <div class="mb-3">
                        <label class="form-label">User NIS</label>
                        <input type="text" name="user_nis" class="form-control w-100" placeholder="User NIS" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control w-100" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control w-100" placeholder="Password" required>
                    </div>
                    <p><?php 
                        if (isset($message)) {
                            echo $message; 
                        }?>
                    </p>
                    <button type="submit" name="buat" class="btn btn-danger w-100 py-2 mb-4">Tambah</button>
                </form>
                <a href="admin_dashboard.php" class="btn btn-danger w-75 py-2">Kembali</a>
            </div>
        </div>    
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="app.js"></script>
</body>
</html>