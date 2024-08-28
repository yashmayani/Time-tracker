


<?php
    $current_page = basename($_SERVER['PHP_SELF']);

include("./config.php");
$date = date("Y-m-d");
$week = date('Y-m-d', strtotime($date .' -1 week'));

// $project=$conn->query("SELECT 
//     du.task,
//     du.date,
//     du.time,
//     p.project_name
// FROM 
//     daily_updates AS du
// JOIN 
//     project AS p
// ON 
//     du.project_id = p.project_id;")->fetch_all();

// Assuming you have already started the session and have a valid database connection in $conn

// Prepare the SQL statement
// Database connection and session start assumed to be here

// Calculate the date for the last week
$date = date("Y-m-d");
$week = date('Y-m-d', strtotime($date .' -1 week'));

// Prepare the SQL statement with date filtering
$stmt = $conn->prepare("
    SELECT 
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
        du.employee_id = ? AND
        du.date >= ?
    ORDER BY 
        du.date DESC, 
        du.hour DESC, 
        du.minute DESC
");


// Bind the session employee ID and the date for the last week to the prepared statement
$stmt->bind_param("ss", $_SESSION['employee_id'], $week);

// Execute the prepared statement
$stmt->execute();

// Fetch all results
$result = $stmt->get_result();
$project = $result->fetch_all(MYSQLI_ASSOC);
// var_dump($project);
// Close the statement
$stmt->close();

// Process the results as needed


// echo '<pre>';
// var_dump($project);
$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);
// print_r('<pre>');
// var_dump($projects);

    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     $date = $_POST['date'];
    //     $project = $_POST['projects'];

    //     // Iterate through dynamically added tasks
    //     $tasks = [];
    //     $times = [];

    //     foreach ($_POST as $key => $value) {
    //         if (strpos($key, 'task-') === 0) {             
    //             $tasks[] = $value;
    //         }
    //         if (strpos($key, 'hour-') === 0) {
    //             $hour[] = $value;
    //         }
    //     }

    //     // Example of processing tasks and times
    //     foreach ($tasks as $index => $task) {
    //         $time = isset($times[$index]) ? $times[$index] : ''; // Ensure time matches task
    //         // Save $date, $project, $task, and $time to the database
    //         // Example query (ensure you handle SQL injection and use prepared statements)
    //         $stmt = $conn->prepare("INSERT INTO daily_updates (date, project_id, task, time) VALUES (?, ?, ?, ?)");
    //         $stmt->bind_param("ssss", $date, $project, $task, $time);
    //         $stmt->execute();
    //     }

    //     // Redirect or display a success message
    //     echo "";
    // }


?>



<?php

if (isset($_SESSION['name'])) {
    // Split the full name into parts
    $nameParts = explode(' ', $_SESSION['name']);
    
    // Initialize variables for initials
    $firstInitial = '';
    $lastInitial = '';

    // Check if there are at least two parts
    if (count($nameParts) >= 2) {
        // Get the first letter of the first name
        $firstInitial = substr($nameParts[0], 0, 1);
        // Get the first letter of the last name
        $lastInitial = substr($nameParts[1], 0, 1);
    }

    // Output the initials
} 
?>











<nav class="navbar navbar-expand-lg main-nav fixed-top">
    <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-lg-0 d-flex justify-content-between align-items-center w-100">
                <li class="nav-item">
                   
                </li>
                <li class="nav-item">
                    <div class="navbar-item">
                        <p class="prodev">
                            <div class="yess">
                                <b> <?php echo htmlspecialchars($firstInitial . $lastInitial); ?></b>
                            </div>
                            <div class="sizes">
                                <span class="as"><b> <?php echo htmlspecialchars($_SESSION['name']); ?></b></span>
                                <span class="ass"><b> <?php echo htmlspecialchars($_SESSION['position']); ?></b></span>
                            </div>
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
