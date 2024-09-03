<?php

session_start();


include("./config.php");

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
</head>

<body>
    <div class="main-content">

        <div class="content">
            <div class="space">
                <div class="prodev">
                    <b class="ai"> <?php echo $projects_details[0]['project_name']; ?> </b>

                    <b class="aii"> <?php echo $projects_details[0]['client_name']; ?>
                    <?php if ($projects_details[0]['platform'] !== "0" && !empty($projects_details[0]['platform'])): ?>
    <span class="badge rounded-pill text-bg-success small-text">
        <?php echo htmlspecialchars($projects_details[0]['platform']); ?>
    </span>
<?php endif; ?>
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
            Start : <?php echo htmlspecialchars($start_date); ?>
        </b>
    <?php endif; ?>

    <?php if ($end_date != '0000-00-00'): ?>
        <b class="aiii">
            <svg xmlns="http://www.w3.org/2000/svg" height="15px" viewBox="0 -960 960 960" width="15px" fill="gray">
                <path d="m612-292 56-56-148-148v-184h-80v216l172 172ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-400Zm0 320q133 0 226.5-93.5T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 133 93.5 226.5T480-160Z" />
            </svg>
            End : <?php echo htmlspecialchars($end_date); ?>
        </b>
    <?php endif; ?>
</div>

            </div>
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
                    <?php foreach ($selected_emp as $selected) { ?>
                    <tr>

                        <td><?php echo htmlspecialchars($selected['employee_id']); ?></td>
                        <td><?php echo htmlspecialchars($selected['name']); ?></td>
                        <td><?php echo htmlspecialchars($selected['email']); ?></td>
                        <td><?php echo htmlspecialchars($selected['position']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>





</body>

</html>















<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>
</body>

</html>