<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "school_db";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$rollno = $_POST['rollno'];
$div = $_POST['div'];
$address = $_POST['address'];

$sql = "INSERT INTO students (Name, Rollno, `Div`, `Address`) VALUES ('$name', '$rollno', '$div', '$address')";
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
