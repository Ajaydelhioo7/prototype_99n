<?php
session_start();
require 'db_connect.php'; // Include database connection settings

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate user input
    if (!empty($email) && !empty($password)) {
        // Create a prepared statement
        $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $email, $hashed_password);
            $stmt->fetch();
            
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Store session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with that email address.";
        }
        
        $stmt->close();
    } else {
        echo "Please enter both email and password.";
    }
}
?>
