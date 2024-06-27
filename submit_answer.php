<?php
session_start();
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $question_id = intval($_POST['question_id']);
    $user_answer = intval($_POST['answer']);
    $user_id = $_SESSION['user_id'];

    // Get the correct answer and question rating from the database
    $sql = "SELECT correct_answer, question_rating FROM questions WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $stmt->bind_result($correct_answer, $question_rating);
    $stmt->fetch();
    $stmt->close();

    // Get the user's current globalRating
    $sql = "SELECT globalRating FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($globalRating);
    $stmt->fetch();
    $stmt->close();

    // Determine win result
    if ($user_answer == $correct_answer) {
        $winresult = 2; // correct answer
    } else {
        $winresult = 0; // wrong answer
    }

    // Calculate probability of success
    $probabilityOfSuccess = 1 / (1 + pow(10, ($question_rating - $globalRating) / 25));

    // Calculate new globalRating
    $newGlobalRating = 2 * ($winresult - $probabilityOfSuccess) + $globalRating;

    // Update user's globalRating
    $sql = "UPDATE users SET globalRating = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $newGlobalRating, $user_id);
    $stmt->execute();
    $stmt->close();

    // Update question attempts
    if ($user_answer == $correct_answer) {
        $update_sql = "UPDATE questions SET no_of_attempts = no_of_attempts + 1, successful_attempts = successful_attempts + 1 WHERE id = ?";
    } else {
        $update_sql = "UPDATE questions SET no_of_attempts = no_of_attempts + 1 WHERE id = ?";
    }
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $question_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Return the result to the user
    if ($user_answer == $correct_answer) {
        echo "<div class='alert alert-success'>Correct! The answer is option $correct_answer. Your new global rating is " . round($newGlobalRating) . ".</div>";
    } else {
        echo "<div class='alert alert-danger'>Incorrect. The correct answer is option $correct_answer. Your new global rating is " . round($newGlobalRating) . ".</div>";
    }
}

$conn->close();
?>
