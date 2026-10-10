<?php
// require_once __DIR__ . "/includes/db-connect.php";
$base = '../';
$pageTitle = "Upload Quiz";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title><?php echo $pageTitle ?> | CH Tecs Visitor Induction</title>
    <?php include('../includes/metadata.php'); ?>
    <?php include('../includes/head.php'); ?>
</head>


<body id="upload-quiz">
    <?php include('../includes/header.php'); ?>
    <?php include('../includes/navigation.php'); ?>

    <div id="selectable-content">
        <div class="container">
            <div class="invit-form">
                <form action="">
                    <div class="main-page-heading">
                        <span class="eyebrow">Quiz admin</span>
                        <h1 class="switch-dblue">Upload a Quiz Question</h1>
                        <p>Add a question and its four answer options.</p>
                    </div>

                    <div class="form-field">
                        <label for="quizName">Quiz name</label>
                        <input type="text" id="quizName" name="quizName" placeholder="Enter quiz name" required>
                    </div>

                    <div class="form-field">
                        <label for="questionNumber">Question number</label>
                        <input type="text" id="questionNumber" name="questionNumber" placeholder="Enter question number" required>
                    </div>

                    <div class="form-field">
                        <label for="quizQuestion">Quiz question</label>
                        <input type="text" id="quizQuestion" name="quizQuestion" placeholder="Enter quiz question" required>
                    </div>

                    <div class="form-field">
                        <label for="quizOption1">Quiz options</label>
                        <input type="text" id="quizOption1" name="quizOption1" placeholder="Enter option 1" required class="mb-2">
                        <input type="text" name="quizOption2" placeholder="Enter option 2" required class="mb-2" aria-label="Option 2">
                        <input type="text" name="quizOption3" placeholder="Enter option 3" required class="mb-2" aria-label="Option 3">
                        <input type="text" name="quizOption4" placeholder="Enter option 4" required aria-label="Option 4">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>
