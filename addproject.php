<?php
session_start();



include("./config.php");


$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);
// print_r('<pre>');
// var_dump($projects);

if(isset($_POST['editproject'])){
    $project_name = $_POST['project'];
    $id = $_POST['project_id'];

    $updateproject = $conn->query("UPDATE project SET project_name = '$project_name' WHERE project_id = '$id'");
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
 }$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);

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

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>
<style>
@import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap");

.navbar-item {
    font-family: "Rubik", sans-serif !important;
}

@import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap");

.fonts {
    font-family: "Rubik", sans-serif !important;
    font-size: 50px;
}

@import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap");

.btn.added {
    font-family: "Rubik", sans-serif !important;
}

@import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap");

.as {
    font-family: "Rubik", sans-serif !important;
}

@import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Rubik&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap");

body {
    font-family: "Rubik", sans-serif !important;
}

.DSDS {
    font-family: "Rubik", sans-serif !important;
    font-size: 25px !important;
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
    background-color: #3365da;
    padding: 10px;
    border-radius: 5px;
}

/* Main content styles */
.main-content {
    margin-left: 250px;
    margin-top: 100px;
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
.content {
    padding: 20px;
}

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

.btn.it:hover {
    color: white;
    background-color: black !important;
}

.space {
    display: flex;
    justify-content: space-between;
}

.spaces {
    display: flex;
    gap: 10px;
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
    margin-top: 40px;
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

.table-striped tbody tr {
    background-color: #fff;
    /* White background for all rows */
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
    width: 65% !important;
    height: 287px;
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
    background-color: #3365da;
}

.sidebar-item a {
    padding: 10px;
    text-decoration: none;
    border-radius: 10px 0 0px 10px !important;
    color: rgb(8, 8, 8) !important;
}

.sidebar-a:hover .sidebar-item,
.sidebar-a.active .sidebar-item {
    color: white !important;
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

.aaaa {
    display: flex;
    justify-content: start;
    align-items: center;
    font-size: 40px;
    color: #3365da;
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
th{
    color:#686D76!important;
}
/* .modal-footer{
    border:none;
} */
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


            <div id="main-table">
                <table id="projectTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>PROJECT ID</th>
                            <th>PROJECT NAME</th>
                            <?php if ($_SESSION['role']==1) {?>
                            <th>ACTION</th>
                            <?php }?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($p['project_id']); ?></td>
                            <td><?php echo htmlspecialchars($p['project_name']); ?></td>


                            <?php if ($_SESSION['role']==1) {?>
                            <td class="spaces">
                                <a href="#" onclick="func(e)" class="btn btn-outline-success " data-bs-toggle="modal"
                                    data-bs-target="#editMeetingModal<?php echo $p['project_id']; ?>"><svg
                                        xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="icon">
                                        <path
                                            d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                    </svg><b>Edit</b></a>
                                <button type="button" class="btn btn-outline-danger delete-btn"
                                    data-id="<?php echo $p['project_id']; ?>" data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmationModal<?php echo $p['project_id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="icon">
                                        <path
                                            d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                    </svg>
                                    Delete
                                </button>


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
                        <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="30px"
                            viewBox="0 -960 960 960" width="30px" fill="#565656" style="cursor:pointer;">
                            <path
                                d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                        </svg>

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
                                </div>

                            </div>
                            <div>
                                <button type="submit" name="addproject" class="btn it">Add</button>
                            </div>
                        </div>
                    </form>
                    <!-- Bootstrap JavaScript Libraries -->
                    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
                        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
                        crossorigin="anonymous"></script>

                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
                        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
                        crossorigin="anonymous"></script>
</body>

</html> 