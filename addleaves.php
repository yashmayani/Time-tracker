<?php

session_start();

include("./config.php");


if(isset($_POST['addleaves'])){
    $from_date=$_POST['from_date'];
    $end_date=$_POST['end_date'];
    $reasan=$_POST['reasan'];
    $id = $_SESSION["employee_id"];


$addleaves =$conn->query("insert into leaves(from_date,end_date,reasan,employee_id)values('$from_date','$end_date','$reasan','$id')");


if ($addleaves) {
     echo "User add vehicle successful."; 
   
    header("Location: leaves.php"); // Redirect to registrationform.php

  } else {
  echo  "Error: " . $conn->error; // Set error message
  }

exit();
}
?>