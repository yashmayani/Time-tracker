<?php 
session_start(); // Start session to store messages


include("./config.php");

if(isset($_POST['addproject'])){
    $project=$_POST['project'];


    $addproject=$conn->query("insert into project(project_name)values('$project')");
    
    
    // print_r($project);
    if ($addproject) {
        echo "User add vehicle successful."; // Set success message
        header("Location: addproject.php"); // Redirect to registrationform.php
  
      } else {
      echo  "Error: " . $conn->error; // Set error message
      }
    
    exit();
}


?>

