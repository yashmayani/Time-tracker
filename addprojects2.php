<?php
session_start();

include("./config.php");


if(isset($_POST['editproject'])){
    $project_name = $_POST['project'];
    $client = $_POST['client'];
    $project_start_date = $_POST['project_start_date'];
    $project_end_date = $_POST['project_end_date'];
    $platform = $_POST['platform'];
    $id = $_POST['project_id'];

$updateproject = $conn->query("UPDATE project SET project_name = '$project_name', client_name = '$client', start_date = '$project_start_date', end_date = '$project_end_date', platform = '$platform' WHERE project_id = '$id'");
// var_dump($updateprojects);
if ($updateproject) {
    header("Location:view_complete_project.php");
    exit; // Ensure script stops after redirection
} else {
    echo "Error updating meeting.";
}
}
?>