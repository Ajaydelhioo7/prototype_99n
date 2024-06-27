<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
require 'db_connect.php';

// Get the user's globalRating
$user_id = $_SESSION['user_id'];
$sql = "SELECT globalRating FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($globalRating);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="styles.css"> <!-- Link the new CSS file -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div id="branding">
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></h1>
                <h2>Your Global Rating: <span id="globalRating"><?php echo number_format($globalRating, 2); ?></span></h2>
            </div>
            <nav>
                <ul>
                    <li><a href="logout.php" class="logout">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <?php include('./header.php')?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="content-section">
                    <h2>History</h2>
                    <p>History is the study of past events, particularly in human affairs. It is an umbrella term that relates to past events as well as the memory, discovery, collection, organization, presentation, and interpretation of information about these events.</p>
                    <p>Historians seek to understand and interpret past events to better comprehend the present and anticipate future developments. History encompasses a wide range of topics including political history, social history, cultural history, economic history, and more.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dashboard-content">
                    <div class="form-group">
                        <label for="tagFilter">Select Tag:</label>
                        <select class="form-control" id="tagFilter" name="tagFilter">
                            <option value="">All History Tags</option>
                            <?php
                            $sql = "SELECT id, tag_name FROM tags WHERE parent_tag_name='History' OR tag_name='History'";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='{$row['id']}'>{$row['tag_name']}</option>";
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div id="questions">
                        <!-- Questions will be loaded here via AJAX -->
                    </div>
                    <div id="question-container" class="mt-4">
                        <form id="question-form" class="question-form">
                            <div id="question-text" class="fade-in-right"></div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer" value="1" id="option1" required>
                                <label class="form-check-label" for="option1" id="label-option1"></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer" value="2" id="option2" required>
                                <label class="form-check-label" for="option2" id="label-option2"></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer" value="3" id="option3" required>
                                <label class="form-check-label" for="option3" id="label-option3"></label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="answer" value="4" id="option4" required>
                                <label class="form-check-label" for="option4" id="label-option4"></label>
                            </div>
                            <input type="hidden" name="question_id" id="question_id">
                            <button type="submit" class="btn btn-primary mt-3">Submit and Next</button>
                            <div class="answer-result mt-3"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let questions = [];
            let currentQuestionIndex = 0;

            function loadQuestion(index) {
                if (index < questions.length) {
                    const question = questions[index];
                    $('#question-text').hide().text(question.question).fadeIn(500).addClass('fade-in-right');
                    $('#label-option1').text(question.option1);
                    $('#label-option2').text(question.option2);
                    $('#label-option3').text(question.option3);
                    $('#label-option4').text(question.option4);
                    $('#question_id').val(question.id);
                    $('input[name="answer"]').prop('checked', false);
                } else {
                    $('#question-container').html('<p>No more questions available.</p>');
                }
            }

            function updateGlobalRating() {
                $.ajax({
                    url: 'fetch_global_rating.php',
                    type: 'GET',
                    success: function(response) {
                        $('#globalRating').text(response);
                    }
                });
            }

            $('#tagFilter').change(function() {
                var tag_id = $(this).val();
                $.ajax({
                    url: 'fetch_questions_history.php',
                    type: 'GET',
                    data: { tag_id: tag_id },
                    success: function(response) {
                        questions = JSON.parse(response);
                        currentQuestionIndex = 0;
                        loadQuestion(currentQuestionIndex);
                    }
                });
            });

            $('#question-form').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: 'submit_answer.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        form.find('.answer-result').html(response);
                        updateGlobalRating(); // Update globalRating after submitting an answer
                        currentQuestionIndex++;
                        loadQuestion(currentQuestionIndex);
                    }
                });
            });

            // Load questions on page load
            $('#tagFilter').trigger('change');

            // Update globalRating every 5 seconds
            setInterval(updateGlobalRating, 5000);
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
