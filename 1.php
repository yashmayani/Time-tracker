<?php
session_start(); // Start session to store messages
include("./config.php");
include("./dashboard.php");

$current_page = basename($_SERVER['PHP_SELF']);

$projects = $conn->query("SELECT * FROM project")->fetch_all(MYSQLI_ASSOC);

// Handle form submissions for editing updates
if (isset($_POST['editupdate'])) {
    $date = $_POST['date'];
    $projects = $_POST['projects'];
    $task = $_POST['task'];
    $hours = $_POST['hours'];
    $minutes = $_POST['minutes'];
    $commit_id = $_POST['commit_id'];
    $id = $_POST['update_id'];

    $update_query = "UPDATE daily_updates 
                     SET date = '$date', 
                         project_id = '$projects', 
                         task = '$task', 
                         hour = '$hours', 
                         minute = '$minutes', 
                         commit_id = '$commit_id' 
                     WHERE update_id = '$id'";

    if ($conn->query($update_query)) {
        header("Location: $current_page"); // Refresh the page to reflect changes
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Handle form submissions for deleting updates
if (isset($_POST['delete'])) {
    $update_id_to_delete = $_POST['delete_update_id'];

    $delete_query = "DELETE FROM daily_updates WHERE update_id = $update_id_to_delete";
    if ($conn->query($delete_query)) {
        header("Location: $current_page"); // Refresh the page to reflect changes
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Handle AJAX request to fetch date details
if (isset($_POST['fetch_date_details'])) {
    $date = $_POST['date'];

    $sql = "SELECT task, hour, minute, commit_id, project_name, action 
            FROM daily_updates 
            WHERE date = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);

    $stmt->close();
    exit; // Stop further script execution
}

// Fetch records for display
$project = $conn->query("SELECT * FROM daily_updates")->fetch_all(MYSQLI_ASSOC);

$groupedByDate = [];
$totalsByDate = [];

foreach ($project as $p) {
    $date = htmlspecialchars($p['date']);
    $hour = isset($p['hour']) ? (int)$p['hour'] : 0;
    $minute = isset($p['minute']) ? (int)$p['minute'] : 0;

    if (!isset($groupedByDate[$date])) {
        $groupedByDate[$date] = [];
        $totalsByDate[$date] = ['hours' => 0, 'minutes' => 0];
    }
    $groupedByDate[$date][] = $p;

    // Add to totals
    $totalsByDate[$date]['hours'] += $hour;
    $totalsByDate[$date]['minutes'] += $minute;
}

// Convert total minutes to hours and minutes
foreach ($totalsByDate as $date => &$total) {
    $totalMinutes = $total['hours'] * 60 + $total['minutes'];
    $total['hours'] = floor($totalMinutes / 60);
    $total['minutes'] = $totalMinutes % 60;
}


include("navbar.php");
include("sidebar.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Updates</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
   <link rel="stylesheet" href="dashboard.css">
<style>
    .qwe{
  display: flex;
  justify-content: end;
}
.btn.addid {
  width: 150px;
  height: 35px;
  background-color: #161616 !important; /* Background color for the button */
  color: #fff; /* Text color for the button */
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 5px; /* Rounded corners for the button */
  text-decoration: none; /* Remove underline from link */

  margin-right: 10px;
}
.btn.addid:hover {
  color: rgb(201, 191, 191);
}
</style>
</head>
<body>

    
/</body>
</html>
<!-- Modal -->
