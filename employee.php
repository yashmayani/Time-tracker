<?php

session_start();



include("./config.php");
include("navbar.php");
include("sidebar.php"); 

$employee = $conn->query("SELECT * FROM employees")->fetch_all(MYSQLI_ASSOC);
// var_dump($employee);
$yess =  $conn->query("SELECT 
    du.update_id,
    du.project_id,
    du.task,
    du.date,
    du.hour,
    du.minute,
    du.commit_id,
    du.employee_id,
    p.project_name
FROM 
    daily_updates AS du
JOIN 
    project AS p
ON 
    du.project_id = p.project_id")->fetch_all(MYSQLI_ASSOC);
    // echo '<pre>';
    // var_dump($yess);
    if (isset($_POST['delete'])) {
        $vehicle_id_to_delete = $_POST['delete_project_id'];
    
        // Perform your deletion query here
        $delete_query = "DELETE FROM employees WHERE employee_id = $vehicle_id_to_delete";
        $result = $conn->query($delete_query);
        // var_dump($dele);
        if ($result) {
            // Deletion successful, redirect or show success message
            header("Location: employee.php");
            exit();
        } else {
            // Deletion failed, handle error
            echo "Error deleting vehicle.";
        }
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
    <link rel="stylesheet" href="dashboard.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap CSS v5.2.1 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
    .search-container {
        position: relative;
        display: inline-block;
        width: 20%;

    }

    .search-container input {
        padding-left: 40px;
        height: 35px !important;
        box-shadow: none;
        border-radius: 7px;
        border: 1px solid #c6bdbdf5 !important;
    }

    .search-container svg {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        height: 24px;
        /* Adjust size as needed */
        width: 24px;
        fill: #c6bdbdf5;
    }
    </style>
</head>

<body>
    <div class="main-content">

        <div class="content">
            <div class="space">
                <b class="aaaa">All Employees</b>
                <a href="#" class="btn added btn-sm" data-bs-toggle="modal" data-bs-target="#createMeetingModal">Add
                    Employee</a>

            </div>

            <!-- Search Input Field with SVG Icon -->
            <div class="search-container mt-3">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                    fill="#5f6368">
                    <path
                        d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                </svg>
                <input type="text" id="searchInput" class="form-control" placeholder="Search...">
            </div>
        </div>

        <div id="main-table">
            <table id="projectTable" class="table table-striped">
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
                    <?php foreach ($employee as $emp) { ?>
                    <tr>

                        <td><?php echo htmlspecialchars($emp['employee_id']); ?></td>
                        <td><?php echo htmlspecialchars($emp['name']); ?></td>
                        <td><?php echo htmlspecialchars($emp['email']); ?></td>
                        <td><?php echo htmlspecialchars($emp['position']); ?></td>


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
                                            href="view_emp.php?id=<?php echo htmlspecialchars($emp['employee_id']); ?>&name=<?php echo urlencode($emp['name']); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                viewBox="0 -960 960 960" width="24px" class="icon">
                                                <path
                                                    d="M480-320q75 0 127.5-52.5T660-500q0-75-52.5-127.5T480-680q-75 0-127.5 52.5T300-500q0 75 52.5 127.5T480-320Zm0-72q-45 0-76.5-31.5T372-500q0-45 31.5-76.5T480-608q45 0 76.5 31.5T588-500q0 45-31.5 76.5T480-392Zm0 192q-146 0-266-81.5T40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200Zm0-300Zm0 220q113 0 207.5-59.5T832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280Z" />
                                            </svg>
                                            <b style="margin-left: 5px;">View</b>
                                        </a></li>


                                    <li><a class="dropdown-item" href="#"  button type="button"
                                            class="btn btn-outline-danger delete-btn"
                                            data-id="<?php echo $p['update_id']; ?>" data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmationModal<?php echo $emp['employee_id']; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                viewBox="0 -960 960 960" width="24px" class="icon">
                                                <path
                                                    d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                            </svg>
                                            <b style="margin-left:5px;">Delete</b>
                                            </button></a></li>
                    </ul>

                                    <div class="modal fade"
                                        id="deleteConfirmationModal<?php echo $emp['employee_id']; ?>" tabindex="-1"
                                        aria-labelledby="editMeetingModalLabel" aria-hidden="true">
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
                                                    <form method="POST" action="deleteupdate2.php"
                                                        style="display: flex; gap: 15px;">

                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <input type="hidden" name="delete_project_id"
                                                            value="<?php echo $emp['employee_id']; ?>">
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
                    <h3 class="modal-title" id="createMeetingModalLabel"><b>Add Employee</b> </h3>
                    <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="30px"
                        viewBox="0 -960 960 960" width="30px" fill="#565656" style="cursor:pointer;">
                        <path
                            d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                    </svg>

                </div>
                <form class="modal-form" method="POST" action="addemployee2.php">
                    <div class="modal-body">
                        <div class="row">

                            <div class="md-6">
                                <div class="mb-3">
                                    <label for="project" class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter you name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="project" class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="project" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Enter your password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="project" class="form-label">Position</label>
                                    <input type="text" name="position" class="form-control"
                                        placeholder="Enter your position" required>
                                </div>
                            </div>

                        </div>
                        <div>
                            <button type="submit" name="addemployee" class="btn it">Add</button>
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

                <!-- JavaScript for Search Functionality -->
                <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const searchInput = document.getElementById('searchInput');
                    const table = document.getElementById('projectTable');
                    const rows = table.querySelectorAll('tbody tr');

                    searchInput.addEventListener('input', () => {
                        const query = searchInput.value.toLowerCase();
                        rows.forEach(row => {
                            const cells = row.getElementsByTagName('td');
                            let match = false;
                            for (let i = 0; i < cells.length; i++) {
                                if (cells[i].textContent.toLowerCase().includes(query)) {
                                    match = true;
                                    break;
                                }
                            }
                            row.style.display = match ? '' : 'none';
                        });
                    });
                });
                </script>


                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
                    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
                    crossorigin="anonymous">
                </script>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
                    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
                    crossorigin="anonymous">
                </script>
</body>

</html>