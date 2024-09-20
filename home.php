<?php


session_start();

include("./config.php");


$prodevnews = $conn->query("SELECT * from prodev_news")->fetch_all(MYSQLI_ASSOC);
// echo '<pre>';
// var_dump($prodevnews);




if (isset($_POST['updatenews'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id = $_POST['news_id'];


    $updatenews = $conn->query("UPDATE prodev_news SET title ='$title',content = '$content' WHERE news_id  ='$id'");
    var_dump($updatenews);

    if ($updatenews) {
        header("Refresh:0");
        ob_end_flush(); // Flush and turn off output buffering
        exit;
    } else {
        echo "Error updating meeting.";
        ob_end_flush(); // Flush and turn off output buffering
        exit;
    }
}






include("./navbar.php");
include("./sidebar.php");
$sql = "
SELECT
    pn.news_id,
    pn.title,
    pn.content,
    pi.image_id,
    pi.image,
    pn.date
FROM
    prodev_news AS pn
JOIN
    prodev_image AS pi
ON
    pn.news_id = pi.news_id
WHERE
    pn.date>= NOW() - INTERVAL 1 DAY
";

$result = $conn->query($sql);

// Initialize an empty array to store the structured data
$newsArray = [];

// Process the query results
while ($row = $result->fetch_assoc()) {
    $newsId = $row['news_id'];

    // If the news item doesn't exist in the array, create a new entry
    if (!isset($newsArray[$newsId])) {
        $newsArray[$newsId] = [
            'news_id' => $row['news_id'],
            'title' => $row['title'],
            'content' => $row['content'],
            'images' => [],
            'date' => $row['date'],
        ];
    }

    // Add the image to the corresponding news item
    $newsArray[$newsId]['images'][] = $row['image'];
}

// Convert the associative array to a numerically indexed array
$newsArray = array_values($newsArray);

// Output the result for demonstration
// echo '<pre>';
// print_r($newsArray);
// echo '</pre>';




?>
<?php

// Function to convert the interval to a human-readable format
function timeAgo($dateString)
{
    date_default_timezone_set('UTC'); // Change to your desired timezone
    $date = new DateTime($dateString);
    $now = new DateTime();
    $interval = $now->diff($date);
    if ($interval->y > 0) {
        return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
    } elseif ($interval->d > 0) {
        return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'just now';
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
        .image-container {
            position: relative;
            width: 100%;
            height: auto;
        }

        .image-container img {
            height: 250px;
            object-fit: cover;
        }

        .news-image {
            border-radius: 15px;
            object-fit: cover;
            width: 100%;
            height: auto;
        }

        .image-count {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            /* Slightly transparent black background */
            color: white;
            padding: 5px 10px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            border: 2px solid white;
            height: 39px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

</head>

<body>
    <div class="main-content">

        <div class="contents">
            <div class="space">
                <b class="a">Prodev Updates</b>
                <?php if ($_SESSION['role'] == 1) { ?>
                    <a href="#" class="btn added btn-sm" data-bs-toggle="modal" data-bs-target="#createProdevnewsModal">Add
                        News</a>
                <?php } ?>
            </div>
            <hr>

            <div class="row row-cols-3 g-3">
                <?php foreach ($newsArray as $n) { ?>
                    <div class="col">
                        <div class="card" style="border: none;">
                            <div class="image-container">
                                <div class="swiper">
                                    <div class="swiper-wrapper">
                                        <?php foreach($n['images'] as $img){?>
                                        <div class="swiper-slide">
                                        <img src='<?php echo htmlspecialchars($img); ?>' class="news-image" />
                                        </div>
                                        <?php }?>
                                    </div>
                                    <div class="swiper-pagination"></div>

                                </div>
                                <!-- <img src='<?php echo htmlspecialchars($n['images'][0]); ?>' class="news-image" />
                            <?php if (count($n['images']) > 1): ?>
                            <div class="image-count">
                                <p><b><?php echo count($n['images']) - 1; ?>+</b></p>
                            </div>
                            <?php endif; ?> -->
                            </div>

                            <div class="card-body">
                                <div class="multiple" style="display:flex; justify-content:space-between">
                                    <div class="ab" style="display: flex; gap: 20px;">
                                        <p class="card-text">
                                            <?php $date = new DateTime($n['date']);
                                            echo htmlspecialchars($date->format('d-m-Y'));
                                            ?></p>
                                        <p class="card-text"><?php echo (timeAgo($n['date'])); ?> </p>
                                    </div>
                                    <?php if ($_SESSION['role'] == 1) { ?>
                                        <div class="dropdown">

                                            <a class="btn" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960"
                                                    width="24px" fill="#5f6368">
                                                    <path
                                                        d="M480-160q-33 0-56.5-23.5T400-240q0-33 23.5-56.5T480-320q33 0 56.5 23.5T560-240q0 33-23.5 56.5T480-160Zm0-240q-33 0-56.5-23.5T400-480q0-33 23.5-56.5T480-560q33 0 56.5 23.5T560-480q0 33-23.5 56.5T480-400Zm0-240q-33 0-56.5-23.5T400-720q0-33 23.5-56.5T480-800q33 0 56.5 23.5T560-720q0 33-23.5 56.5T480-640Z" />
                                                </svg>
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editMeetingModal<?php echo $n['news_id']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                            viewBox="0 -960 960 960" width="24px" class="icon">
                                                            <path
                                                                d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
                                                        </svg><b style="margin-left: 5px;">Edit</b>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#deleteConfirmationModal<?php echo $n['news_id']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" height="24px"
                                                            viewBox="0 -960 960 960" width="24px" class="icon">
                                                            <path
                                                                d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                        </svg><b style="margin-left: 5px;">Delete</b>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>

                                <h5 class="card-title"><?php echo nl2br(htmlspecialchars($n['title'])); ?></h5>
                                <p class="card-text"><?php echo nl2br($n['content']); ?></p>
                            </div>
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div class="modal fade" id="deleteConfirmationModal<?php echo $n['news_id']; ?>" tabindex="-1"
                            aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content delete-modal2">
                                    <div class="modal-body delete-modal">
                                        <center>
                                            <div class="yass">
                                                <svg xmlns="http://www.w3.org/2000/svg" height="30px"
                                                    viewBox="0 -960 960 960" width="30px" fill="red"
                                                    style="background-color:#ebecef; border-radius: 10px !important;">
                                                    <path
                                                        d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
                                                </svg>
                                            </div>
                                            <p style="color:red;"><b>Delete</b></p>
                                            <p>Are you sure you want to delete this news update?</p>
                                        </center>
                                    </div>
                                    <div class="yash">
                                        <form method="POST" action="deletenews.php" style="display: flex; gap: 15px;">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <input type="hidden" name="delete_news_id" value="<?php echo $n['news_id']; ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editMeetingModal<?php echo $n['news_id']; ?>" tabindex="-1"
                            aria-labelledby="createMeetingModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="createMeetingModalLabel" style="margin-left: 55px;">
                                            <b>Update Prodev News</b>
                                        </h3>
                                        <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg"
                                            height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368"
                                            style="cursor:pointer;">
                                            <path
                                                d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                                        </svg>
                                    </div>
                                    <form method="POST" class="p-4" action="home.php">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Title</label>
                                                <input type="text" name="title" class="form-control"
                                                    value="<?php echo htmlspecialchars($n['title']); ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="content" class="form-label">Content</label>
                                                <textarea name="content" class="form-control" id="content"
                                                    required><?php echo htmlspecialchars($n['content']); ?></textarea>
                                            </div>
                                            <input type="hidden" name="news_id" value="<?php echo $n['news_id']; ?>">
                                        </div>
                                        <div>
                                            <button type="submit" name="updatenews" class="btn it">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="modal fade" id="createProdevnewsModal" tabindex="-1" aria-labelledby="createProdevModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <span></span>
                        <h3 class="modal-title" id="createMeetingModalLabel"><b> Add Prodev News</b> </h3>

                        <svg data-bs-dismiss="modal" aria-label="Close" xmlns="http://www.w3.org/2000/svg" height="24px"
                            viewBox="0 -960 960 960" width="24px" fill="#5f6368" style="cursor:pointer;">
                            <path
                                d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z" />
                        </svg>

                    </div>
                    <form class="modal-form" method="POST" action="addprodevnews.php" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="title" name="title" class="form-control"
                                            placeholder="Enter your title" required>
                                    </div>
                                </div>
                            </div>
                            <div class="md-6">
                                <div class="mb-3">
                                    <label for="images" class="form-label">Upload Images</label>
                                    <input type="file" id="image" name="image[]" class="form-control" multiple
                                        accept="image/*">
                                </div>
                            </div>
                            <div class="md-6">
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea name="content" class="form-control" id="content"
                                        placeholder="Enter content" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" name="submit" class="btn it">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        src = "https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity = "sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin = "anonymous"
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        // Swiper initialization
        const swiper = new Swiper('.swiper', {
            loop: true, // Enable looping of slides
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            
            autoplay: {
                delay: 2500, // Delay between transitions (in ms)
                disableOnInteraction: false,
            },
        });
    </script>

</body>

</html>