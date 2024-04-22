<?php
function removeImage($imagePath) {
    unlink($imagePath); 
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

$imagesDirectory = "upload-images/";
$maxFileSize = 1 * 1024 * 1024; 
$allowedTypes = array('image/jpeg', 'image/png');

if(isset($_FILES['image'])) {
    $file_name = $_FILES['image']['name'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_size = $_FILES['image']['size'];
    $file_type = $_FILES['image']['type'];

    if($file_size <= $maxFileSize && in_array($file_type, $allowedTypes)) {
        if(move_uploaded_file($file_tmp, $imagesDirectory . $file_name)){
            echo "Successfully uploaded.";
        } else {
            echo "Could not upload the file.";
        }
    } else {
        echo "File size should be under 1 MB and the type should be JPEG or PNG.";
    }
}

if(isset($_POST['remove']) && isset($_POST['image_path'])) {
    removeImage($_POST['image_path']);
}

$images = glob($imagesDirectory . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and Remove Images</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            text-align: center;
        }
        input[type="file"] {
            margin-bottom: 10px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .image-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }
        .image-container .image-wrapper {
            margin: 10px;
            text-align: center;
        }
        .image-container img {
            max-width: 200px;
            height: auto;
            border-radius: 5px;
        }
        .image-container form {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload and Remove Images</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" />
            <br><br>
            <input type="submit" value="Upload" />
        </form>

        <div class="image-container">
            <?php foreach($images as $image) { ?>
                <div class="image-wrapper">
                    <img src="<?php echo $image; ?>" />
                    <form action="" method="POST">
                        <input type="hidden" name="image_path" value="<?php echo $image; ?>" />
                        <input type="submit" name="remove" value="Remove" />
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
