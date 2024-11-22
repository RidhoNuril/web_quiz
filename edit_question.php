<?php
session_start();

if (isset($_SESSION["is_login"]) == false) {
    header("location: login.php");
    exit();
}

include 'includes/db.php'; // Pastikan path ke file db.php benar
include 'includes/functions.php';

if(isset($_POST['question_text'])){
    $id_question = isset($_POST['id_question']) ? strip_tags($_POST['id_question']) : '';
    $id_quiz = isset($_POST['id_quiz']) ? strip_tags($_POST['id_quiz']) : '';
    $question_text = isset($_POST['question_text']) ? $_POST['question_text'] : '';
    $option_a = isset($_POST['option_a']) ? strip_tags($_POST['option_a']) : '';
    $option_b = isset($_POST['option_b']) ? strip_tags($_POST['option_b']) : '';
    $option_c = isset($_POST['option_c']) ? strip_tags($_POST['option_c']) : '';
    $option_d = isset($_POST['option_d']) ? strip_tags($_POST['option_d']) : '';
    $answer = isset($_POST['answer']) ? strip_tags($_POST['answer']) : '';

    $update_question = update_question($id_question, $id_quiz, $question_text, $option_a, $option_b, $option_c, $option_d, $answer);
    echo json_encode($update_question);
    exit();
}

if (isset($_GET['id_question'])) {
    $id_question = $_GET['id_question'];

    $stmt = $db->prepare("SELECT * FROM questions WHERE id_question = ?");
    $stmt->bind_param("i", $id_question);
    $stmt->execute();
    
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();

    $option = json_decode($question['options'], true);

    if (!$question) {
        echo "<script>alert('Question tidak ditemukan!'); location.href='data_subjects.php'</script>";
        exit();
    }
} else {
    echo "Id question tidak disertakan!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Bahrul Ulum Quiz App - Edit Soal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">

</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-3">Edit Soal</h1>
        <div class="shadow p-4 rounded mb-4">
            <form action="edit_question.php" method="POST" id="form_edit_question">
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" value="<?= $question['id_question'] ?>" name="id_question">
                        <input type="hidden" value="<?= $question['id_quiz'] ?>" name="id_quiz">
                        <div class="mb-3">
                            <label for="summernote" class="form-label">Soal</label>
                            <textarea id="summernote" name="question_text" class="bg-white"><?= $question['question_text'] ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_a" class="form-label">Pilihan A</label>
                                <input id="option_a" name="option_a" value="<?= $option['options']['a'] ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_b" class="form-label">Pilihan B</label>
                                <input id="option_b" name="option_b" value="<?= $option['options']['b'] ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_c" class="form-label">Pilihan C</label>
                                <input id="option_c" name="option_c" value="<?= $option['options']['c'] ?>" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="option_d" class="form-label">Pilihan D</label>
                                <input id="option_d" name="option_d" value="<?= $option['options']['d'] ?>" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="answer" class="form-label">Jawaban yang benar</label>
                            <select class="form-select" name="answer" aria-label="Default select example">
                                <option disabled selected>Pilih jawaban yang benar</option>
                                <option value="a" <?= $option['answer'] == 'a' ? 'selected' : '' ?>>A</option>
                                <option value="b" <?= $option['answer'] == 'b' ? 'selected' : '' ?>>B</option>
                                <option value="c" <?= $option['answer'] == 'c' ? 'selected' : '' ?>>C</option>
                                <option value="d" <?= $option['answer'] == 'd' ? 'selected' : '' ?>>D</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="data_question.php?id_quiz=<?= $question['id_quiz'] ?>" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#summernote').summernote({
                placeholder: 'Isikan soal',
                tabsize: 2,
                height: 180
            });
            
            $('#form_edit_question').submit(function(e){
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
