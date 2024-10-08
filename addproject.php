<?php
session_start();



include("./config.php");


$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);
//  print_r('<pre>');
// var_dump($projects);
$eid=$_SESSION['employee_id'];

$select_project_id = $conn->query("SELECT project_id from project_assign where employee_id = $eid ")->fetch_all(MYSQLI_ASSOC);
// print_r('<pre>');
// var_dump($select_project_id);

$allIds = [];
foreach ($select_project_id as $project) {
    $allIds[] = $project['project_id'];
}

if(isset($_POST['editproject'])){
    $project_name = $_POST['project'];
    $client = $_POST['client'];
    $project_start_date = $_POST['project_start_date'];
    $project_end_date = $_POST['project_end_date'];
    $platform = $_POST['platform'];
    $id = $_POST['project_id'];

    $updateproject = $conn->query("UPDATE project SET project_name = '$project_name', client_name = '$client', start_date = '$project_start_date', end_date = '$project_end_date', platform = '$platform' WHERE project_id = '$id'");
// var_dump($updateprojects);
if ($updateproject) {
    header("Location: addproject.php");
    exit; // Ensure script stops after redirection
} else {
    echo "Error updating meeting.";
}

}

include("navbar.php");
include("sidebar.php"); 

             
if (isset($_POST['delete'])) {
    $vehicle_id_to_delete = $_POST['delete_project_id'];

    // Perform your deletion query here
    $delete_query = "DELETE FROM project WHERE project_id = $vehicle_id_to_delete";
    $result = $conn->query($delete_query);
    // var_dump($dele);
    if ($result) {
        // Deletion successful, redirect or show success message
        header("Location: addproject.php");
        exit();
    } else {
        // Deletion failed, handle error
        echo "Error deleting vehicle.";
    }
 }
 
 if(count($allIds)>0){
    $idsString = implode(',', $allIds);
    $formattedIds = "($idsString)";
    $projects = $conn->query("SELECT * FROM project where project_id in $formattedIds")->fetch_all(MYSQLI_ASSOC);
}
else{
    $projects=[];
}
if (($_SESSION['role']==1)) {
    $projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);
}

 if (isset($_POST['submit'])) {
     $project = trim($_POST['project']);
 
     if (empty($project)) {
         $_SESSION['message'] = "Project name cannot be empty.";
         header("Location: addproject.php");
         exit();
     }
 
     // Prepare and execute insertion query
     $stmt = $conn->prepare("INSERT INTO project (project_name) VALUES (?)");
     $stmt->bind_param("s", $project);
 
     if ($stmt->execute()) {
         $_SESSION['message'] = "Project added successfully.";
     } else {
         $_SESSION['message'] = "Error adding project: " . $stmt->error;
     }
 
     $stmt->close();
     $conn->close();
 
     header("Location: addproject.php");
     exit();
 }
?>



















<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');
body *{
  font-family: "Montserrat", sans-serif !important;
}
/* styles.css */

/* Basic reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Sidebar styles */
.sidebar {
    width: 250px;
    height: 100vh;
    background-color: white;
    color: black;
    position: fixed;
    left: 0;
    top: 0;
    padding-left: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-right: 1px solid rgba(146, 141, 141, 0.651) !important;
}

.logo {
    font-size: 24px;
    margin-bottom: 20px;
}

.sidebar-item {
    margin: 10px 0;
    font-size: 18px;
    cursor: pointer;

}

.sidebar-item:hover {
    background-color: rgba(231, 185, 0, 1);
    padding: 10px;
    border-radius: 5px;
}

/* Main content styles */
.main-content {
    margin-left: 250px;
    margin-top: 70px;
    background-color: #fff;
    overflow-y: auto;
}

/* Navbar styles */
.navbar {
    background-color: white;
    color: rgba(30, 30, 30, 1);
    padding-block: 15px !important;
    font-size: 45px;
    text-align: start;
    margin-left: 250px;
    --bs-navbar-padding-y: 0px !important;
}

