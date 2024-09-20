<?php

session_start();

include "./config.php";
include "./navbar.php";
include "./sidebar.php";

if (isset($_POST['editleaves'])) {
    $from_date = $_POST['from_date'];
    $end_date = $_POST['end_date'];
    $reasan = $_POST['reasan'];
    $id = $_POST['leaves_id'];

    $update_leaves = $conn->query("UPDATE leaves SET  from_date = '$from_date' , end_date = '$end_date' , reasan = '$reasan'  WHERE leaves_id ='$id' ");
    // var_dump($update_leaves);

}

$id = $_SESSION["employee_id"];
//   echo '<pre>';
//      var_dump($id);
if ($_SESSION["role"] == 0) {
    $leaves = $conn
        ->query(
            "SELECT

 l.leaves_id,
 l.from_date,
 l.end_date,
 l.reasan,
 l.status,
 l.employee_id,
 e.name
 FROM
  leaves AS l
  JOIN
employees AS e
ON
 l.employee_id = e.employee_id  WHERE e.employee_id = $id"
        )
        ->fetch_all(MYSQLI_ASSOC);
    //  echo '<pre>';
    //  var_dump($leaves);
} elseif ($_SESSION["role"] == 1) {
    $leaves = $conn
        ->query(
            "SELECT

        l.leaves_id,
        l.from_date,
        l.end_date,
        l.reasan,
        l.status,
        l.employee_id,
        e.name
        FROM
         leaves AS l
         JOIN
       employees AS e
       ON
        l.employee_id = e.employee_id "
        )
        ->fetch_all(MYSQLI_ASSOC);
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
</head>

<body>



    <div class="main-content">

        <div class="contents">
            <div class="space">
                <b class="a">Leaves</b>
                <a href="#" class="btn added btn-sm" data-bs-toggle="modal" data-bs-target="#createMeetingModal">Add
                    Leave</a>

            </div>

        </div>

        <div id="main-table">
            <table id="projectTable" class="table ">

                <thead>


                    <tr>
                        <th>NAME</th>
                        <th>START DATE</th>
                        <th>END DATE</th>
                        <th>REASAN</th>
                        <th>STATUS</th>

                        <!-- <?php if ($_SESSION['role'] == 0 && $leaves['status'] == 0) { ?>
                            <th>ACTION</th>
                        <?php } ?> -->

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaves as $l) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($l["name"]); ?></td>
                            <td><?php echo htmlspecialchars($l["from_date"]); ?></td>
                            <td><?php echo htmlspecialchars($l["end_date"]); ?></td>
                            <td><?php echo htmlspecialchars($l["reasan"]); ?></td>

                            <?php if ($_SESSION['role'] == 0) { ?>
                                <td>
                                    <?php
                                    if ($l['status'] == 0) {
                                        echo "Pending";
                                    } elseif ($l['status'] == 1) {
                                        echo "Approve";
                                    } elseif ($l['status'] == 2) {
                                        echo "Rejected";
                                    }
                                    ?>
                                </td>

                            <?php } elseif ($_SESSION['role'] == 1) { ?>
                                <td>
                                    <?php
                                    if ($l['status'] == 0) { ?>









                                        <a style="text-decoration:none;"
                                            href="approve_leaves.php?id=<?php echo htmlspecialchars($l['leaves_id']); ?>">
                                            <button class='btn btn-outline-success'>
                                                <svg xmlns='http://www.w3.org/2000/svg' height='24px' viewBox='0 -960 960 960'
                                                    width='24px' fill='currentColor'>
                                                    <path
                                                        d='m344-60-76-128-144-32 14-148-98-112 98-112-14-148 144-32 76-128 136 58 136-58 76 128 144 32-14 148 98 112-98 112 14 148-144 32-76 128-136-58-136 58Zm34-102 102-44 104 44 56-96 110-26-10-112 74-84-74-86 10-112-110-24-58-96-102 44-104-44-56 96-110 24 10 112-74 86 74 84-10 114 110 24 58 96Zm102-318Zm-42 142 226-226-56-58-170 170-86-84-56 56 142 142Z' />
                                                </svg>
                                            </button>
                                        </a>
                                        <a style="text-decoration:none;"
                                            href="rejected_leaves.php?id=<?php echo htmlspecialchars($l['leaves_id']); ?>">
                                            <button class='btn btn-outline-danger'><svg xmlns='http://www.w3.org/2000/svg'
                                                    width='24' height='24' fill='currentColor' class='bi bi-x-octagon'
                                                    viewBox='0 0 16 16'>
                                                    <path
                                                        d='M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z' />
                                                    <path
                                                        d='M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.70' />
                                                </svg>
                                            </button>
                                        </a>
                                    <?php } elseif ($l['status'] == 1) {
                                        echo "Approve";
                                    } elseif ($l['status'] == 2) {
                                        echo "Rejected";
                                    }
                                    ?>
                                </td>
                            <?php } ?>

                            <?php if ($_SESSION['role'] == 0 && $l['status'] == 0) { ?>
                                <td>
                                    <span class="spaces">
                                        <a href="#" onclick="func(e)" class="btn btn-outline-success " data-bs-toggle="modal"
                                            data-bs-target="#editMeetingModal<?php echo $l['leaves_id']; ?>"><svg
                                                xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                                width="24px" class="icon">
                                                <path
                                                    d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                            </svg><b>Edit</b></a>
                                        <button type="button" class="btn btn-outline-danger delete-btn"
                                            data-id="<?php echo $l['leaves_id']; ?>" data-bs-toggle="modal"
                                            data-bs-target="#deleteConfirmationModal<?php echo $l['leaves_id']; ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                                width="24px" class="icon">
                                                <path
                                                    d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                            </svg>
                                            Delete
                                        </button>
                                        <div class="modal fade" id="deleteConfirmationModal<?php echo $l['leaves_id']; ?>"
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
                                                                <p>Are you want to sure delete leave?</p>


                                                    </div>
                                                    <div class="yash">
                                                        <form method="POST" action="deleteleaves.php"
                                                            style="display: flex; gap: 15px;">

                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <input type="hidden" name="delete_leaves_id"
                                                                value="<?php echo $l['leaves_id']; ?>">
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class=" modal fade" id="editMeetingModal<?php echo $l['leaves_id']; ?>"
                                            tabindex="-1" aria-labelledby="createMeetingModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <span></span>
                                                        <h3 class="modal-title" id="createMeetingModalLabel"><b>Edit Leave</b>
                                                        </h3>
                                                        <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368" style="cursor:pointer;"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>


                                                    </div>
                                                    <form class="modal-form" method="POST" action="leaves.php">
                                                        <div class="modal-body">
                                                            <div class="row">

                                                                <div class="md-6">
                                                                    <div class="mb-3">
                                                                        <label for="project" class="form-label">from
                                                                            date</label>
                                                                        <input type="datetime-local" name="from_date"
                                                                            class="form-control"
                                                                            value="<?php echo $l['from_date']; ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="project" class="form-label">end date</label>
                                                                        <input type="datetime-local" name="end_date"
                                                                            class="form-control"
                                                                            value="<?php echo $l['end_date']; ?>" required>
                                                                    </div>

                                                                    <div class="mb-3">
                                                                        <label for="project" class="form-label">Reasan</label>
                                                                        <input type="text" name="reasan" class="form-control"
                                                                            value="<?php echo $l['reasan']; ?>" required>
                                                                    </div>

                                                                </div>

                                                            </div>
                                                            <input type="hidden" name="leaves_id"
                                                                value="<?php echo $l['leaves_id']; ?>">
                                                            <div>
                                                                <button type="submit" name="editleaves"
                                                                    class="btn it">Send</button>
                                                            </div>
                                                        </div>

                                                    </form>




                                                <?php } ?>

                                            <?php } ?>
                </tbody>
            </table>
        </div>
    </div>







    <div class=" modal fade" id="createMeetingModal" tabindex="-1" aria-labelledby="createMeetingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <span></span>
                    <h3 class="modal-title" id="createMeetingModalLabel"><b>Add Leave</b>
                    </h3>
                    <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368" style="cursor:pointer;"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>


                </div>
                <form class="modal-form" method="POST" action="addleaves.php">
                    <div class="modal-body">
                        <div class="row">

                            <div class="md-6">
                                <div class="mb-3">
                                    <label for="project" class="form-label">from
                                        date</label>
                                    <input type="datetime-local" name="from_date" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="project" class="form-label">end date</label>
                                    <input type="datetime-local" name="end_date" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="project" class="form-label">Reasan</label>
                                    <input type="text" name="reasan" class="form-control"
                                        placeholder="Enter your reasan" required>
                                </div>

                            </div>

                        </div>
                        <div>
                            <button type="submit" name="addleaves" class="btn it">Send</button>
                        </div>
                    </div>

                </form>




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