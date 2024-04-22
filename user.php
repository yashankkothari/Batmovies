<?php
session_start();

function removeImage($imagePath) {
    unlink($imagePath); 
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

$imagesDirectory = "upload-images/";
$maxFileSize = 1 * 1024 * 1024; 
$allowedTypes = array('image/jpeg', 'image/png');

// Function to download a file
function downloadFile($filePath) {
    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}

if(isset($_FILES['image'])) {
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_size = $_FILES['image']['size'];
    $file_type = $_FILES['image']['type'];

    if($file_size <= $maxFileSize && in_array($file_type, $allowedTypes)) {
        if(move_uploaded_file($file_tmp, $imagesDirectory . $file_name)){
            echo "Successfully uploaded.";
            $_SESSION['profile_picture'] = $imagesDirectory . $file_name;
        } else {
            echo "Could not upload the file.";
        }
    } else {
        echo "File size should be under 1 MB and the type should be JPEG or PNG.";
    }
}

if(isset($_POST['remove']) && isset($_SESSION['profile_picture'])) {
    removeImage($_SESSION['profile_picture']);
    unset($_SESSION['profile_picture']);
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

// Function to append data to a file
function appendToFile($filePath, $data) {
    $file = fopen($filePath, "a");
    fwrite($file, $data);
    fclose($file);
}

// Function to delete a file
function deleteFile($filePath) {
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

// Function to open and read a file
function openAndReadFile($filePath) {
    if (file_exists($filePath)) {
        $file = fopen($filePath, "r");
        $content = fread($file, filesize($filePath));
        fclose($file);
        return $content;
    }
    return false;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batmovies</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/batmovies.png">
    <style>
.movie {
    display: inline-block;
    margin-right: 20px;
    vertical-align: top;
    text-align: center;
}
.movie img {
    max-width: 150px;
    height: auto;
}
.movie p {
    color: white;
    margin-top: 5px;
}
.trending-title {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: white;
}
.trending-container {
    text-align: center;
}
.navbar a {
    color: white;
    text-decoration: none;
}
body {
    background-color: #222;
    color: white;
}
.user-profile {
    text-align: center;
    margin-top: 50px;
}
.user-profile h1 {
    font-size: 24px;
    margin-bottom: 20px;
}
.user-profile img {
    max-width: 200px;
    height: auto;
    margin-bottom: 20px;
}
.user-profile form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
}
.user-profile label {
    display: block;
    margin-bottom: 10px;
}
.user-profile input[type="file"] {
    width: 200px;
    margin-bottom: 10px;
}
.user-profile button {
    padding: 10px 20px;
    margin-top: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.user-profile button:hover {
    background-color: #0056b3;
}
.user-profile form .remove-profile-btn {
    width: auto; 
    padding: 10px 20px; 
    background-color: #dc3545; 
    color: white; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    margin-top: 10px; 
}
.user-profile form .remove-profile-btn:hover {
    background-color: #c82333;
}
    </style>
</head>
<body>
    <div class="prime">
        <nav class="navbar">
            <img class="logo" src="./images/batmovies.png">
            <div class="search">
                <input id="searchInput" type="text" placeholder="Search Batmovies">
                <button id="searchButton" type="submit"><img src="./images/search.svg"></button>
            </div>
            <?php
                if(isset($_SESSION['username'])){
                    echo '<div class="sign"><a href="logout.php">Logout</a></div>';
                    $username = $_SESSION['username'];
                    echo "<div class='sign'><a href='user.php'>$username</a></div>";
                } else {
                    echo '<div class="sign"><a href="login.html">Sign in</a></div>';
                }
            ?>
            <div class="sign"><a href="watchlist.html">Your Watchlist</a></div>
        </nav>
        <div class="user-profile">
            <?php
                echo "<h1>Welcome, $username!</h1>";
                if (isset($_SESSION['profile_picture'])) {
                    $profilePicture = $_SESSION['profile_picture'];
                    echo "<img src='$profilePicture' alt='Profile Picture'>";
                    echo "<form action='' method='post'>";
                    echo "<input type='submit' name='remove' value='Remove Profile Picture' class='remove-profile-btn' />";
                    echo "</form>";
                    echo "<form action='' method='post'>";
                    echo "<input type='hidden' name='redownload' value='$profilePicture'>";
                    echo "<button type='submit'>Redownload Profile Picture</button>";
                    echo "</form>";
                } else {
                    echo "You don't have a profile picture.";
                }
            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <label for="profilePicture">Upload Profile Picture:</label>
                <input type="file" id="profilePicture" name="image" accept="image/*">
                <button type="submit">Upload</button>
            </form>
            <?php
                // Example usage of file functionalities
                $testFilePath = "test.txt";
                appendToFile($testFilePath, "Hello, world!\n");
                $fileContent = openAndReadFile($testFilePath);
                echo "<p>File Content: $fileContent</p>";
                deleteFile($testFilePath);
            ?>
        </div>
    </div>
</body>
</html>
