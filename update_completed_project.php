<?php

session_start();


include("./config.php");

$project_id = $_GET['id'];


$completed_project = $conn->query("UPDATE  project SET status ='1'  WHERE project_id = '$project_id'");

header("Location: addproject.php");
exit();
?>     
