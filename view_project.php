<?php

session_start();


include("./config.php");

$add_employee=$conn->query("SELECT * from employees")->fetch_all(MYSQLI_ASSOC);
// echo '<pre>';
//  var_dump($add_employee);

if (isset($_POST['delete'])) {
    $vehicle_id_to_delete = $_POST['delete_project_id'];

    // Perform your deletion query here
    $delete_query = "DELETE FROM project_assign WHERE employee_id = $vehicle_id_to_delete";
    $result = $conn->query($delete_query);
    // var_dump($dele);
    if ($result) {
        // Deletion successful, redirect or show success message
        header("Location: view_project.php");
        exit();
    } else {
        // Deletion failed, handle error
        echo "Error deleting vehicle.";
    }
 }
$project_id=$_GET['id'];


$projects_details= $conn->query("SELECT * FROM project WHERE project_id = $project_id")->fetch_all(MYSQLI_ASSOC);

// echo '<pre>';
// var_dump($projects_details);

$select_emp_id = $conn->query("SELECT employee_id from project_assign where project_id = $project_id")->fetch_all(MYSQLI_ASSOC);

$allIds = [];
foreach ($select_emp_id as $employee) {
    $allIds[] = $employee['employee_id'];
}

if(count($allIds)>0){
$idsString = implode(',', $allIds);
$formattedIds = "($idsString)";
$selected_emp = $conn->query("SELECT * from employees where employee_id in $formattedIds")->fetch_all(MYSQLI_ASSOC);
}else{
    $selected_emp=[];
}
// echo '<pre>';
// var_dump($selected_emp);



include("./navbar.php");
include("./sidebar.php");


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

<!-- Bootstrap CSS v5.2.1 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    
    <div class="main-content">

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
        <div class="content">
            <div class="space">
                <div class="prodev">
                    <b class="ai"> <?php echo $projects_details[0]['project_name']; ?> </b>

                    <b class="aii">Client name : <?php echo $projects_details[0]['client_name']; ?>
                    <?php if ($projects_details[0]['platform'] !== "0" && !empty($projects_details[0]['platform'])): ?>
   
<?php endif; ?>

</b>
<b class="aii">Platform :
        <?php echo htmlspecialchars($projects_details[0]['platform']); ?>
                    </b>
                </div>
                <div class="infotech">
    <?php
    $start_date = $projects_details[0]['start_date'];
    $end_date = $projects_details[0]['end_date'];
    ?>

    <?php if ($start_date != '0000-00-00'): ?>
        <b class="aiii">
            <svg xmlns="http://www.w3.org/2000/svg" height="15px" viewBox="0 -960 960 960" width="15px" fill="gray">
                <path d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z" />
            </svg>
           Poject Start Date : <?php echo htmlspecialchars($start_date); ?>
        </b>
    <?php endif; ?>

    <?php if ($end_date != '0000-00-00'): ?>
        <b class="aiii">
            <svg xmlns="http://www.w3.org/2000/svg" height="15px" viewBox="0 -960 960 960" width="15px" fill="gray">
                <path d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z" />
            </svg>
          Project End Date : <?php echo htmlspecialchars($end_date); ?>
        </b>
    <?php endif; ?>
</div>
      </div>
        </div>
        <div class="add_employee">
   <a href="#" class="btn added btn-sm" data-bs-toggle="modal" data-bs-target="#createMeetingModal">Add
                    Employee</a></div>
        <div id="main-table">
            <table id="projectTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>EMPLOYEE ID</th>
                        <th>NAME</th>
                        <th>EMAIL</th>
                        <th>POSITION</th>
                        <th>ACTION</th>


                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($selected_emp as $selected) { ?>
                    <tr>

                        <td><?php echo htmlspecialchars($selected['employee_id']); ?></td>
                        <td><?php echo htmlspecialchars($selected['name']); ?></td>
                        <td><?php echo htmlspecialchars($selected['email']); ?></td>
                        <td><?php echo htmlspecialchars($selected['position']); ?></td>



                        <td>
                            <span class="spaces">
                            <button type="button" class="btn btn-outline-danger delete-btn"
                                    data-id="<?php echo $selected['employee_id']; ?>" data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmationModal<?php echo $selected['employee_id']; ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                        width="24px" class="icon">
                                        <path
                                            d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                    </svg>
                                    Delete
                                </button>


                                <div class="modal fade" id="deleteConfirmationModal<?php echo $selected['employee_id']; ?>"
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
                                                        <p>Are you want to sure delete employee ?</p>


                                            </div>
                                            <div class="yash">
                                                <form method="POST" action="delete_employee.php"
                                                    style="display: flex; gap: 15px;">

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <input type="hidden" name="delete_project_id"
                                                        value="<?php echo $selected['employee_id']; ?>">
                                                        <input type="hidden" name="project_id"
                                                        value="<?php echo $project_id; ?>">
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>


        <div class="modal fade" id="createMeetingModal" tabindex="-1" aria-labelledby="createMeetingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span></span>
                    
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
                    
                    <h3 class="modal-title" id="createMeetingModalLabel"><b>Add Employee</b> </h3>
                    <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="30px"
                        viewBox="0 -960 960 960" width="30px" fill="#565656" style="cursor:pointer;">
                        <path
                            d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>

                </div>
                <form class="modal-form" method="POST" action="add-employee-project.php">
                    <div class="modal-body">
                        <div class="row">
                           
                            <div class="md-6">
                                <div class="mb-3">
                                    <label for="project" class="form-label">Select Employee</label>
                                    <select name="emp_id" id="projects" class="form-select" required>
                                        <option value="" disabled selected>Select Employee</option>
                                        
                                        <?php foreach ($add_employee as $add): ?>
                                      
                                        <option value="<?php echo htmlspecialchars($add['employee_id']) ?>"> <?php echo htmlspecialchars($add['employee_id']) ?>. 
    <?php echo htmlspecialchars($add['name']) ?> ( <?php echo htmlspecialchars($add['position']) ?>)
</option>

                                       
                                        <?php endforeach; ?>
                                        
                                        
                                        
                                    </select>
                                    <input type="hidden" name="project_id"
                                    value="<?php echo $_GET['id']; ?>">
                                </div>
                            </div>

                        </div>

                        <div id="input-container">
                    

                
               
                    <div>
                        <button type="submit" name="submit" class="btn it">Submit</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
    </div>


</html>










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




<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>
</body>

</html>