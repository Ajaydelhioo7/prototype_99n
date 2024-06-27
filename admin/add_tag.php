<?php
require '../db_connect.php'; // Include database connection settings

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tag_name = $_POST['tag_name'];
    $parent_tag_name = isset($_POST['parent_tag_name']) ? $_POST['parent_tag_name'] : NULL;

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO tags (tag_name, parent_tag_name) VALUES (?, ?)");
    $stmt->bind_param("ss", $tag_name, $parent_tag_name);

    if ($stmt->execute()) {
        echo "Tag added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
