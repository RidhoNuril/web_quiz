<!-- AGAMA ISLAM 02-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bahrul Ulum Quiz App - Kuis Agama Islam 2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/quiz_style.css">
    <style>
        @media only screen and (max-width: 800px) {
            .container .backButton{
                left: 35%;
                top: 97%;
            }
        }
        @media only screen and (max-width: 768px) {
            .w-50{
                width: 100% !important;
            }
            .container .backButton{
                left: 40%;
                top: 97%;
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<?php
    session_start();
    if (isset($_SESSION["is_login"]) == false) {
        header("location: login.php");
        exit();
    }
    
?>
<body>
    <header>
        <div class="header-bar header">
            <img src="assets/image/img-icon.png" alt="" class="img-fluid  rounded-circle">
            <h4 class="text">Bahrul Ulum Quiz App - Agama Islam 2</h4>
        </div>
    </header>
    <div class="container p-4 w-50" style="min-height: 60vh; margin-top: 100px;" id="resultContainer">
        <div class="checkIcon fw-bold bg-aqua text-exercise">✓</div>
        <div class="row p-md-5 p-2 pt-5">
            <h1 class="text-center fw-bold pb-4 text-exercise">SKOR LATIHAN</h1>
            <h1 class="fw-bold w-25 text-center mx-auto p-2 text-white bg-aqua" id="scoreResult"><?= substr($_SESSION['score'], 0, 5) ?></h1>
            <p class="text-center fs-6 pt-5" id="message"></p>
        </div>
        <a class="backButton p-1 fw-bold bg-aqua text-white" href="dashboard.php">KEMBALI</a>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>
</html>