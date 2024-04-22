<?php
session_start(); // Start session

include 'db_connection.php';

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];

// Query database to check if user exists and password is correct
$sql = "SELECT * FROM usersall WHERE (Username='$username' OR Email='$username') AND Password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, login successful
    $_SESSION['username'] = $username; // Store username in session
    header("Location: Home.php"); // Redirect to dashboard or profile page
    exit();
} else {
    // Login failed, redirect back to login page with error message
    $_SESSION['login_error'] = "Invalid username/email or password";
    header("Location: login.html");
    exit();
}

// Close database connection
$conn->close();
?>
