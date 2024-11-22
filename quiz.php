<?php 
    session_start();
    include 'includes/functions.php';
    include 'includes/db.php';

    $id_quiz = isset($_GET['id_quiz']) ? $_GET['id_quiz'] : '';
    $count_question = count_question_quiz($id_quiz);
    $point = 100 / $count_question;

    if(isset($_POST['answer'])){
        $answer = $_POST['answer'];
        $question = $_POST['id_question'];

        $stmt = $db->prepare("SELECT options FROM questions WHERE id_question = ?");
        $stmt->bind_param("i", $question);
        $stmt->execute();
        $result = $stmt->get_result();
        $result_answer = $result->fetch_assoc();
        $correct_answer = json_decode($result_answer['options'], true);


        if($answer == $correct_answer['answer']){
            // $_SESSION['question_index'] = $_SESSION['question_index'] += 1;
            $_SESSION['score'] = $_SESSION['score'] += $point;

            $response = [
                'status' => 'true',
                'score' => $_SESSION['score'],
                'question' => $_SESSION['question_index']
            ];
        }

        echo json_encode($response);
        exit();
    }

?>
<!-- AGAMA ISLAM 01-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bahrul Ulum Quiz App - Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Agdasima:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="css/quiz_style.css">
</head>
<?php 

    $data_quiz = get_data_quiz($id_quiz);

    $question = get_question_for_quiz($id_quiz);

    if (!isset($_SESSION['question_index'])) {
        $_SESSION['question_index'] = 0;
        $_SESSION['score'] = 0;
    }
    
    $current_question = $question[$_SESSION['question_index']];
    

?>
<body>
    <header>
        <div class="header-bar header">
            <img src="assets/image/img-icon.png" alt="" class="img-fluid  rounded-circle">
            <h4 class="text">Bahrul Ulum Quiz App - <?= $data_quiz['title'] ?></h4> <!--INI DISESUAIKAN-->
        </div>
    </header>
    <div class="container mt-3 p-4">
        <div class="row">
            <div class="numberBox col-md-1 border border-dark pt-3 text-center fw-">
                <p id="number">1/<?= $count_question ?></p>
            </div>
            <div class="questionBox col-md-9 pt-md-0 py-3 px-5">
                <div class="row">
                    <div class="fw-bold mb-3">Baca Soal Dengan Seksama<?php print_r($_SESSION) ?></div>
                    <?php if($current_question['image_soal'] != null){ ?>
                        <img src="assets/image_soal/<?= $current_question['image_soal'] ?>" alt="image_soal" class="img-fluid border border-dark p-0 mb-3" id="questionImage">
                    <?php } ?>
                    <div id="question" >
                        <?= $current_question['question_text'] ?>
                    </div>
                    <div class="choice">
                        <?php foreach($current_question['options']['options'] as $key_opt => $value_opt){ ?>
                            <div class="choiceContainer my-3 d-flex border border-dark p-3" data-question="<?= $current_question['id_question'] ?>">
                                <div class="choicePrefix"><?= $key_opt ?></div>
                                <div class="choiceText mx-3 w-100 d-flex align-items-center"><?= $value_opt ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="progressBox col-md-2">
                <div class="row">
                    <div class="timeBar p-3 border border-dark fs-6 mb-3" >Waktu <span id="timeBar"></span></div>
                    <div class="scoreBar p-3 border border-dark fs-6 mb-3" >Nilai <span id="scoreBar">0</span></div>
                    <div id="progressBar"><div id="progressBarFull"></div></div>
                    <audio controls loop autoplay style="width: 100%; transform: scale(0.9); " class="pt-3" >
                        <source src="../../Assets/BGSPAI.mp3"> 
                    </audio>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.choiceContainer').click(function(){
                let answer_user = $(this).find('.choicePrefix').text();
                let id_question = $(this).data('question');

                $.ajax({
                    type: 'POST',
                    url: 'quiz.php',
                    data: {
                        answer: answer_user,
                        id_question: id_question
                    },
                    dataType: 'JSON',
                    success: function(response){
                        if(response.status == 'true'){
                            $(this).addClass('bg-primary');
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>