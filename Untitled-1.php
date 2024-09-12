  <?php if ($_SESSION['role'] == 1 ){?>
  <td>
      <?php
       if ($l["status"] == 0) {
           echo "Pending";
      } elseif ($l["status"] == 1) {
           echo "Approved";
      } else {
           // Optionally handle unexpected values
     echo "Unknown Status";
      }
      ?>
  </td>
  <?php } ?>






  <?php if ($_SESSION['role'] == 0) { ?>
    <td>
        <?php
        if ($l['status'] == 0) {
            echo "Pending";
        } elseif ($l['status'] == 1) {
            echo "Approved";
        } else {
            echo "Unknown Status";
        }
        ?>
    </td>
<?php } elseif ($_SESSION['role'] == 1) { ?>
    <td>
        <?php
        if ($l['status'] == 0) {
            echo "<button>Pending</button>";
        } elseif ($l['status'] == 1) {
            echo "<button>Approved</button>";
        } elseif ($l['status'] == 2) {
            echo "<button>Rejected</button>";
        }
        ?>
    </td>
<?php } ?>
