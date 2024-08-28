<?php
session_start(); // Start session to store messages

include("./config.php");

// Fetch projects from the database
$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);
// print_r('<pre>');
// var_dump($projects);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $project = $_POST['projects'];

    // Iterate through dynamically added tasks
    $tasks = [];
    $times = [];

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'task-') === 0) {
            $tasks[] = $value;
        }
        if (strpos($key, 'time-') === 0) {
            $times[] = $value;
        }
    }

    // Example of processing tasks and times
    foreach ($tasks as $index => $task) {
        $time = isset($times[$index]) ? $times[$index] : ''; // Ensure time matches task
        // Save $date, $project, $task, and $time to the database
        // Example query (ensure you handle SQL injection and use prepared statements)
        $stmt = $conn->prepare("INSERT INTO daily_updates (date, project_id, task, time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $date, $project, $task, $time);
        $stmt->execute();
    }

    // Redirect or display a success message
    echo "";
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>Daily Updates</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
    .btn.added {
        background-color: #3365da;
        color: white;                                                        
    }

    .modal-header {
        background-color: white;
        border-bottom: 1px solid #dee2e6;
        border-bottom:none;
        
    }

    .modal-title {
        color: black;
        margin-top:29px;
        margin-left:110px;
      
    

    }

    .btn.it {
        background-color: black;
        color: white;
    }

    .modal-body {
        padding: 0;
    }

    .form-control,
    .form-select {
        border-radius: 0.25rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }

    .btn.it {
        width: 100%;
        margin-top: 25px;
    }

    .btn.its {
        /* margin-left: 410px; */
      padding:0 !important;
    }

    .btn.ites{
        color:#3365da;
        margin-top:32px;
    }

    .modal-dialog-centered {
        margin: 1.75rem auto;
    }
    .modal-content{
   
        width:83%!important;

    }
    .hi{
        position: relative;
        
        display:flex;
        justify-content:end;
    }
    .modal-form{
        padding:30px;
    }
    </style>
</head>

<body>
    <a href="#" class="btn added btn-sm" data-bs-toggle="modal" data-bs-target="#createMeetingModal"
        style="width: 75px; height: 35px;">Add +</a>

    <div class="modal fade" id="createMeetingModal" tabindex="-1" aria-labelledby="createMeetingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="createMeetingModalLabel">Add Update </h3>
                    <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="30px"
                        viewBox="0 -960 960 960" width="30px" fill="#565656" style="cursor:pointer; margin-top:-40px";>
                        <path
                            d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>
                   
                </div>
                <form class="modal-form" method="POST" action="addupdates2.php">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" id="date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="projects" class="form-label">Projects</label>
                                    <select name="projects" id="projects" class="form-select" required>
                                        <option value="" disabled selected>Select project</option>
                                        <?php foreach ($projects as $p): ?>
                                        <option value="<?= $p['project_id'] ?>"><?= $p['project_name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <!-- <hr> -->
                        <div id="input-container">
                            <!-- Initial task-time pair -->
                            <div class="row task-time-container">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="task-1" class="form-label">Task</label>
                                        <input type="text" name="task" class="form-control" id="task-1"
                                            placeholder="Enter task" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="time-1" class="form-label">Time</label>
                                        <input type="text" name="time" class="form-control" id="time-1"
                                            placeholder="Enter Time duration" required>
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
                        <div class="hi">
                            <button type="button" id="add-input-btn" class="btn its" style="border:none; color:blue;">
                                        <b> Add Task </b>
                            </button>
                        </div>
                        <div>
                            <button type="submit" name="submit" class="btn it">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
<script>
let taskCount = 1;

// Function to add new task-time fields
function addTaskTimeFields() {
    taskCount++; // Increment task count

    const container = document.getElementById('input-container');
    const newTaskTimeContainer = document.createElement('div');
    newTaskTimeContainer.className = 'row task-time-container';

    const taskDiv = document.createElement('div');
    taskDiv.className = 'col-md-5';
    taskDiv.innerHTML = `
        <div class="mb-3">
            <label for="task-${taskCount}" class="form-label">Task</label>
            <input type="text" name="task" class="form-control" id="task-${taskCount}" placeholder="Enter Your Task" required>
        </div>
    `;

    const timeDiv = document.createElement('div');
    timeDiv.className = 'col-md-5';
    timeDiv.innerHTML = `
        <div class="mb-3">
            <label for="time-${taskCount}" class="form-label">Time</label>
            <input type="text" name="time" class="form-control" id="time-${taskCount}" placeholder="Enter Your Time" required>
        </div>
    `;

    const deleteDiv = document.createElement('div');
    deleteDiv.className = 'col-md-2';
    deleteDiv.innerHTML = `
        <div class="mb-3">
            <button type="button" class="btn btn-danger ites delete-btn">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="white" class="icon">
                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                </svg>
            </button>
        </div>
    `;

    newTaskTimeContainer.appendChild(taskDiv);
    newTaskTimeContainer.appendChild(timeDiv);
    newTaskTimeContainer.appendChild(deleteDiv);

    container.appendChild(newTaskTimeContainer);
}

// Function to handle the deletion of task-time fields
function handleDeleteClick(event) {
    if (event.target.closest('.delete-btn')) {
        const button = event.target.closest('.delete-btn');
        const container = button.closest('.task-time-container');
        container.remove();
    }
}

// Attach event listener to the container to delegate the delete action
document.getElementById('input-container').addEventListener('click', handleDeleteClick);

// Attach event listener to the add button
document.getElementById('add-input-btn').addEventListener('click', addTaskTimeFields);
</script>

</body>

</html>