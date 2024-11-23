<?php
session_start();

if (isset($_SESSION["is_login"]) == false) {
    header("location: login.php");
    exit();
}
if ($_SESSION["role"] != "admin") {
    header("location: dashboard.php");
    exit();
}

include 'includes/db.php';
include 'includes/functions.php';

if(isset($_POST['subject_name'])){

    $subject_name = isset($_POST['subject_name']) ? strip_tags($_POST['subject_name']) : '';
    $subject_desc = isset($_POST['subject_desc']) ? strip_tags($_POST['subject_desc']) : '';
    $thumbnail = $_FILES['thumbnail']['name'] != '' ? $_FILES['thumbnail']['name'] : '';
    $tmp_name = isset($_FILES['thumbnail']['tmp_name']) ? $_FILES['thumbnail']['tmp_name'] : '';

    
    $insert_subject = insert_subject( $subject_name, $subject_desc,$tmp_name, $thumbnail);
    echo json_encode($insert_subject);
    exit();
}


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Bahrul Ulum Quiz App - Tambah Data Subject</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="css/user_style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-3">Tambah Subject</h1>
        <div class="shadow p-4 rounded">
            <form action="add_subject.php" method="POST" id="form_tambah_subject">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="subject_name" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject_name" name="subject_name" placeholder="Masukkan nama subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea name="subject_desc" class="w-100 form-control" id="deskripsi" placeholder="Masukkan deskripsi subject" style="min-height: 200px;" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control file_thumbnail mb-3" name="thumbnail" id="image">
                            <img src="assets/image/default_thumbnail.png" alt="" class="img-fluid w-100 rounded mb-2">
                            <div class="text-center">Recommended dimention 1280x720 pixel</div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Tambah</button>
                <a href="data_subjects.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".file_thumbnail").change(function() {
                let thumb = $(this).siblings('img');
                let reader = new FileReader();
                
                reader.onload = function(e) {
                    thumb.attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#form_tambah_subject').submit(function(e){
                e.preventDefault();

                let form = $(this);
                let url = form.attr('action');
                let method = form.attr('method');
                let data = new FormData(form[0]);
                
                $.ajax({
                    url: url,
                    type: method,
                    contentType: false,
                    processData: false,
                    data: data,
                    dataType: 'JSON',

                    success: function(response){
                        if(response.status == 'success'){
                            toastr.success(response.message, 'Success !', {
                                closeButton: true,
                                progressBar: true,
                                timeOut: 1500
                            });
                            
                            setTimeout(function() {
                                if (response.redirect != "") {
                                    location.href = response.redirect;
                                } else {
                                    location.reload();
                                }
                            }, 1800);
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
</body>
</html>
