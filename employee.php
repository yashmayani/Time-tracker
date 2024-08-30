<?php

session_start();



include("./config.php");
include("navbar.php");
include("sidebar.php"); 

$employee = $conn->query("SELECT * FROM employees")->fetch_all(MYSQLI_ASSOC);
// var_dump($employee);
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

</head>

<body>
    <div class="main-content">

        <div class="content">
        <div class="space">
                <b class="aaaa">All Employees</b>
                
            </div>

            <div id="main-table">
                <table id="projectTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>EMPLOYEE ID</th>
                            <th>NAME</th>
                            <th>EMAIL</th>
                            <th>POSITION</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($employee as $emp) { ?>
                            <tr data-bs-toggle="modal" data-bs-target="#exampleModal<?php echo $emp[
                    "employee_id"
                ]; ?>">
                            <td><?php echo htmlspecialchars($emp['employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($emp['name']); ?></td>
                            <td><?php echo htmlspecialchars($emp['email']); ?></td>
                            <td><?php echo htmlspecialchars($emp['position']); ?></td>


                            <div class="modal fade" id="exampleModal<?php echo $emp[
                    "employee_id"
                ]; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl">
                                <div class="modal-content okay-modal2">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel" style="color:#3365da;">Daily update
                                        </h5>
                                        <svg data-bs-dismiss="modal" aria-label="Close"
                                            xmlns="http://www.w3.org/2000/svg" height="30px" viewBox="0 -960 960 960"
                                            width="30px" fill="#565656">
                                            <path
                                                d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                                        </svg>
                                    </div>
                                    <div class="modal-body" style="margin-top:8px;">
                                        <div class='wrapper-div'>
                                            <div class="a d-flex flex-row">

                                                <b>name :</b> <?php echo $emp["name"]; ?>
                                            </div>
                                            <div class="b d-flex flex-row">
                                                <b>email :</b> <?php echo $emp["email"]; ?>
                                            </div>
                                            <div class="c d-flex flex-row">
                                                <b>position :</b> <?php echo $emp["position"]; ?>
                                            </div>
                                            <div class="c d-flex flex-row">
                                                <b>employee id :</b> <?php echo $emp["employee_id"]; ?>
            </div>
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>




                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>












        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
        </script>
</body>

</html>