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
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/dashboard.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h6>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></h6>
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
    <div class="container">
        <div class="dashboard-content">
            <div class="form-group">
                <label for="tagFilter">Select Tag:</label>
                <select class="form-control" id="tagFilter" name="tagFilter">
                    <option value="">All Tags</option>
                    <?php
                    $sql = "SELECT id, tag_name FROM tags";
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
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Function to fetch and update globalRating
            function updateGlobalRating() {
                $.ajax({
                    url: 'fetch_global_rating.php',
                    type: 'GET',
                    success: function(response) {
                        $('#globalRating').text(response);
                    }
                });
            }

            // Update globalRating every 5 seconds
            setInterval(updateGlobalRating, 5000);

            $('#tagFilter').change(function() {
                var tag_id = $(this).val();
                $.ajax({
                    url: 'fetch_questions.php',
                    type: 'GET',
                    data: { tag_id: tag_id },
                    success: function(response) {
                        $('#questions').html(response);
                    }
                });
            });

            // Load all questions on page load
            $('#tagFilter').trigger('change');

            $(document).on('submit', '.question-form', function(e) {
                e.preventDefault();
                var form = $(this);
                $.ajax({
                    url: 'submit_answer.php',
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        form.find('.answer-result').html(response);
                        updateGlobalRating(); // Update globalRating after submitting an answer
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
