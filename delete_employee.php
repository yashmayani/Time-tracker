<?php
session_start();

include("./config.php");

if (isset($_POST['delete_project_id'])) {
    $project_id_to_delete = intval($_POST['delete_project_id']);

    // Prepare and execute deletion query
    $stmt = $conn->prepare("DELETE FROM project_assign WHERE employee_id = ?");
    $stmt->bind_param("i", $project_id_to_delete);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Employee deleted successfully.";
    } else {
        $_SESSION['message'] = "Error deleting project: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    $pro=$_POST['project_id'];
    header("Location: view_project.php?id=$pro");
    exit();
}
?>
