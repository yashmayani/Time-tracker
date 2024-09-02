<?php 
session_start(); // Start session to store messages


include("./config.php");

if(isset($_POST['addemployee'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];
    $position=$_POST['position'];



    $addproject=$conn->query("insert into employees(name,email,password,position)values('$name','$email','$password','$position')");
    
    
    // print_r($project);
    if ($addproject) {
        echo "User add vehicle successful."; // Set success message
        header("Location: employee.php"); // Redirect to registrationform.php
  
      } else {
      echo  "Error: " . $conn->error; // Set error message
      }
    
    exit();
}


?>

