<?php
session_start();

include("./config.php");


if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $prodevnews = $conn->query("INSERT INTO prodev_news (title, content) VALUES ('$title', '$content')");

    if ($prodevnews) {
        $news_id = $conn->insert_id;
        $stmt = $conn->prepare("INSERT INTO prodev_image (news_id, image) VALUES (?, ?)");
        $uploadedImages = $_FILES['image'];
    
        foreach ($uploadedImages['name'] as $key => $value) {
            $targetDir = "news_img/";
            // Get the file extension
            $fileExtension = pathinfo($uploadedImages['name'][$key], PATHINFO_EXTENSION);
            // Create a new file name with timestamp
            $fileName = pathinfo($uploadedImages['name'][$key], PATHINFO_FILENAME) . '_' . time() . '.' . $fileExtension;
            $targetFilePath = $targetDir . $fileName;
    
            if (file_exists($targetFilePath)) {
                echo "Sorry, file already exists: " . $fileName . "<br>";
            } else {
                if (move_uploaded_file($uploadedImages["tmp_name"][$key], $targetFilePath)) {
                    $imagePath = $targetFilePath;
                    $stmt->bind_param("is", $news_id, $imagePath);
                    $stmt->execute();
                } else {
                    echo "Sorry, there was an error uploading your file: " . $fileName . "<br>";
                }
            }
        }
        header("Location: home.php");
        exit();
        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }
}    
