<?php
    session_start();
    
    if (isset($_SESSION["is_login"]) == false) {
        header("location: login.php");
        exit();
    }
    
    include 'includes/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Bahrul Ulum Quiz App - Lihat Data Murid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Tabel Data Murid -->
        <div class="card mt-3 overflow-auto shadow-lg rounded">
            <h5 class="card-header p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Data Murid</span>
                    <div class="d-flex">
                        <a href="admin_dashboard.php" class="btn btn-danger">Kembali</a>
                    </div>

                </div>
            </h5>
            <div class="card-body">
                <table class="table table-bordered table-striped mt-4" class="display" id="table">
                    <thead class="table-secondary">
                        <tr>
                            <th>NIS</th>
                            <th>Username</th>
                            <th>Tanggal Pembuatan Akun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $db->query("SELECT user_nis, username, created_at FROM akun_users ORDER BY created_at DESC");
                        $no = 1;
                        while ($row = $stmt->fetch_assoc()) { 
                            $no++;
                        ?>
                            <tr>
                                <td><?= $row['user_nis'] ?></td>
                                <td><?= $row['username'] ?></td>
                                <td><?= $row['created_at'] ?></td>
                                <td>
                                    <a href='edit_account.php?user_nis=<?= $row['user_nis'] ?>' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='delete_account.php?user_nis=<?=$row['user_nis'] ?>' class='btn btn-danger btn-sm' onclick='return confirm("Yakin ingin menghapus user ini?")'>Hapus</a>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        $('#table').DataTable();
    </script>
    <script src="app.js"></script>
</body>
</html>