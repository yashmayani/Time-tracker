<?php

session_start();


include("./config.php");

$project_id=$_GET['id'];


$projects_details= $conn->query("SELECT * FROM project WHERE project_id = $project_id")->fetch_all(MYSQLI_ASSOC);

// echo '<pre>';
// var_dump($projects_details);

$select_emp_id = $conn->query("SELECT employee_id from project_assign where project_id = $project_id")->fetch_all(MYSQLI_ASSOC);

// var_dump($select_emp_id);





include("./navbar.php");
include("./sidebar.php");


?>
<!doctype html>
<html lang="en">
    <head>
        <title>Title</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />

        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="dashboard.css">
    </head>

    <body>
    <div class="main-content">

<div class="content">
    <div class="space">
        <b class="aaaa">project name : <?php echo $projects_details[0]['project_name']; ?> </b>
        

    </div>
<b class="aaaa">client name : <?php echo $projects_details[0]['client_name']; ?> </b>
     
</div>

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

</body>

</html>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
    </body>
</html>