.navbar-item {
    background-color: white;
    margin-left: 26px;
    font-size: 24px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Content area */
/* .content {
    padding: 20px;
} */

.hi {
    border: -4px solid rgba(255, 255, 255, 1);
}

.name {
    color: rgba(0, 113, 218, 1);
    /* Color for the PHP-generated name */
    font-weight: bold;
    font-size: 46px;
}

.main-nav {
    border-bottom: 1px solid rgba(146, 141, 141, 0.651) !important;
}


.btn.added {
    width: 150px;
    height: 35px;
    background-color: #161616 !important;
    /* Background color for the button */
    color: #fff;
    /* Text color for the button */
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 5px;
    /* Rounded corners for the button */
    text-decoration: none;
    /* Remove underline from link */

    margin-right: 10px;
}

.btn.added:hover {
    color: rgb(201, 191, 191);
}

.btn.adde {
    width: 200px;
    height: 35px;
    background-color: #161616 !important;
    /* Background color for the button */
    color: #fff;
    /* Text color for the button */
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 5px;
    /* Rounded corners for the button */
    text-decoration: none;
    /* Remove underline from link */

    margin-right: 10px;
}

.btn.adde:hover {
    color: rgb(201, 191, 191);
}

.btn.it:hover {
    color: white;
    background-color: black !important;
}

.space {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.spaces {
    display: flex;
    gap: 10px;
}
#main-table{
    margin-top: 30px;
}
/* Basic reset for margin and padding */
body,
table {
    margin: 0;
    padding: 0;
    border-collapse: collapse;
    /* Ensures table borders are collapsed */
}

/* Style for the table */
.table {
    width: 100%;
    border-collapse: collapse;
    /* Collapses the table borders */
}

.table th {
    padding: 8px;
    /* Padding inside header cells for better readability */
    text-align: left;
    /* Align text to the left */
    border-bottom: 1px solid #ddd;
    /* Light gray border for table rows */
}

.table td {
    border: none;
    /* Remove borders from table cells */
    padding: 8px;
    /* Padding inside cells for better readability */
    text-align: left;
    /* Align text to the left */
}

.table tr {
    border-bottom: 1px solid #ddd;
    /* Light gray border for table rows */
}

.table tr:last-child {
    border-bottom: 1px solid #ddd;
    /* Remove border from the last row */
}


.main-content {
    padding: 20px;
}

.btn.added {
    background-color: #3365da;
    color: white;

}

.modal-header {
    background-color: white;
    /* border-bottom: 1px solid #dee2e6; */
    border-bottom: none !important;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-title {
    color: black;
}

.btn.it {
    background-color: black;
    color: white;
}

.modal-bodys {
    padding: 21px !important;
}

.modal-body {
    padding: 0px !important;
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
    padding: 0 !important;
}

.btn.ites {
    color: #3365da;
    /* margin-top:32px; */
}

.modal-dialog-centered {
    margin: 1.75rem auto;
}

.modal-content {
    width: 100% !important;
    height: 100%;
}

.sizing {
    width: 65% !important;
    height: 274px !important;
}

.hi {
    position: relative;

    display: flex;
    justify-content: end;
}

.modal-form {
    padding: 30px;
}

.login-logo {
    /* margin-left: 30px !important; */
    margin-top: 18px;
}

.login-photo {
    margin-left: 45%;
    margin-top: 10px;
    transform: translate(-50%, 0);
    height: 10px;
    width: 100px;
}

.item-wrapper {
    width: 100%;
}

.sidebar-item {
    padding: 10px;
    text-decoration: none;
    border-radius: 10px 0 0px 10px !important;
    color: rgb(8, 8, 8) !important;


}

.sidebar-a {
    color: black !important;
    text-decoration: none !important;
}

.sidebar-a:hover,
.sidebar-a.active {
    text-decoration: none !important;
}


.sidebar-item.active {
    border-radius: 10px 0 0px 10px !important;
    background-color: rgba(231, 185, 0, 1);
}

.sidebar-item a {
    padding: 10px;
    text-decoration: none;
    border-radius: 10px 0 0px 10px !important;
    color: rgb(8, 8, 8) !important;
}

.sidebar-a:hover .sidebar-item,
.sidebar-a.active .sidebar-item {
    color:black !important;
}



.sidebar-item.active a {
    font-weight: bold;
    /* Optional: make the text bold */
    text-decoration: none;
}

.prodev {
    display: flex !important;
    justify-content: center !important;
    align-items: center;
}

.yess {
    color: #3365da !important;
    background-color: rgb(209, 202, 202);
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 50px;
    height: 50px;
    padding: 3px;
    margin-right: 13px;
    font-size: 20px;
}

.as {

    font-size: 20px;
}

.ass {
    font-size: 15px;
    font-weight: 100;
}

.sizes {
    display: flex;
    flex-direction: column !important;
}

.my-row {
    display: flex;
    gap: 10px;
}

.heyy {
    padding: 15px !important;
    font-size: 16px;
}

.btn-outline-danger {
    color: red;
    /* Text color */
    border-color: red;
    /* Border color */
}

.btn-outline-danger .icon {
    fill: red;
    /* Default color for the icon */
    transition: fill 0.3s;
    /* Smooth transition for color change */
}

/* Change icon color on button hover and focus */
.btn-outline-danger:hover .icon,
.btn-outline-danger:focus .icon,
.btn-outline-danger:active .icon {
    fill: white !important;
    /* Icon color on hover/focus/active */
}

.btn-outline-success {
    color: green;
    /* Text color */
    border-color: green;
    /* Border color */
}

/* SVG icon color */
.btn-outline-success .icon {
    fill: green;
    /* Default icon color */
    transition: fill 0.3s;
    /* Smooth transition for color change */
}

/* Change icon color on button hover, focus, and active */
.btn-outline-success:hover .icon,
.btn-outline-success:focus .icon,
.btn-outline-success:active .icon {
    fill: white;
    /* Icon color on hover/focus/active */
}

/* Optional: change text color on hover/focus/active */
.btn-outline-success:hover,
.btn-outline-success:focus,
.btn-outline-success:active {
    color: white;
    /* Text color on hover/focus/active */
}

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

.aaaa {
    display: flex;
    justify-content: start;
    align-items: center;
    font-size: 40px;
    color: black    ;
}

#main-table {
    height: 63vh;
    overflow-y: auto;
}

.yass {
    background-color: #ebecef;
    padding: 15px;
    width: min-content;
    display: flex;
    justify-content: center;
    border-radius: 13px;
    margin-top: 15px;
}

.yash {
    display: flex;
    justify-content: center;

    gap: 5px !important;
    margin-bottom: 10px;

}

.delete-modal {
    text-align: center;

}

.delete-modal2 {
    height: 300px !important;
    width: 300px !important;
    padding: 20px;
    background-color: white;
    border-radius: 10px !important;
}


p {
    margin-top: 15px !important;
}

th {
    color: #686D76 !important;
}

.styled-button {
    /* outline: 2px solid #007BFF; */
    /* Blue outline with 2px width */

    border: none;
    /* Remove default border */
    padding: 7px 5px;
    /* Add some padding */
    /* background-color: #f8f9fa; */
    /* Light background color */
    color: #1a273a;
    /* Text color */
    cursor: pointer;
    /* Change cursor to pointer */
    font-size: 16px;
    /* Font size */
    border-radius: 5px;
    text-decoration: none;
    display: flex;
    justify-content: center;
    align-items: center;

}

/* .styled-button:hover { */
    /* background-color: #e9ecef; */
    /* Change background on hover */
    /* border: 1px solid green; */
/* } */

/* .styled-button svg {
    margin-right: 8px;
} */

.hey {
    display: flex;
    justify-content: end;
    margin-bottom: 20px;
}

/* .modal-footer{
    border:none;
} */
#auto-dismiss-alert {
    width: 393px;
    position: absolute;
    top: 10px;
    left: 50%;
    z-index: 100000;
    transform: translateX(-50%);
}
.sidebar-img {
    margin-right: 10px; /* Adjust this value as needed */
}
</style>

