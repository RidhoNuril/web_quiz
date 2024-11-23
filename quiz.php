<?php 
    session_start();
    if (isset($_SESSION["is_login"]) == false) {
        header("location: login.php");
        exit();
    }

    include 'includes/db.php';
    include 'includes/functions.php';
  
    $id_quiz = isset($_GET['id_quiz']) ? $_GET['id_quiz'] : '';
    $count_question = count_question_quiz($id_quiz);
    
    if (isset($_POST['answer'])) {
        $answer = $_POST['answer'];
        $question = $_POST['id_question'];
        $point = $_POST['point'];
        
        $stmt = $db->prepare("SELECT * FROM questions WHERE id_question=?");
        $stmt->bind_param("i", $question);
        $stmt->execute();
        $result = $stmt->get_result();
        $result_answer = $result->fetch_assoc();
        
        $correct_answer = json_decode($result_answer['options'], true);
        
        if ($answer == $correct_answer['answer']) {
            $_SESSION['score'] += $point;
            $_SESSION['question_index']++;
            
            $response = [
                'status' => 'true',
                'score' => $_SESSION['score'],
                'question' => $_SESSION['question_index'],
                'redirect' => ''
            ];
        } else {
            $_SESSION['question_index']++;
            $response = [
                'status' => 'false',
                'score' => $_SESSION['score'],
                'question' => $_SESSION['question_index'],
                'redirect' => ''
            ];
        }
        
        echo json_encode($response);
        exit();
    }
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bahrul Ulum Quiz App - Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/quiz_style.css">
</head>

<?php 
    $point = $count_question > 0 ? 100 / $count_question : 0;

    $data_quiz = get_data_quiz($id_quiz);
    $question = get_question_for_quiz($id_quiz);
    
    if (!isset($_SESSION['question_index'])) {
        $_SESSION['question_index'] = 0;
        $_SESSION['score'] = 0;
    }
    
    if(isset($question[$_SESSION['question_index']])){
        $current_question = $question[$_SESSION['question_index']];
    }else{
        $score_user = $db->prepare("INSERT INTO quiz_score (user_nis, id_quiz, score) VALUES (?, ?, ?)");
        $score_user->bind_param("sii",$_SESSION['user_nis'], $id_quiz, $_SESSION['score']);
        $score_user->execute();

        header('location: quiz_result.php');
    }
?>

<body>
    <header>
        <div class="header-bar header">
            <img src="assets/image/img-icon.png" alt="" class="img-fluid rounded-circle">
            <h4 class="text">Bahrul Ulum Quiz App - <?= $data_quiz['title'] ?></h4>
        </div>
    </header>

    <div class="container mt-3 p-4">
        <div class="row">
            <div class="numberBox col-md-1 border border-dark pt-3 text-center fw-">
                <p><span id="number"><?= $_SESSION['question_index'] + 1 ?></span>/<?= $count_question ?></p>
            </div>
            <div class="questionBox col-md-9 pt-md-0 py-3 px-5">
                <div class="fw-bold mb-3">Baca Soal Dengan Seksama<?php print_r($_SESSION ) ?></div>
                <?php if($current_question['image_soal'] != null){ ?>
                    <img src="assets/image_soal/<?= $current_question['image_soal'] ?>" alt="image_soal" class="img-fluid border border-dark p-0 mb-3">
                <?php } ?>
                <div id="question"><?= $current_question['question_text'] ?></div>
                <div class="choice">
                    <?php foreach($current_question['options']['options'] as $key_opt => $value_opt){ ?>
                        <div class="choiceContainer my-3 d-flex border border-dark p-3" data-question="<?= $current_question['id_question'] ?>" data-point="<?= $point ?>">
                            <div class="choicePrefix"><?= $key_opt ?></div>
                            <div class="choiceText mx-3 w-100 d-flex align-items-center"><?= $value_opt ?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="progressBox col-md-2">
                <div class="timeBar p-3 border border-dark fs-6 mb-3">Waktu <span id="timeBar"></span></div>
                <div class="scoreBar p-3 border border-dark fs-6 mb-3">Nilai <span id="scoreBar"><?= substr($_SESSION['score'], 0, 5) ?></span></div>
                <div id="progressBar"><div id="progressBarFull"></div></div>
                <audio controls loop autoplay style="width: 100%; transform: scale(0.9);" class="pt-3">
                    <source src="../../Assets/BGSPAI.mp3">
                </audio>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.choiceContainer').click(function(){
                let choice = $(this);
                let answer_user = choice.find('.choicePrefix').text();
                let id_question = choice.data('question');
                let point = choice.data('point');

                $.ajax({
                    type: 'POST',
                    url: 'quiz.php',
                    data: {
                        answer: answer_user,
                        id_question: id_question,
                        point: point
                    },
                    dataType: 'JSON',
                    success: function(response){
                        if(response.status == 'true'){
                            choice.addClass('bg-success');
                        } else {
                            choice.addClass('bg-danger');
                        }
                        setTimeout(function(){
                            if(response.redirect != ''){
                                location.href = response.redirect;
                            }else{
                                location.reload();
                            }
                        },1000)
                    }
                });
            });
        });
    </script>
</body>
</html>
