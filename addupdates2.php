<?php
session_start(); // Start session to store messages

include("./config.php");

if (isset($_POST['submit'])) {
    $tasks = $_POST['task'];
    $hours = $_POST['hours'];
    $minutes = $_POST['minutes'];
    $comment_ids = $_POST['commit_id'];
    $project_id = $_POST['projects'];
    $employee_id = $_SESSION['employee_id'];
    $date = $_POST['date'];
    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO daily_updates (employee_id, project_id, task, date, hour, minute, commit_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($tasks as $index => $task) {
        $hour = $hours[$index];
        $minute = $minutes[$index];
        $commit_id = $comment_ids[$index];

        // Bind parameters
        $stmt->bind_param('iisssss', $employee_id, $project_id, $task, $date, $hour, $minute, $commit_id);

        // Execute the statement
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
            exit();
        }
    }

    $stmt->close();
    $conn->close();

    echo "Tasks added successfully.";
    header("Location: dashboard.php"); // Redirect to dashboard or another page
    exit();
}
?>
