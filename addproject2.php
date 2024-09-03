<?php 
session_start(); // Start session to store messages


include("./config.php");

if(isset($_POST['addproject'])){
    $project=$_POST['project'];
    $client=$_POST['client'];
    $project_start_date=$_POST['project_start_date'];
    $project_end_date=$_POST['project_end_date'];
    $platform=$_POST['platform'];
//  print_r($_POST);

    $addproject=$conn->query("insert into project(project_name,client_name,start_date,end_date,platform)values('$project','$client','$project_start_date','$project_end_date','$platform')");
    
    
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

