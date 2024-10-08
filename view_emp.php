<?php
session_start(); // Start session to store messages
$current_page = basename($_SERVER['PHP_SELF']);

include("./config.php");






$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);
// var_dump($project);




if (isset($_POST['editupdate'])) {
    $date = $_POST['date'];
    $projects = $_POST['projects'];
    $task = $_POST['task'];
    $hours = $_POST['hours'];
    $minutes = $_POST['minutes'];
    $commit_id = $_POST['commit_id'];
    $id = $_POST['update_id'];



    $updatedaily_updates = $conn->query("UPDATE daily_updates SET date = '$date' , project_id = '$projects', task = '$task', hour = '$hours', minute = '$minutes', commit_id = ' $commit_id ' WHERE update_id = '$id' ");
    // var_dump($date);
    // var_dump($projects);
    // var_dump($task);
    // var_dump($hours);
    // var_dump($minutes);
    // var_dump($commit_id);

    ob_start(); // Start output buffering

    if ($updatedaily_updates) {
        header("Refresh:0");
        ob_end_flush(); // Flush and turn off output buffering
        exit;
    } else {
        echo "Error updating meeting.";
        ob_end_flush(); // Flush and turn off output buffering
        exit;
    }
}
include("navbarss.php");


$filter = false;
if (isset($_POST['date_view'])) {
    $filter = true;
    $emp_id = $_POST['emp_id'];
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : '';



    $date = date("Y-m-d");
    $week = date('Y-m-d', strtotime($date . ' -1 week'));

    $now = new DateTime();
    $firstDayOfCurrentMonth = new DateTime($now->format('Y-m-01'));
    $lastDayOfCurrentMonth = (clone $firstDayOfCurrentMonth)->modify('last day of this month');
    $firstDayOfLastMonth = (clone $firstDayOfCurrentMonth)->modify('first day of last month');
    $lastDayOfLastMonth = (clone $firstDayOfLastMonth)->modify('last day of this month');

    // Format dates for SQL queries
    $currentMonthStart = $firstDayOfCurrentMonth->format('Y-m-d');
    $currentMonthEnd = $lastDayOfCurrentMonth->format('Y-m-d');
    $lastMonthStart = $firstDayOfLastMonth->format('Y-m-d');
    $lastMonthEnd = $lastDayOfLastMonth->format('Y-m-d');

    $sql =  $conn->prepare("SELECT 
    du.update_id,
    du.project_id,
    du.task,
    du.date,
    du.hour,
    du.minute,
     SUM(du.hour) OVER (PARTITION BY du.employee_id, du.project_id, du.task, du.date) AS total_hour,
        SUM(du.minute) OVER (PARTITION BY du.employee_id, du.project_id, du.task, du.date) AS total_minute,
    du.commit_id,
    du.employee_id,
    p.project_name
FROM 
    daily_updates AS du
JOIN 
    project AS p
ON 
    du.project_id = p.project_id
    WHERE 
        du.employee_id = ?
        AND (du.date BETWEEN ? AND ?)
        ORDER BY 
    du.date DESC, du.hour DESC, du.minute DESC");
    $sql->bind_param("sss", $emp_id, $startDate, $endDate);
    $sql->execute();
    $result = $sql->get_result();
    $project = $result->fetch_all(MYSQLI_ASSOC);

    $sqlCurrentMonth = $conn->prepare("
    SELECT 
        SUM(du.hour) AS total_hour,
        SUM(du.minute) AS total_minute
    FROM 
        daily_updates AS du
    JOIN 
        project AS p
    ON 
        du.project_id = p.project_id
    WHERE 
        du.employee_id = ?
        AND du.date BETWEEN ? AND ?
");
    $sqlCurrentMonth->bind_param("sss", $emp_id, $currentMonthStart, $currentMonthEnd);
    $sqlCurrentMonth->execute();
    $resultCurrentMonth = $sqlCurrentMonth->get_result();
    $currentMonthTotals = $resultCurrentMonth->fetch_assoc();

    $totalMinutes = $currentMonthTotals['total_minute'];
    $additionalHours = intdiv($totalMinutes, 60); // Hours derived from minutes
    $remainingMinutes = $totalMinutes % 60; // Remaining minutes after accounting for hours

    // Add additional hours to the total hours
    $totalHours = $currentMonthTotals['total_hour'] + $additionalHours;

    // Format the result
    $formattedHours = $totalHours;
    $formattedMinutes = str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT); // Ensure minutes are two digits

    // Display the result in HH:MM format
    $formattedTime = $formattedHours . ' : ' . $formattedMinutes;
    // Dump the results

} else {

    $emp_id = $_GET['id'];
    $name = $_GET['name'];

    $date = date("Y-m-d");
    $week = date('Y-m-d', strtotime($date . ' -1 week'));

    $now = new DateTime();
    $firstDayOfCurrentMonth = new DateTime($now->format('Y-m-01'));
    $lastDayOfCurrentMonth = (clone $firstDayOfCurrentMonth)->modify('last day of this month');
    $firstDayOfLastMonth = (clone $firstDayOfCurrentMonth)->modify('first day of last month');
    $lastDayOfLastMonth = (clone $firstDayOfLastMonth)->modify('last day of this month');

    // Format dates for SQL queries
    $currentMonthStart = $firstDayOfCurrentMonth->format('Y-m-d');
    $currentMonthEnd = $lastDayOfCurrentMonth->format('Y-m-d');
    $lastMonthStart = $firstDayOfLastMonth->format('Y-m-d');
    $lastMonthEnd = $lastDayOfLastMonth->format('Y-m-d');

    $sql =  $conn->prepare("SELECT 
    du.update_id,
    du.project_id,
    du.task,
    du.date,
    du.hour,
    sum(du.hour) as total_hour,
    du.minute,
    sum(du.minute) as total_minute,
    du.commit_id,
    du.employee_id,
    p.project_name
FROM 
    daily_updates AS du
JOIN 
    project AS p
ON 
    du.project_id = p.project_id
    WHERE 
        du.employee_id = ?
         AND
        du.date >= ?
        ORDER BY 
    du.date DESC, du.hour DESC, du.minute DESC");
    $sql->bind_param("ss", $emp_id, $week);
    $sql->execute();
    $result = $sql->get_result();
    $project = $result->fetch_all(MYSQLI_ASSOC);


    $sqlCurrentMonth = $conn->prepare("
    SELECT 
        SUM(du.hour) AS total_hour,
        SUM(du.minute) AS total_minute
    FROM 
        daily_updates AS du
    JOIN 
        project AS p
    ON 
        du.project_id = p.project_id
    WHERE 
        du.employee_id = ?
        AND du.date BETWEEN ? AND ?
");
    $sqlCurrentMonth->bind_param("sss", $emp_id, $currentMonthStart, $currentMonthEnd);
    $sqlCurrentMonth->execute();
    $resultCurrentMonth = $sqlCurrentMonth->get_result();
    $currentMonthTotals = $resultCurrentMonth->fetch_assoc();

    $totalMinutes = $currentMonthTotals['total_minute'];
    $additionalHours = intdiv($totalMinutes, 60); // Hours derived from minutes
    $remainingMinutes = $totalMinutes % 60; // Remaining minutes after accounting for hours

    // Add additional hours to the total hours
    $totalHours = $currentMonthTotals['total_hour'] + $additionalHours;

    // Format the result
    $formattedHours = $totalHours;
    $formattedMinutes = str_pad($remainingMinutes, 2, '0', STR_PAD_LEFT); // Ensure minutes are two digits

    // Display the result in HH:MM format
    $formattedTime = $formattedHours . ' : ' . $formattedMinutes;
}




// var_dump($project[0]['total_hour']);

// var_dump($formattedTime);
include("sidebar.php");
// Your database queries and processing code here




if (isset($_POST['delete'])) {
    $vehicle_id_to_delete = $_POST['delete_project_id'];

    // Perform your deletion query here
    $delete_query = "DELETE FROM daily_updates WHERE update_id = $vehicle_id_to_delete";
    $result = $conn->query($delete_query);
    // var_dump($dele);
    if ($result) {
        // Deletion successful, redirect or show success message
        header("Location: dashboard.php");
        exit();
    } else {
        // Deletion failed, ;handle error
        echo "Error deleting vehicle.";
    }
}

$profile = $conn->query("SELECT * from employees where employee_id = $emp_id")->fetch_all(MYSQLI_ASSOC);

// echo  '<pre>';
// var_dump($profile);
?>
<?php
// Assuming $project is an array of records
$groupedByDate = [];
$totalsByDate = [];
// var_dump($project);
foreach ($project as $p) {
    $date = htmlspecialchars($p['date']);
    $hour = isset($p['hour']) ? (int)$p['hour'] : 0;
    $minute = isset($p['minute']) ? (int)$p['minute'] : 0;

    if (!isset($groupedByDate[$date])) {
        $groupedByDate[$date] = [];
        $totalsByDate[$date] = ['hours' => 0, 'minutes' => 0];
    }
    $groupedByDate[$date][] = $p;

    // Add to totals
    $totalsByDate[$date]['hours'] += $hour;
    $totalsByDate[$date]['minutes'] += $minute;
}

// Convert total minutes to hours and minutes
foreach ($totalsByDate as $date => &$total) {
    $totalMinutes = $total['hours'] * 60 + $total['minutes'];
    $total['hours'] = floor($totalMinutes / 60);
    $total['minutes'] = $totalMinutes % 60;
}
?>
<?php

if (isset($_SESSION['name'])) {
    // Split the full name into parts
    $nameParts = explode(' ', $profile[0]['name']);

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
<?php
// Get total hours and minutes from the project array
$total_hours = $project[0]['total_hour'];
$total_minutes = $project[0]['total_minute'];

// Convert total minutes to hours and minutes
$additional_hours = intdiv($total_minutes, 60); // Additional hours from minutes
$remaining_minutes = $total_minutes % 60; // Remaining minutes after converting to hours

// Calculate the final total hours and minutes
$final_hours = $total_hours + $additional_hours;
$final_minutes = $remaining_minutes;


$eid = $_GET['id'];
// var_dump($eid);
$select_project_id = $conn->query("SELECT project_id from project_assign where employee_id = $eid ")->fetch_all(MYSQLI_ASSOC);
// print_r('<pre>');
// var_dump($select_project_id);

$allIds = [];
foreach ($select_project_id as $pro) {
    $allIds[] = $pro['project_id'];
}


if (count($allIds) > 0) {
    $idsString = implode(',', $allIds);
    $formattedIds = "($idsString)";
    $selected_project = $conn->query("SELECT * FROM project where project_id in $formattedIds")->fetch_all(MYSQLI_ASSOC);
} else {
    $selected_project = [];
}
//   print_r('<pre>');
//  var_dump($selected_project);
$total_count = count($selected_project);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
        

<style>
 body {
    font-family: 'Arial', sans-serif;
}

.hours-container {
    display: flex;
    gap: 50px; /* Adds space between the boxes */
    padding: 20px;
}

.hours-box {
    text-align: center;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.hours-box h2 {
    font-size: 28px; /* Match the size in the photo */
    margin: 0;
    font-weight: bold;
}

.hours-box span{
    font-size: 16px; /* Smaller text for the label */
    color: gray;
    margin-block: 6px;
}
.modal-body{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

</style>
    </head>

<body>
    <div class="main-content">
        <div class="content" style="margin-top:25px;">
            <!-- Your main content goes here -->

            <div class="space">

                    <div class="spacebetween">

                        <div class="xyz">
                            <div class="firstlast">
                                <b> <?php echo htmlspecialchars($firstInitial . $lastInitial); ?></b>
                            </div>
                            <div class="usersss">

                                <b class="atoz"> <?php echo $profile[0]['name']; ?> </b>


                                <b class="atos"><?php echo $profile[0]['position']; ?></b>
                            </div>
                        </div>

                        <div class="emails">
                           
                            <b class="ay"><div style="display: flex; justify-content: end; width:100%;">
    <svg xmlns="http://www.w3.org/2000/svg" height="25px"
         viewBox="0 -960 960 960" width="25px" fill="black">
        <path
            d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
    </svg>
</div>
 <?php echo $profile[0]['email']; ?></b>
                        </div>

                    </div>

            </div>
            <hr>


            <!-- <div class="space">

                <div class="userdetails-box d-flex">
                    <div class="xyz">
                        <div class="yessh">
                            <b> <?php echo htmlspecialchars($firstInitial . $lastInitial); ?></b>
                        </div>
                        <div class="userdetails">

                            <b class="aaaa"> <?php echo $profile[0]['name']; ?> </b>


                            <b class="aaaa"><?php echo $profile[0]['position']; ?></b>
                        </div>
                    </div>

                    <div class="emails">
                        <b class="aaaa"><svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960"
                                width="50px" fill="white">
                                <path
                                    d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
                            </svg> <?php echo $profile[0]['email']; ?></b>
                    </div>

                </div>

            </div> -->














             <!-- <div class="container">
    <div class="card border-primary mb-3" style="max-width: 15rem; height:fit-content;">
        <div class="card-header">Weekly Total Hours</div>
        <div class="card-body text-primary">
            <h5 class="card-title">
                <?php echo $final_hours; ?> : <?php echo $final_minutes; ?> hr
            </h5>
        </div>
    </div>

    <div class="card border-primary mb-3" style="max-width: 15rem; height:fit-content;">
        <div class="card-header">Monthly Total Hours</div>
        <div class="card-body text-primary">
            <h5 class="card-title">
                <?php echo $formattedTime; ?> hr
            </h5>
        </div>
    </div>
</div>  -->


<div class="hours-container">
    <div data-bs-toggle="modal" data-bs-target="#exampleModal" class="hours-box">
        <h2><?php echo $total_count; ?></h2>
        <span>Total Projects  &nbsp;<a class=" btn-primary btn-sm" style="cursor: pointer;">View</a></span>
    </div>
    <div class="v-hr"></div>
    <div class="hours-box">
        <h2><?php echo $final_hours; ?> : <?php echo $final_minutes; ?> hrs</h2>
        <span>Weekly Total Hours</span>
    </div>
    <div class="v-hr"></div>
    <div class="hours-box">
        <h2><?php echo $formattedTime; ?> hrs</h2>
        <span>Monthly Total Hours</span>
    </div>
</div>

<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Total Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if (!empty($selected_project)): ?>
          <ul class="list-group">
            <?php foreach ($selected_project as $project): ?>
              <li class="list-group-item project-item">
                <i class="fas fa-project-diagram me-2"></i>
                <?php echo htmlspecialchars($project['project_name']); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php else: ?>
          <div class="text-center">
            <p>No projects available</p>
          </div>
        <?php endif; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<style>
  .project-item {
    border: none; /* Remove the side borders */
    width: 100%; /* Adjust width as needed */
    font-size: 1.2rem; /* Increase font size */
  }
</style>






<hr>



            <label for="project">Select a Project:</label>
            <select name="project" id="project">
                <?php
                // Assuming $selected_project is fetched and available
                if (!empty($selected_project)) {
                    foreach ($selected_project as $project) {
                        echo '<option value="' . htmlspecialchars($project['project_id']) . '">'
                            . htmlspecialchars($project['project_name']) . '</option>';
                    }
                } else {
                    echo '<option value="">No projects available</option>';
                }
                ?>
            </select>








            <div class="view">

                <button id="toggleButton" class="btn btn-success" onclick="toggleVisibility()"> <svg
                        xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                        fill="white">
                        <path
                            d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z" />
                    </svg><b>View</b></button>
            </div>
            <div class="startenddate" id="start-and-end-date">
                <form method="post">
                    <label for="start_date"><b>Start Date : </b></label>
                    <input type="date" id="start_date" name="start_date"
                        value="<?php echo isset($_POST['start_date']) ? ($_POST['start_date']) : ''; ?>" required
                        class="date-input">

                    <label for="end_date"><b>End Date : </b></label>
                    <input type="date" id="end_date" name="end_date"
                        value="<?php echo isset($_POST['end_date']) ? ($_POST['end_date']) : ''; ?>" required
                        class="date-input">
                    <input type="hidden" name='emp_id' value='<?php if (isset($_GET['id'])) {
                                                                    echo $_GET['id'];
                                                                } else {
                                                                    echo $_POST['emp_id'];
                                                                } ?>' />

                    <input type="hidden" name='name' value='<?php if (isset($_GET['name'])) {
                                                                echo $_GET['name'];
                                                            } else {
                                                                echo $_POST['name'];
                                                            } ?>' />

                    <input type="submit" name='date_view' value="Filter" class="filter-button">
                </form>
            </div>

            <div id="main-table">

                <table id="vehicleTable" class="table table-striped">
                    <thead>


                        <tr>

                            <th>UPDATE</th>
                            <th>TIME</th>
                            <th>COMMIT ID</th>
                            <th>PROJECT</th>


                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($groupedByDate as $date => $records) { ?>
                        <!-- Date Header Row -->
                        <tr class="date-header">
                        <tr>
                            <td colspan="6" style="color: #00215E;">
                                <strong>Date: </strong><?php echo $date; ?>&nbsp; &nbsp; <strong>Total hours:</strong>
                                <?php echo sprintf('%02d:%02d', $totalsByDate[$date]['hours'], $totalsByDate[$date]['minutes']); ?>
                            </td>
                        </tr>




                        </tr>
                        <!-- Data Rows for the Current Date -->
                        <?php foreach ($records as $p) { ?>
                        <tr>

                            <td><?php echo nl2br(($p['task'])); ?></td>
                            <td><?php echo htmlspecialchars($p['hour']) . ':' . htmlspecialchars($p['minute']); ?></td>
                            <td><?php echo htmlspecialchars($p['commit_id']); ?></td>
                            <td><?php echo htmlspecialchars($p['project_name']); ?></td>









                            <div class="modal fade" id="editMeetingModal<?php echo $p['update_id']; ?>" tabindex="-1"
                                aria-labelledby="createMeetingModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <span></span>
                                            <h3 class="modal-title" id="createMeetingModalLabel"><b>Edit Daily
                                                    Update</b>
                                            </h3>
                                            <svg data-bs-dismiss="modal" aria-label="Close"
                                                xmlns="http://www.w3.org/2000/svg" height="30px"
                                                viewBox="0 -960 960 960" width="30px" fill="#565656"
                                                style="cursor:pointer;">
                                                <path
                                                    d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                                            </svg>

                                        </div>
                                        <form class="modal-form" method="POST" action="dashboard.php">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="md-6">
                                                        <div class="mb-3">
                                                            <label for="date" class="form-label">Select Date</label>
                                                            <input type="date" name="date" class="form-control"
                                                                value="<?php echo $p['date']; ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="md-6">
                                                        <div class="mb-3">
                                                            <label for="project" class="form-label">Select
                                                                Project</label>
                                                            <select name="projects" id="projects" class="form-select"
                                                                required>
                                                                <option value="<?php echo $p['project_id']; ?>" disabled
                                                                    selected>Select project</option>
                                                                <?php foreach ($projects as $s): ?>
                                                                <option <?php if ($s['project_id'] == $p['project_id']) {
                                                                                        echo 'selected';
                                                                                    } ?>
                                                                    value="<?= $s['project_id'] ?>">
                                                                    <?= $s['project_name'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div id="input-container">
                                                    <!-- Initial task-time pair -->
                                                    <div class="row task-time-container">
                                                        <div class="md-6">
                                                            <div class="mb-3">
                                                                <label for="task" class="form-label">Enter task
                                                                    description</label>
                                                                <textarea name="task" class="form-control" id="task-1"
                                                                    required><?php echo htmlspecialchars($p['task']); ?></textarea>
                                                            </div>

                                                        </div>

                                                        <div class="my-row">
                                                            <div class="mb-3">
                                                                <label for="hours" class="form-label">Select
                                                                    hours</label>
                                                                <select name="hours" class="form-select" id="time-1"
                                                                    required>
                                                                    <option value="" disabled selected>Select Time
                                                                        duration
                                                                    </option>
                                                                    <option value="0"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 0) ? 'selected' : ''; ?>>
                                                                        0</option>
                                                                    <option value="1"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 1) ? 'selected' : ''; ?>>
                                                                        1</option>
                                                                    <option value="2"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 2) ? 'selected' : ''; ?>>
                                                                        2</option>
                                                                    <option value="3"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 3) ? 'selected' : ''; ?>>
                                                                        3</option>
                                                                    <option value="4"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 4) ? 'selected' : ''; ?>>
                                                                        4</option>
                                                                    <option value="5"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 5) ? 'selected' : ''; ?>>
                                                                        5</option>
                                                                    <option value="6"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 6) ? 'selected' : ''; ?>>
                                                                        6</option>
                                                                    <option value="7"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 7) ? 'selected' : ''; ?>>
                                                                        7</option>
                                                                    <option value="8"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 8) ? 'selected' : ''; ?>>
                                                                        8</option>
                                                                    <option value="9"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 9) ? 'selected' : ''; ?>>
                                                                        9</option>
                                                                    <option value="10"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 10) ? 'selected' : ''; ?>>
                                                                        10</option>
                                                                    <option value="11"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 11) ? 'selected' : ''; ?>>
                                                                        11</option>
                                                                    <option value="12"
                                                                        <?php echo (isset($p['hour']) && $p['hour'] == 12) ? 'selected' : ''; ?>>
                                                                        12</option>
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="minutes" class="form-label">Select
                                                                    minutes</label>
                                                                <select name="minutes" class="form-select" id="time-1"
                                                                    required>
                                                                    <option value="" disabled selected>Select Time
                                                                        duration
                                                                    </option>
                                                                    <option value="00"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '00') ? 'selected' : ''; ?>>
                                                                        00</option>
                                                                    <option value="05"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '05') ? 'selected' : ''; ?>>
                                                                        05</option>
                                                                    <option value="10"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '10') ? 'selected' : ''; ?>>
                                                                        10</option>
                                                                    <option value="15"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '15') ? 'selected' : ''; ?>>
                                                                        15</option>
                                                                    <option value="20"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '20') ? 'selected' : ''; ?>>
                                                                        20</option>
                                                                    <option value="25"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '25') ? 'selected' : ''; ?>>
                                                                        25</option>
                                                                    <option value="30"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '30') ? 'selected' : ''; ?>>
                                                                        30</option>
                                                                    <option value="35"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '35') ? 'selected' : ''; ?>>
                                                                        35</option>
                                                                    <option value="40"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '40') ? 'selected' : ''; ?>>
                                                                        40</option>
                                                                    <option value="45"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '45') ? 'selected' : ''; ?>>
                                                                        45</option>
                                                                    <option value="50"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '50') ? 'selected' : ''; ?>>
                                                                        50</option>
                                                                    <option value="55"
                                                                        <?php echo (isset($p['minute']) && $p['minute'] == '55') ? 'selected' : ''; ?>>
                                                                        55</option>
                                                                </select>
                                                            </div>


                                                        </div>

                                                        <div class="md-6">
                                                            <div class="mb-3">
                                                                <label for="task" class="form-label">Enter task
                                                                    commit
                                                                    id</label>
                                                                <input type="text" name="commit_id" class="form-control"
                                                                    value="<?php echo $p['commit_id']; ?>" id="task-1">
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <input type="hidden" name="update_id"
                                                        value="<?php echo $p['update_id']; ?>">
                                                    <div>
                                                        <button type="submit" name="editupdate"
                                                            class="btn it">Submit</button>
                                                    </div>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Includ Delete Confirmation Modal -->
                            </span>
                            </td>
                        </tr>
                        <tr class="total-row">
                            <!-- <td colspan="6">
                    <strong>Total Hours for <?php echo $date; ?>:</strong>
                    <?php echo sprintf('%02d:%02d', $totalsByDate[$date]['hours'], $totalsByDate[$date]['minutes']); ?>
                </td> -->
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
















        <div class="modal fade" id="createMeetingModal" tabindex="-1" aria-labelledby="createMeetingModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <span></span>
                        <h3 class="modal-title" id="createMeetingModalLabel"><b>Submit Update</b> </h3>
                        <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="30px"
                            viewBox="0 -960 960 960" width="30px" fill="#565656" style="cursor:pointer;">
                            <path
                                d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                        </svg>

                    </div>
                    <form class="modal-form" method="POST" action="addupdates2.php">
                        <div class="modal-body">
                            <div class="row">
                                <div class="md-6">
                                    <div class="mb-3">
                                        <label for="date" class="form-label">Select Date</label>
                                        <input type="date" name="date" class="form-control" placeholder="dd-mm-yyyy"
                                            required>
                                    </div>
                                </div>
                                <div class="md-6">
                                    <div class="mb-3">
                                        <label for="project" class="form-label">Select Project</label>
                                        <select name="projects" id="projects" class="form-select" required>
                                            <option value="" disabled selected>Select project</option>
                                            <?php foreach ($projects as $p): ?>
                                            <option value="<?= $p['project_id'] ?>"><?= $p['project_name'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div id="input-container">
                                <!-- Initial task-time pair -->
                                <div class="row task-time-container">
                                    <div class="md-6">
                                        <div class="mb-3">
                                            <label for="task" class="form-label">Enter task description</label>
                                            <textarea name="task[]" class="form-control" id="task-1"
                                                placeholder="Enter task" required></textarea>
                                        </div>

                                    </div>

                                    <div class="my-row">
                                        <div class="mb-3">
                                            <label for="hours" class="form-label">Select hours</label>
                                            <select name="hours[]" class="form-select" id="time-1" required>
                                                <option value="" disabled selected>Select Time duration</option>
                                                <!-- Generate options from 1 to 12 -->
                                                <option value="0">0</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12">12</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="minutes" class="form-label">Select minutes</label>
                                            <select name="minutes[]" class="form-select" id="time-1" required>
                                                <option value="" disabled selected>Select minute duration</option>
                                                <!-- Generate options from 5 to 55 in increments of 5 -->
                                                <option value="00">00</option>
                                                <option value="05">05</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="25">25</option>
                                                <option value="30">30</option>
                                                <option value="35">35</option>
                                                <option value="40">40</option>
                                                <option value="45">45</option>
                                                <option value="50">50</option>
                                                <option value="55">55</option>
                                            </select>
                                        </div>


                                    </div>

                                    <div class="md-6">
                                        <div class="mb-3">
                                            <label for="task" class="form-label">Enter task commit id</label>
                                            <input type="text" name="commit_id[]" class="form-control" id="task-1"
                                                placeholder="Enter commit id">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-2">
                                <div class="mb-3">
                           
                                <button type="button"  class="btn  btn-danger ites delete-btn" >
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"  fill="white" class="icon">
        <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
    </svg>
 
</button></div> -->
                                </div>
                            </div>

                        </div>
                        <!-- <div class="hi">
                        <button type="button" id="add-inputss-btn" class="btn its" style="border:none; color:blue;">
                            <b> Add Task </b>
                        </button>
                    </div> -->
                        <div>
                            <button type="submit" name="submit" class="btn it">Submit</button>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div id="chart"></div>
    <!-- Include modal form -->
    <!-- Your modal code here -->
    <script>
    let is_open = <?php echo $filter ? 'true' : 'false'; ?>;
    var myTaskDiv = document.getElementById('main-table');
    var myTaskDiv2 = document.getElementById('start-and-end-date');

    if (is_open) {
        myTaskDiv.style.display = 'block';
        myTaskDiv2.style.display = 'flex';
    } else {
        myTaskDiv.style.display = 'none';
        myTaskDiv2.style.display = 'none';
    }

    function toggleVisibility() {
        if (myTaskDiv.style.display === 'none') {
            myTaskDiv.style.display = 'block';
        } else {
            myTaskDiv.style.display = 'none';
        }

        if (myTaskDiv2.style.display === 'none') {
            myTaskDiv2.style.display = 'flex';
        } else {
            myTaskDiv2.style.display = 'none';
        }
    }
    </script>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
    $(".addid").on("change", function() {
        this.setAttribute(
            "data-date",
            this.value
        )
        console.log(this.value)
    }).trigger("change")
    </script>


    <script>
    let taskCount = 1;

    function addTaskTimeFields() {
        taskCount++;
        const container = document.getElementById('input-container');
        const newTaskTimeContainer = document.createElement('div');
        newTaskTimeContainer.className = 'row task-time-container';

        const taskDiv = document.createElement('div');
        taskDiv.className = 'col-md-4 qw';
        taskDiv.innerHTML = `
        <div class="mb-3">
            <label for="task-${taskCount}" class="form-label">Enter task description</label>
            <input type="text" name="task[]" class="form-control" id="task-${taskCount}" placeholder="Enter task" required>
        </div>
        `;

        const hoursDiv = document.createElement('div');
        hoursDiv.className = 'col-md-3 er';
        hoursDiv.innerHTML = `
        <div class="mb-3 yashh">
            <label for="hours-${taskCount}" class="form-label">Enter hours</label>
            <input type="text" name="hours[]" class="form-control" id="hours-${taskCount}" placeholder="Enter hours" required>
        </div>
        `;

        const minutesDiv = document.createElement('div');
        minutesDiv.className = 'col-md-3 er';
        minutesDiv.innerHTML = `
        <div class="mb-3">
            <label for="minutes-${taskCount}" class="form-label">Enter minutes</label>
            <input type="text" name="minutes[]" class="form-control" id="minutes-${taskCount}" placeholder="Enter minutes" required>
        </div>
        `;

        const commentDiv = document.createElement('div');
        commentDiv.className = 'col-md-4 yt';
        commentDiv.innerHTML = `
        <div class="mb-3">
            <label for="comment-${taskCount}" class="form-label">Enter task commit id</label>
            <input type="text" name="commit_id[]" class="form-control" id="comment-${taskCount}" placeholder="Enter commit id" required>
        </div>
        `;

        const deleteDiv = document.createElement('div');
        deleteDiv.className = 'col-md-1 ty';
        deleteDiv.innerHTML = `
        <div class="mb-3">
            <button type="button" class="btn btn-danger delete-btn">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="white" class="icon">
                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                </svg>
            </button>
        </div>
        `;

        newTaskTimeContainer.appendChild(taskDiv);
        newTaskTimeContainer.appendChild(hoursDiv);
        newTaskTimeContainer.appendChild(minutesDiv);
        newTaskTimeContainer.appendChild(commentDiv);
        newTaskTimeContainer.appendChild(deleteDiv);

        container.appendChild(newTaskTimeContainer);
    }

    function handleDeleteClick(event) {
        if (event.target.closest('.delete-btn')) {
            const button = event.target.closest('.delete-btn');
            const container = button.closest('.task-time-container');
            container.remove();
        }
    }

    document.getElementById('input-container').addEventListener('click', handleDeleteClick);
    document.getElementById('add-inputss-btn').addEventListener('click', addTaskTimeFields);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    var colors = ["black"]
    var options = {
        series: [{
            data: [21, 22, 10, 28, 16, 21, 13, 30]
        }],
        chart: {
            height: 350,
            type: 'bar',
            events: {
                click: function(chart, w, e) {
                    // console.log(chart, w, e)
                }
            }
        },
        colors: colors,
        plotOptions: {
            bar: {
                columnWidth: '45%',
                distributed: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: [
                ['John', 'Doe'],
                ['Joe', 'Smith'],
                ['Jake', 'Williams'],
                'Amber',
                ['Peter', 'Brown'],
                ['Mary', 'Evans'],
                ['David', 'Wilson'],
                ['Lily', 'Roberts'],
            ],
            labels: {
                style: {
                    colors: colors,
                    fontSize: '12px'
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    // chart.render();
    </script>
</body>

</html>