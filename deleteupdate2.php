<?php
session_start();

include("./config.php");

if (isset($_POST['delete_project_id'])) {
    $project_id_to_delete = intval($_POST['delete_project_id']);

    // Prepare and execute deletion query
    $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
    $stmt->bind_param("i", $project_id_to_delete);

    $stmt2 = $conn->prepare("DELETE FROM project_assign WHERE employee_id = ?");
    $stmt2->bind_param("i", $project_id_to_delete);

    if ($stmt->execute() && $stmt2->execute()) {
        $_SESSION['message'] = "Employee deleted successfully.";
        header("Location: employee.php");
        exit();
    } else {
        $_SESSION['message'] = "Error deleting project: " . $stmt->error;
        echo $stmt->error;
    }

    $stmt->close();
    $conn->close();

   
}
?>
