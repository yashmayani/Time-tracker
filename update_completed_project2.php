<?php
session_start();

include("./config.php");

$project_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($project_id) {
    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE project SET status = ? WHERE project_id = ?");
    $status = 0;
    $stmt->bind_param('ii', $status, $project_id);

    if ($stmt->execute()) {
        // Set a success message in session
        $_SESSION['message'] = 'Project marked as incompleted.';
        $_SESSION['message_type'] = 'success'; // Alert type
    } else {
        // Set an error message in session
        $_SESSION['message'] = 'Failed to mark project as completed.';
        $_SESSION['message_type'] = 'danger'; // Alert type
    }

    $stmt->close();
} else {
    // Set an error message if project_id is not valid
    $_SESSION['message'] = 'Invalid project ID.';
    $_SESSION['message_type'] = 'danger'; // Alert type
}

header("Location: view_complete_project.php");
exit();
?>
