<?php
require '../db_connect.php'; // Include the database connection settings

$sql = "SELECT id, tag_name FROM tags";
$result = $conn->query($sql);
$options = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row['id']}'>{$row['tag_name']}</option>";
    }
}

$conn->close();
echo $options;
?>
