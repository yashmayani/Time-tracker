<?php
session_start(); // Start session to store messages


include("./config.php");



$project=$conn->query("SELECT 
    du.task,
    du.date,
    du.time,
    p.project_name
FROM 
    daily_updates AS du
JOIN 
    project AS p
ON 
    du.project_name = p.project_name;
")->fetch_all();
print_r('<pre>');
 var_dump($projects);
 
?>


