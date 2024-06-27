<?php
require 'db_connect.php';

$tag_id = isset($_GET['tag_id']) ? intval($_GET['tag_id']) : 0;

$sql = "SELECT * FROM questions";
if ($tag_id > 0) {
    $sql .= " WHERE taglist_id = $tag_id";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='card mb-3'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . htmlspecialchars($row['question']) . "</h5>";
        echo "<form class='question-form'>";
        echo "<input type='hidden' name='question_id' value='{$row['id']}'>";
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='radio' name='answer' value='1' required>";
        echo "<label class='form-check-label'>" . htmlspecialchars($row['option1']) . "</label>";
        echo "</div>";
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='radio' name='answer' value='2' required>";
        echo "<label class='form-check-label'>" . htmlspecialchars($row['option2']) . "</label>";
        echo "</div>";
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='radio' name='answer' value='3' required>";
        echo "<label class='form-check-label'>" . htmlspecialchars($row['option3']) . "</label>";
        echo "</div>";
        echo "<div class='form-check'>";
        echo "<input class='form-check-input' type='radio' name='answer' value='4' required>";
        echo "<label class='form-check-label'>" . htmlspecialchars($row['option4']) . "</label>";
        echo "</div>";
        echo "<button type='submit' class='btn btn-primary mt-3'>Submit</button>";
        echo "<div class='answer-result mt-3'></div>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>No questions available.</p>";
}

$conn->close();
?>
