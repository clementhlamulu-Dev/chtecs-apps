<?php
// require_once __DIR__ . "/includes/db-connect.php";
?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Company Name Results type for the year ended Day&nbsp;Month&nbsp;Year |
        <?php echo $pageTitle ?>
    </title>
    <?php include('../includes/metadata.php'); ?>
    <?php include('../includes/head.php'); ?>
    <link rel="stylesheet" href="../css/app-styles.css">
</head>


<body id="<SECTIONSHORT>">
    <?php include('../includes/header.php'); ?>
    <?php include('../includes/navigation.php'); ?>

    <?php include('../includes/breadcrumb.php'); ?>
    <div id="selectable-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <div class="main-page-heading">
                        <h1 class="switch-red">
                            Admin Dashboard
                        </h1>
                    </div>



                </div>



                <div class="col-lg-12">
                    <form action="">
                        <label for="quiz-file">Quiz name</label>
                        <input type="text" name="quizName" placeholder="Enter quiz name" required>

                        <label for="quiz-file">Question Number</label>
                        <input type="text" name="questionNumber" placeholder="Enter question number" required>

                        <label for="quiz-file">Quiz Question</label>
                        <input type="text" name="quizQuestion" placeholder="Enter quiz question" required>

                        <label for="quiz-file">Quiz Options</label>
                        <input type="text" name="quizOption1" placeholder="Enter option 1" required>
                        <input type="text" name="quizOption2" placeholder="Enter option 2  " required>
                        <input type="text" name="quizOption3" placeholder="Enter option 3" required>
                        <input type="text" name="quizOption4" placeholder="Enter option 4" required>
                    </form>


                </div>






            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>
</body>

</html>