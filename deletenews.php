<?php
session_start();
include("./config.php");

if (isset($_POST['delete_news_id'])) {
    $news_id_to_delete = intval($_POST['delete_news_id']);

    // Prepare and execute deletion query
    $stmt = $conn->prepare("DELETE FROM prodev_news WHERE news_id = ?");
    
    // Check if preparation was successful
    if (!$stmt) {
        $_SESSION['message'] = "Error preparing statement: " . $conn->error;
        header("Location: home.php");
        exit();
    }

    $stmt->bind_param("i", $news_id_to_delete);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Project deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting project: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: home.php");
    exit();
}
?>
