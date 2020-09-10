<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

//DB Connection Info
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../assets/includes/headerCenter.php'; ?>
    <title>Lesson Scheduling | The Hull Seals</title>
    <meta content="Lesson Scheduling System" name="description">
</head>
<body>
    <div id="home">
      <?php include '../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Welcome, Pup!</h1>
        <p>Here you can view or edit your availability for lessons.</p>
        <br>
        <table class="table table-dark table-striped table-bordered table-hover table-responsive-md">
          <thead>
          <tr>
              <th>Module</th>
              <th>Status</th>
              <th>Length</th>
              <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php
          //Need DBinfo
          //$stmt = $mysqli->prepare("");
          //$stmt->bind_param("i", $user->data()->id);
          //$stmt->execute();
          //$result = $stmt->get_result();

          //while ($row = $result->fetch_assoc()) {
            //$field1name = $row["day"];
            //$field2name = $row["time"];
            //$field3name = $row["location"];
            //$field4name = $row["schedule_ID"];
            //echo '<tr>
            //  <td>'.$field2name.'</td>
            //  <td>'.$field1name.'</td>
            //  <td>'.$field3name.'</td>
            //  <td><a href="edit-availability.php?cne='.$field4name.'" class="btn btn-warning active">Edit</a></td>
            //  <td><a href="rem-irc.php?cne='.$field4name.'" class="btn btn-danger active">Delete</a></td>
            //</tr>';
          //}
          //$result->free();
          ?>
        </tbody>
        </table>
        <p><a href="add-schedule.php" class="btn btn-success btn-lg active">New Scheduling Availability</a><a href=".." class="btn btn-small btn-danger" style="float: right;">Go To Training Index</a></p>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
</body>
</html>
