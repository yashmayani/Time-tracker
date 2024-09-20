<?php



include("./config.php");

// $vehicles = $conn->query("SELECT * FROM daily_updates")->fetch_all();
// print_r('<pre>');
//  var_dump($vehicles);
 
$project=$conn->query("SELECT 
    du.task,
    du.date,
    du.time,
    p.project_name
FROM 
    daily_updates AS du
JOIN 
    project AS p
ON 
    du.project_id = p.project_id;")->fetch_all();
// print_r('<pre>');
//  var_dump($project);
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
    <style>
    .users_maindiv {

        padding-block: 30px;
        margin-block: 60px;
        box-shadow: 0 0 10px rgba(179, 165, 165, 0.5);
        border-radius: 20px;

    }

    #vehicleTable_filter {
        border-bottom: 1px solid black;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .search-header {
        margin-inline: 10px;
    }

    .search-header label {
        display: flex;
        justify-content: start;

    }

    .dataTables_length {
        display: none;
    }

    /* Styling for the delete button */
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
        fill: white;
        /* Icon color on hover/focus/active */
    }

    /* Button styling for outline-success */
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


    thead * {
        border: none !important;
    }

    .dataTables_info {
        display: none;
    }

    .modal-header {
        text-align: center;
    }

    .delete-modal {
        text-align: center;
    }

    .delete-modal2 {
        height: 300px;
        width: 300px;
        padding: 20px;
        background-color: white;
        border-radius: 10px !important;

    }

    .update-modal2 {
        height: 330px;
        width: 350px;
        border-radius: 10px !important;

    }

    .it {
        margin-top: 5px;
        background-color: #524FFF !important;
        color: white;

    }

    .add-modal2 {
        height: 330px;
        width: 350px;
        border-radius: 10px !important;

    }

    .yash {
        display: flex;
        justify-content: center;
        ;
        gap: 15px;
        margin-bottom: 25px;

    }

    p {
        margin-top: 15px;
    }

    #tableSearch {
        width: 20%;
        margin-bottom: 20px;
    }

    .space {
        display: flex;
        gap: 20px !important;
    }


    .search-create-wrapper {
        display: flex;
        justify-content: space-between;
        /* Space between the items */
        align-items: center;
        /* Align items vertically */
    }

    #tableSearch {
        width: 20%;
        margin-bottom: 10px;
        margin-top: 20px;

    }



    .btn {
        /* Ensure the button doesn’t exceed its content width */
        white-space: nowrap;
    }

    .logouts {
        color: white;
        background-color: #524FFF;
    }

    .input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;


    }

    .input-wrapper input {
        padding-left: 40px;
        /* Space for the icon */
        height: 35px !important;

        box-shadow: none;
        border-radius: 7px;
        border: 1px solid #c6bdbdf5 !important;

    }

    .input-wrapper svg {
        position: absolute;
        left: 10px;
        top: 60%;
        transform: translateY(-50%);
        height: 24px;
        /* Adjust size as needed */
        width: 24px;
        fill: #c6bdbdf5;
        /* SVG color */
    }

    input::placeholder {
        color: #c6bdbdf5 !important;
        /* Change this to your desired color */
    }

    ul.pagination * {
        margin-top: 10px;
        border: none;
        border-radius: 7px;
        background-color: white;
        color: #a8aaac;
        font-size: 15px;
        /* Adjust the font size as needed */


    }

    .page-item.active .page-link {
        background-color: #524FFF;
        box-shadow: none;

    }

    .added {
        color: white;
        background-color: #524FFF;
        margin-left: 1170px;

    }

    .yass {
        background-color: #ebecef;
        padding: 15px;
        width: min-content;
        display: flex;
        justify-content: center;
        border-radius: 13px;
    }
    </style>

</head>

<body>

    <div class="container">
        <div class="users_maindiv p-3">
            <div class="d-flex justify-content-between">
                <h2 style="font-size: 30px;">daily updates</h2>

            </div>
            <div class="search-create-wrapper">
                <div class="input-wrapper">


                    <a href="#" class="btn added   btn-sm " data-bs-toggle="modal" data-bs-target="#createMeetingModal"
                        style="width: 75px; height:35px; ">Add +</a>
                </div>
            </div>
            <hr style="border:1px solid black !important;">

            <table id="vehicleTable" class="table " style="width:100%">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>TIME</th>
                        <th>PROJECT</th>
                        <th>TASK</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($project as $p) { ?>
                    <tr>
                        <td><?php echo $p[1]; ?></td>
                        <td><?php echo $p[2]; ?></td>
                        <td><?php echo $p[3]; ?></td>
                        <td><?php echo $p[0]; ?></td>
                    </tr>
                    <?php } ?>
                </tbody>












                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
                    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
                    crossorigin="anonymous"></script>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
                    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
                    crossorigin="anonymous" >
                    </script>
                <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js" >
                </script>
                <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

                </script>
                <script>
                $(document).ready(function() {
                    $("#vehicleTable").DataTable({
                        language: {
                            paginate: {
                                previous: "<",
                                next: ">",
                            }
                        },
                        dom: '<"top"i>rt<"bottom"lp><"clear">',
                        pagingType: "simple_numbers"
                    });
                });
                </script>

</html>