<?php
session_start(); // Start session to store messages
$current_page = basename($_SERVER['PHP_SELF']);

include("./config.php");

$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);
// var_dump($project);



if(isset($_POST['editupdate'])){
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
include("navbar.php");
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
        // Deletion failed, handle error
        echo "Error deleting vehicle.";
    }                                                           
 }
 
?>
<?php
// Assuming $project is an array of records
$groupedByDate = [];
$totalsByDate = [];

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


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <div class="main-content">
        <div class="content">
            <!-- Your main content goes here -->

            <div class="space">
                <b class="aaaa">Daily Update</b>
                <a href="#" class="btn added btn-sm" data-bs-toggle="modal" data-bs-target="#createMeetingModal">Add
                    Update</a>
            </div>
        </div>
        <div id="main-table">
            <table id="vehicleTable" class="table table-striped">
                <thead>
                    <tr>

                        <th>UPDATE</th>
                        <th>TIME</th>
                        <th>COMMIT ID</th>
                        <th>PROJECT</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupedByDate as $date => $records) { ?>
                    <!-- Date Header Row -->
                    <tr class="date-header">
                    <tr>
                        <td colspan="6">

                            <strong>DATE: </strong><?php echo $date; ?>&nbsp; &nbsp; <strong>TOTAL HOUR:</strong>
                            <?php echo sprintf('%02d:%02d', $totalsByDate[$date]['hours'], $totalsByDate[$date]['minutes']); ?>
                        </td>
                    </tr>




                    </tr>
                    <!-- Data Rows for the Current Date -->
                    <?php foreach ($records as $p) { ?>
                    <tr>

                        <td><?php echo htmlspecialchars($p['task']); ?></td>
                        <td><?php echo htmlspecialchars($p['hour']) . ':' . htmlspecialchars($p['minute']); ?></td>
                        <td><?php echo htmlspecialchars($p['commit_id']); ?></td>
                        <td><?php echo htmlspecialchars($p['project_name']); ?></td>






                        <td class="spaces">
                        <a href="#" onclick="func(e)" class="btn btn-outline-success " data-bs-toggle="modal"
                                data-bs-target="#editMeetingModal<?php echo $p['update_id']; ?>"><svg
                                    xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px" class="icon">
                                    <path
                                        d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                </svg><b>Edit</b></a>
                            <button type="button" class="btn btn-outline-danger delete-btn"
                                data-id="<?php echo $p['update_id']; ?>" data-bs-toggle="modal"
                                data-bs-target="#deleteConfirmationModal<?php echo $p['update_id']; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                    width="24px" class="icon">
                                    <path
                                        d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                </svg>
                                Delete
                            </button>


                            <div class="modal fade" id="deleteConfirmationModal<?php echo $p['update_id']; ?>"
                                tabindex="-1" aria-labelledby="editMeetingModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content delete-modal2">
                                        <div class="modal ">


                                        </div>
                                        <div class="modal-body delete-modal">

                                            <center>
                                                <div class="yass">
                                                    <svg xmlns="http://www.w3.org/2000/svg" height="30px"
                                                        viewBox="0 -960 960 960" width="30px" fill="red"
                                                        style="background-color:#ebecef; border-radius: 10px !important;  width: 30px; height: 30px;">
                                                        <path
                                                            d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                    </svg>
                                                </div>
                                                <center>
                                                    <p style=color:red;><b>Delete</b></p>
                                                    <p>Are you want to sure delete update?</p>


                                        </div>
                                        <div class="yash">
                                            <form method="POST" action="deleteupdate.php" style="display: flex; gap: 15px;">

                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <input type="hidden" name="delete_project_id"
                                                    value="<?php echo $p['update_id']; ?>">
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                           


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
                                                                <option
                                                                    <?php if($s['project_id']==$p['project_id']){echo 'selected';} ?>
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
                                                                <label for="task" class="form-label">Enter task commit
                                                                    id</label>
                                                                <input type="text" name="commit_id" class="form-control"
                                                                    value="<?php echo $p['commit_id']; ?>" id="task-1"
                                                                    required>
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
                                            placeholder="Enter commit id" required>
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
    <!-- Include modal form -->
    <!-- Your modal code here -->


    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
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

</body>

</html>