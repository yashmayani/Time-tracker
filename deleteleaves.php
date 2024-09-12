<?php
session_start();

include("./config.php");

if (isset($_POST['delete_leaves_id'])) {
    $leaves_id_to_delete = intval($_POST['delete_leaves_id']);

    // Prepare and execute deletion query
    $stmt = $conn->prepare("DELETE FROM leaves WHERE leaves_id = ?");
    $stmt->bind_param("i", $leaves_id_to_delete);

    if ($stmt->execute()) {
        echo  "Project deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting project: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: leaves.php");
    exit();
}
?>
