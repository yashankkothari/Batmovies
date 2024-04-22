<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    include 'db_connection.php';

    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; 

    // Perform validation (e.g., check if fields are not empty) (TO-do)

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM usersall WHERE Username='$username' OR Email='$email'";
    $result = $conn->query($checkQuery);
    if ($result->num_rows > 0) {
        // Username or email already exists
        echo "<script>alert('Username or email is already taken. Please choose another one.');";
        echo "window.location.href = 'signup.html';</script>";
        exit(); // Stop further execution
    } else {
        // Insert data into the database
        $sql = "INSERT INTO usersall (Username, Email, Password, SignupDate) VALUES ('$username', '$email', '$password', NOW())";

        if ($conn->query($sql) === TRUE) {
            // Registration successful
            // Redirect to signup page with success message as URL parameter
            header("Location: signup.html?signup=success");
            exit(); // Stop further execution
        } else {
            // Registration failed
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close database connection
    $conn->close();
}
?>

