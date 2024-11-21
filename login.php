<?php
include "includes/db.php";
session_start();

if (isset($_SESSION["is_login"])) {
    if ($_SESSION["username"] == "admin") {
        header("location: admin_dashboard.php");
        exit();
    } else{
        header("location: users_dashboard.php");
        exit();
    }
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Variabel untuk notifikasi
    $notification = "";

    // Cek apakah username ada di tabel admin
    $sqlAdmin = "SELECT * FROM akun_admins WHERE username = '$username' AND password = '$password'";
    $resultAdmin = $db->query($sqlAdmin);

    if ($resultAdmin->num_rows > 0) {
        // Jika ditemukan di tabel admin
        $dataAdmin = $resultAdmin->fetch_assoc();
        $_SESSION["username"] = $dataAdmin['username'];
        $_SESSION["is_login"] = true;
        header("location: admin_dashboard.php");
        exit();
    } else {
        // Jika tidak ditemukan di tabel admin, cek tabel user
        $sqlUser = "SELECT * FROM akun_users WHERE username = '$username' AND password = '$password'";
        $resultUser = $db->query($sqlUser);

        if ($resultUser->num_rows > 0) {
            // Jika ditemukan di tabel user
            $dataUser = $resultUser->fetch_assoc();
            $_SESSION["username"] = $dataUser['username'];
            $_SESSION["is_login"] = true;
            header("location: users_dashboard.php");
            exit();
        } else {
            // Jika tidak ditemukan di kedua tabel
            $notification = "Akun tidak ditemukan";
        }
    }
    $db->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Bahrul Ulum Quiz App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="px-3">
    <main>
        <div class="container mt-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4 shadow-lg rounded d-flex flex-column align-items-center justify-content-center p-4">
                    <img src="assets/image/img-icon.png" alt="" class="img-fluid rounded-circle mb-3 d-flex justify-content-center align-items-center" style="width: 200px;">
                    <form action="login.php" method="POST" class="w-100">
                        <h2 class="text-center">Login</h2>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" placeholder="Username" required class="w-100 form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" placeholder="Password" required class="w-100 form-control">
                        </div>
                        <p><?php 
                            if (isset($notification)) {
                                echo $notification; 
                            }?>
                        </p>
                        <button type="submit" name="login" class="btn btn-danger w-100 py-3 mt-3">Login</button>
                    </form>
                </div>
            </div>    
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<html>
