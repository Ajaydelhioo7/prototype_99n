<?php
require 'db_connect.php';

$tag_id = isset($_GET['tag_id']) ? intval($_GET['tag_id']) : 0;

$sql = "SELECT q.*, t.tag_name FROM questions q 
        JOIN tags t ON q.taglist_id = t.id 
        WHERE t.tag_name = 'History'";

if ($tag_id > 0) {
    $sql .= " AND q.taglist_id = $tag_id";
}

$result = $conn->query($sql);

$questions = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}

$conn->close();

echo json_encode($questions);
?>
