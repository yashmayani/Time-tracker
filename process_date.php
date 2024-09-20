<?php
session_start();
// Database connection parameters
include("./config.php");
       
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);
// var_dump($projects);
// Get date from form
$date_from_form = $_POST['date'];

// Fetch records for the given date
$sql =  $conn->prepare("SELECT 
    du.update_id,
    du.project_id,
    du.task,
    du.date,
    du.hour,
    du.minute,
    du.commit_id,
    p.project_name
FROM 
    daily_updates AS du
JOIN 
    project AS p
ON 
    du.project_id = p.project_id
    WHERE 
        du.employee_id = ? AND date = ?");
$sql->bind_param("ss", $_SESSION['employee_id'],$date_from_form);
$sql->execute();
$result = $sql->get_result();

// var_dump($result);

// Check if any records are found
if ($result->num_rows > 0) {
    echo "<h2>Updates for: " . htmlspecialchars($date_from_form) . "</h2>";
    echo "<div id='main-table'>";
    echo  "<table id='vehicleTable' class='table '>
            <tr>
                 <th>UPDATE</th>
                        <th>TIME</th>
                        <th>COMMIT ID</th>
                        <th>PROJECT</th>
                        <th>ACTION</th>
            </tr>";

    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . nl2br($row['task']) . "</td>
                <td>" . htmlspecialchars($row['hour']) . ':' . htmlspecialchars($row['minute']) . "</td>
                  <td>" . htmlspecialchars($row['commit_id']) . "</td>
                <td>" . htmlspecialchars($row['project_name']) . "</td>
              

                 <td>
                            
                                
        
              </tr>";
    }

    echo "</table>";
} else {
    echo "No updates found for the selected date.";
}

// Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    
</body>
</html>