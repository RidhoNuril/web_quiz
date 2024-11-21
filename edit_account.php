<?php
session_start();
include 'includes/db.php';

// Ambil data user berdasarkan NIS
if (isset($_GET['user_nis'])) {
    $user_nis = $_GET['user_nis'];

    // Menggunakan MySQLi untuk query
    $stmt = $db->prepare("SELECT * FROM akun_users WHERE user_nis = ?");
    $stmt->bind_param("s", $user_nis);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "<script>alert('User tidak ditemukan!'); location.href='view_account.php'</script>";
        exit();
    }
} else {
    echo "NIS tidak disertakan!";
    exit();
}

// Update data user tanpa hashing password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? $_POST['password'] : $user['password']; // Tidak melakukan hashing

    $stmt = $db->prepare("UPDATE akun_users SET username = ?, password = ? WHERE user_nis = ?");
    $stmt->bind_param("sss", $username, $password, $user_nis);
    $stmt->execute();

    header("Location: view_account.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Bahrul Ulum Quiz App - Edit Data Murid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/user_style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-3">Edit Murid</h1>
        <div class="shadow px-3 py-4 rounded">
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password (Biarkan kosong jika tidak ingin diubah)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="view_account.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
