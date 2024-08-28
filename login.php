<?php
session_start(); // Start session to store messages


include("./config.php");

if(isset($_POST['login'])){
    
    $emailaddress=$_POST['email'];
    $password=$_POST['password'];

    $user=$conn->query("SELECT password,employee_id,name,position,role  FROM employees WHERE email = '$emailaddress'")->fetch_all();
    // print_r('<pre>');
    // print_r($user);
    if (($password==$user[0][0])) {
        // Password is correct
        $_SESSION['employee_id'] = $user[0][1];
        $_SESSION['name'] = $user[0][2];
        $_SESSION['position'] = $user[0][3];
        $_SESSION['role'] = $user[0][4];
        
        header("Location: dashboard.php");
        exit();
    } else {
        // Incorrect password
        echo "The password you've entered is incorrect.";
        header("Location: loginform.php");
        exit();
    }
} 



?>