<?php
session_start();
require_once "db_connection.php";

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

// Fetch user's reviews from the database
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $userReviewsQuery = "SELECT * FROM movie_reviews WHERE username='$username'";
    $result = mysqli_query($conn, $userReviewsQuery);
    $reviews = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
    }
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
    display: flex;
    justify-content: space-between;
    margin-top: 50px;
    padding: 0 20px;
}
.user-profile .left {
    width: 40%;
    text-align: left;
}
.user-profile .right {
    width: 55%;
    text-align: left;
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
    align-items: flex-start;
    margin-top: 20px;
}
.user-profile label {
    display: block;
    margin-bottom: 10px;
    width: 200px;
}
.user-profile input[type="file"] {
    width: 200px;
    margin-bottom: 10px;
    padding: 0px 8px;
}
.user-profile button {
    padding: 5px 50px;
    margin-top: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    width: 220px;
}
.user-profile button:hover {
    background-color: #0056b3;
}
.user-profile form .remove-profile-btn {
    width: auto; 
    padding: 23px 40px; 
    background-color: #dc3545; 
    color: white; 
    border: none; 
    border-radius: 5px; 
    cursor: pointer; 
    height: 60px;
}
.user-profile form .remove-profile-btn:hover {
    background-color: #c82333;
}
.review {
    padding: 20px;
    border: 1px solid #ccc;
    margin-bottom: 20px;
}
.review h2 {
    color: white;
}
.review p {
    color: white;
}
    </style>
</head>
<body>
    <div class="prime">
        <nav class="navbar">
            <a href="Home.php"><img class="logo" src="./images/batmovies.png"></a>
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
            <div class="left">
                <h1>Welcome, <?php echo $username; ?>!</h1>
                <?php
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
            </div>
            <div class="right">
    <h2>Your Reviews:</h2>
    <?php 
    if (!empty($reviews)) {
        foreach ($reviews as $review) {
            echo "<div class='review'>";
            echo "<h2>{$review['movie_title']}</h2>";
            echo "<p>Rating: {$review['rating']}</p>"; // Modified this line
            echo "</div>";
        }
    } else {
        echo "<p>No reviews found.</p>";
    }
    ?>
</div>
            </div>
        </div>
    </div>
</body>
</html>