<body>

    <div class="main-content">


        <div class="content">
            <!-- Your main content goes here -->
            <!-- role 0 == employee  and  1 == project manager -->

            <div class="space">
                <b class="aaaa">All Projects</b>

                <?php if ($_SESSION['role']==1) {?>
                <a href="#" class="btn added btn-sm" data-bs-toggle="modal" data-bs-target="#createMeetingModal">Add
                    Project</a> <?php }?>


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

            <div class="hey">
                <?php if ($_SESSION['role']==1) {?>
                <a href="view_complete_project.php" class="btn adde btn-sm">View
                    Complete Project</a> <?php }?>
            </div>
            <div id="main-table">
            <!-- <table id="projectTable" class="table table-striped"> -->
                <table id="projectTable" class="table">
                    <thead>
                        <tr>
                            <th>PROJECT ID</th>
                            <th>PROJECT NAME</th>
                            <th>CLIENT NAME</th>
                            <?php if ($_SESSION['role']==1) {?>
                            <th>ACTION</th>

                            <?php }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p) { ?>
                        <tr>
                            <?php if ($p['status']==0){?>
                            <td><?php echo htmlspecialchars($p['project_id']); ?></td>
                            <td><?php echo htmlspecialchars($p['project_name']); ?></td>
                            <td><?php echo htmlspecialchars($p['client_name']); ?></td>
                            <?php } ?>

                            <?php if ($_SESSION['role']==1 && $p['status']==0) {?>

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
                                        <?php if ($_SESSION['role'] == 1 && $p['status'] == 0) { ?>

                                        <li>
                                            <a class="dropdown-item"
                                                href="update_completed_project.php?id=<?php echo htmlspecialchars($p['project_id']); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                    viewBox="0 -960 960 960" width="24px" fill="black">
                                                    <path
                                                        d="M360-120q-100 0-170-70t-70-170v-240q0-100 70-170t170-70h240q100 0 170 70t70 170v240q0 100-70 170t-170 70H360Zm80-200 240-240-56-56-184 184-88-88-56 56 144 144Zm-80 120h240q66 0 113-47t47-113v-240q0-66-47-113t-113-47H360q-66 0-113 47t-47 113v240q0 66 47 113t113 47Zm120-280Z" />
                                                </svg>
                                                <b style="margin-left:5px;">Mark as completed</b>
                                            </a>
                                        </li>

                                        <?php } ?>
                                    </ul>
                                </div>



                                <?php }?>


                                <div class="modal fade" id="editMeetingModal<?php echo $p['project_id']; ?>"
                                    tabindex="-1" aria-labelledby="editMeetingModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content update-modal2">
                                            <div class="modal-header">
                                                <span></span>
                                                <h3 class="modal-title" id="editMeetingModalLabel"><b>Update Project</b>
                                                </h3>
                                                <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368" style="cursor:pointer;"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>

                                            </div>

                                            <form class="modal-form" method="POST" action="addproject.php">
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
                                                <form method="POST" action="deleteproject.php"
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




                        </tr>
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
                        <h3 class="modal-title" id="createMeetingModalLabel"><b>Add project</b> </h3>
                        <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368" style="cursor:pointer;"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>


                    </div>
                    <form class="modal-form" method="POST" action="addproject2.php">
                        <div class="modal-body">
                            <div class="row">

                                <div class="md-6">
                                    <div class="mb-3">
                                        <label for="project" class="form-label">Add Project</label>
                                        <input type="text" name="project" class="form-control"
                                            placeholder="Enter new project" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="project" class="form-label">Client Name</label>
                                        <input type="text" name="client" class="form-control"
                                            placeholder="Enter your client name" required>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col">
                                            <label for="project" class="form-label">Project Start Date</label>
                                            <input type="date" name="project_start_date" class="form-control">

                                        </div>
                                        <div class="mb-3 col">
                                            <label for="project" class="form-label">Project End Date</label>
                                            <input type="date" name="project_end_date" class="form-control">

                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="project" class="form-label">plateform</label>
                                        <input type="text" name="platform" class="form-control"
                                            placeholder="Enter your platform" required>
                                    </div>

                                </div>

                            </div>
                            <div>
                                <button type="submit" name="addproject" class="btn it">Add</button>
                            </div>
                        </div>
                    </form>

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

                    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

                    <!-- Bootstrap JavaScript Libraries -->
                    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
                        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
                        crossorigin="anonymous"></script>

                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
                        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
                        crossorigin="anonymous"></script>
</body>

</html>