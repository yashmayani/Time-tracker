<?php
session_start();

include("./config.php");

$project_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($project_id) {
    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE leaves SET status = ? WHERE leaves_id = ?");
    $status = 1;
    $stmt->bind_param('ii', $status, $project_id);

    if ($stmt->execute()) {
        // Set a success message in session
        echo "Project marked as completed successfully.";
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

header("Location: leaves.php");
exit();
?>
