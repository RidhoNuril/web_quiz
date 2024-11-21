<?php
    session_start();
    
    if (isset($_SESSION["is_login"]) == false) {
        header("location: login.php");
        exit();
    }

    include 'includes/db.php';
    include 'includes/functions.php';

    if(isset($_GET['delete_id'])){
        $delete_quiz = delete_quiz($_GET['delete_id']);
        echo json_encode($delete_quiz);
        exit();
    }
    
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Bahrul Ulum Quiz App - Lihat Data Murid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php 
        $id_subject = $_GET['id'];

        $query = $db->query("SELECT subject_name FROM subject WHERE subject_id=$id_subject");
        $result = $query->fetch_assoc();
    ?>
    <div class="container mt-5">
        <!-- Tabel Data quiz -->
        <div class="card mt-3 shadow-lg rounded mb-4">
            <h5 class="card-header p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span>Quiz <?= $result['subject_name'] ?></span>
                    <div class="d-flex">
                        <a href="add_quiz.php?id=<?= $id_subject ?>" class="btn btn-primary me-2">Tambah quiz</a>
                        <a href="data_subjects.php" class="btn btn-danger">Kembali</a>
                    </div>
                </div>
            </h5>
            <div class="card-body">
                <table class="table table-bordered table-striped mt-4" class="display" id="table">
                    <thead class="table-secondary">
                        <tr>
                            <th>Judul</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $db->query("SELECT * FROM quiz WHERE subject_id=$id_subject");
                        $no = 1;
                        while ($row = $stmt->fetch_assoc()) { 
                            $no++;
                            $date_create = date_create($row['created_at']);
                            $date_format = date_format($date_create, "d F Y, H:i");
                        ?>
                            <tr>
                                <td><?= $row['title'] ?></td>
                                <td><?= $date_format ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href='add_question.php' class='btn btn-secondary btn-sm me-1'>Tambah Soal</a>
                                        <a href='papan_peringkat.php?id=<?= $row['id_quiz'] ?>' class='btn btn-primary btn-sm me-1'>Papan Peringkat</a>
                                        <a href='edit_quiz.php?id=<?= $row['id_quiz'] ?>' class='btn btn-warning btn-sm me-1'>Edit</a>
                                        <button type="button" class="btn_delete btn btn-sm btn-danger" data-bs-toggle="modal" data-quiz="<?= $row['id_quiz'] ?>" data-bs-target="#confirm_delete">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="modal fade" id="confirm_delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Quiz</h1>
                    <button type="button" class="btn_close_delete btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah anda yakin ingin menghapus quiz?
                </div>
                <div class="modal-footer">
                    <form action="data_quiz.php" id="ajax-delete">
                        <button type="button" class="btn_close_delete btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#table').DataTable();
            
            $('.btn_delete').click(function(){
                let quiz_id = $(this).data('quiz');
                let row = $(this).closest('tr');
                let form_delete = $('#ajax-delete');

                form_delete.attr('action','data_quiz.php?delete_id='+quiz_id);
                form_delete.data('row',row);
            });

            $('.btn_close_delete').click(function(){
                let form_delete = $('#ajax-delete');

                form_delete.attr('action','data_quiz.php');
            });

            $('#ajax-delete').submit(function(e){
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');
                let row = form.data('row');
                
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'JSON',
                    beforeSend: function(){
                        $('#confirm_delete').modal('hide');
                    },
                    success: function(response){
                        if(response.status == 'success'){
                            row.remove();

                            toastr.success(response.message, 'Success !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });

                        }else{
                            toastr.error(response.message, 'Failed !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });
                        }
                    },
                });

            });
            
        });
    </script>
    <script src="app.js"></script>
</body>
</html>