<?php 
session_start(); // Start session to store messages


include("./config.php");

if(isset($_POST['submit'])){
    $emp_id=$_POST['emp_id'];
    $project_id=$_POST['project_id'];
//  print_r($_POST);

    $addproject=$conn->query("insert into project_assign(employee_id,project_id)values('$emp_id','$project_id')");
    
    
    // print_r($project);
    if ($addproject) {
        $_SESSION['message'] = "Employee Added successfully.";
        header("Location: view_project.php?id=$project_id"); // Redirect to registrationform.php
  
      } else {
      echo  "Error: " . $conn->error; // Set error message
      }
    
    exit();
}