<?php
session_start();

include("./config.php");

if (isset($_POST['delete_project_id'])) {
    $project_id_to_delete = intval($_POST['delete_project_id']);

    // Prepare and execute deletion query
    $stmt = $conn->prepare("DELETE FROM daily_updates WHERE update_id = ?");
    $stmt->bind_param("i", $project_id_to_delete);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Project deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting project: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

    header("Location: dashboard.php");
    exit();
}
?>
