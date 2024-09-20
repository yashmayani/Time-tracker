<?php

session_start();

 include("./config.php");
 include("./navbar.php");
 include("./sidebar.php");
 $projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);



 


//    print_r('<pre>');
// var_dump($projects);
 ?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
        
.btn-outline-primary {
    color: blue;
    /* Text color */
    border-color: blue;
    /* Border color */
}

/* SVG icon color */
.btn-outline-primary .icon {
    fill: blue;
    /* Default icon color */
    transition: fill 0.3s;
    /* Smooth transition for color change */
}

/* Change icon color on button hover, focus, and active */
.btn-outline-primary:hover .icon,
.btn-outline-primary:focus .icon,
.btn-outline-primary:active .icon {
    fill: white;
    /* Icon color on hover/focus/active */
}

/* Optional: change text color on hover/focus/active */
.btn-outline-primary:hover,
.btn-outline-primary:focus,
.btn-outline-primary:active {
    color: white;
    /* Text color on hover/focus/active */
}
#auto-dismiss-alert{
    width: 320px;
    position: absolute;
    top: 10px;
    left: 50%;
    z-index: 100000;
    transform: translateX(-50%);
 }
        </style>
</head>

<body>


    <div class="main-content">

        <div class="content">
            <div class="space">
                <b class="a">Completed Project</b>

            </div>
        </div>
        <?php 
    if (isset($_SESSION['message'])) {
        // Determine the alert type based on the message
        $alertType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info'; // Default to 'info'
        echo "<div id='auto-dismiss-alert' class='alert alert-$alertType alert-dismissible fade show' role='alert'>
                {$_SESSION['message']}
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
              </div>";
        // Clear the message and type from the session
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
?>
        <div id="main-table">
            <table id="projectTable" class="table ">
                <thead>
                    <tr>
                        <th>PROJECT ID</th>
                        <th>PROJECT NAME</th>
                        <th>CLIENT NAME</th>
                        <?php if ($_SESSION['role']==1) {?>
                            <th>ACTION</th>
                            <?php } ?>
                    </tr>
</thead>
<tbody>
    <?php foreach($projects as $p) { ?>
        <tr>
            <?php if ($p['status']==1){?>
                <td><?php echo htmlspecialchars($p['project_id']); ?></td>
                <td><?php echo htmlspecialchars($p['project_name']); ?></td>
                <td><?php echo htmlspecialchars($p['client_name']); ?></td>
            <?php } ?>
            <?php if ($p['status']==1 ){?>
            <td class="spaces">
            <div class="dropdown">
                                    <a class="btn" data-bs-toggle="dropdown">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                            width="24px" fill="#5f6368">
                                            <path
                                                d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                                        </svg>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item"
                                                href="view_project.php?id=<?php echo htmlspecialchars($p['project_id']); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                    viewBox="0 -960 960 960" width="24px" class="icon">
                                                    <path
                                                        d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z" />
                                                </svg>
                                                <b style="margin-left: 5px;">View</b>
                                            </a></li>
                                            <li><a class="dropdown-item" href="#" onclick="func(e)"
                                                class="btn btn-outline-success " data-bs-toggle="modal"
                                                data-bs-target="#editMeetingModal<?php echo $p['project_id']; ?>"><svg
                                                    xmlns="http://www.w3.org/2000/svg" height="24px"
                                                    viewBox="0 -960 960 960" width="24px" class="icon">
                                                    <path
                                                        d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                                </svg><b style="margin-left:7px;">Edit</b></a></li>
                                        <li><a class="dropdown-item" href="#" button type="button"
                                                class="btn btn-outline-danger delete-btn"
                                                data-id="<?php echo $p['project_id']; ?>" data-bs-toggle="modal"
                                                data-bs-target="#deleteConfirmationModal<?php echo $p['project_id']; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                    viewBox="0 -960 960 960" width="24px" class="icon">
                                                    <path
                                                        d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                </svg>
                                                <b style="margin-left:5px;">Delete</b>
                                                </button></a></li>
                                                <?php if ($_SESSION['role'] == 1 && $p['status'] == 1) { ?>
                                                    
                                                    <li><a class="dropdown-item" 
                                        href="update_completed_project2.php?id=<?php echo htmlspecialchars($p['project_id']); ?> ">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="black"><path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm0-560v560-560Z"/></svg>
                                       <b style="margin-left:5px;"> Mark as uncompleted</b>
                                    </a></li>
                                    <?php } ?>
                                                </ul>
                                </div>



<?php }?>
<div class="modal fade" id="editMeetingModal<?php echo $p['project_id']; ?>"
                                    tabindex="-1" aria-labelledby="editMeetingModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content update-modal2">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="editMeetingModalLabel">Update Project</h3>
                                                <svg data-bs-dismiss="modal" aria-label="Close"
                                                    xmlns="http://www.w3.org/2000/svg" height="30px"
                                                    viewBox="0 -960 960 960" width="30px" fill="#565656"
                                                    style="cursor:pointer;">
                                                    <path
                                                        d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                                                </svg>
                                            </div>

                                            <form class="modal-form" method="POST" action="addprojects2.php">
                                                <div class="modal-body">
                                                    <div class="row">

                                                        <div class="md-6">
                                                            <div class="mb-3">
                                                                <label for="project" class="form-label">Add
                                                                    Project</label>
                                                                <input type="text" name="project" class="form-control"
                                                                    value="<?php echo $p['project_name']; ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="project" class="form-label">Client
                                                                    Name</label>
                                                                <input type="text" name="client" class="form-control"
                                                                    value="<?php echo $p['client_name']; ?>" required>
                                                            </div>
                                                            <div class="row">
                                                                <div class="mb-3 col">
                                                                    <label for="project" class="form-label">Project
                                                                        Start
                                                                        Date
                                                                    </label>
                                                                    <input type="date" name="project_start_date"
                                                                        class="form-control"
                                                                        value="<?php echo $p['start_date']; ?>">
                                                                </div>
                                                                <div class="mb-3 col">
                                                                    <label for="project" class="form-label">Project End
                                                                        Date
                                                                    </label>
                                                                    <input type="date" name="project_end_date"
                                                                        class="form-control"
                                                                        value="<?php echo $p['end_date']; ?>">
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="project" class="form-label">Platform
                                                                </label>
                                                                <input type="text" name="platform" class="form-control"
                                                                    value="<?php echo $p['platform']; ?>" required>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <input type="hidden" name="project_id"
                                                        value="<?php echo $p['project_id']; ?>">
                                                    <div>
                                                        <button type="submit" name="editproject"
                                                            class="btn it">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>




                                <!-- Delete Confirmation Modal -->

                                <div class="modal fade" id="deleteConfirmationModal<?php echo $p['project_id']; ?>"
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
                                                        <p>Are you sure want to delete this project?</p>


                                            </div>
                                            <div class="yash">
                                                <form method="POST" action="deleteproject2.php"
                                                    style="display: flex; gap: 15px;">

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <input type="hidden" name="delete_project_id"
                                                        value="<?php echo $p['project_id']; ?>">
                                                    <button type="submit" class="btn btn-danger">Delete</button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                          
                            <?php } ?>

                        </tr>



            </tbody>
            </table>

            <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the alert element
        var alertElement = document.getElementById('auto-dismiss-alert');
        
        if (alertElement) {
            // Set a timeout to dismiss the alert after 2 seconds
            setTimeout(function() {
                // Remove the alert element
                $(alertElement).alert('close');
            }, 2000); // 2000 milliseconds = 2 seconds
        }
    });
</script>
    
</script>

                    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
                integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
                crossorigin="anonymous"></script>

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
                integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
                crossorigin="anonymous"></script>
</body>

</html>