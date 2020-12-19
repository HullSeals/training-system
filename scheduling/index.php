<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

//IP Tracking Stuff
require '../../assets/includes/ipinfo.php';

$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training_records', $db['port']);
$platformList = [];
$res = $mysqli->query('SELECT * FROM lookups.platform_lu ORDER BY platform_id');
while ($burgerking = $res->fetch_assoc())
{
    $platformList[$burgerking['platform_id']] = $burgerking['platform_name'];
}
?>
<!DOCTYPE html>
  <html lang="en">
  <head>
      <meta content="Trainee Signup Form" name="description">
      <title>Trainee Signup Form | The Hull Seals</title>
      <?php include '../../assets/includes/headerCenter.php'; ?>
  </head>
  <body>
    <div id="home">
      <?php include '../../assets/includes/menuCode.php';?>
      <section class="introduction container">
        <article id="intro3">
          <h1>Pup Signup Form</h1>
          <p>
            Welcome, CMDR. You can request to be scheduled for Seal trainings here.<br>
            Remember, you may be asked to serve as a stand-in client for another pup. All training is held on the Hull Seals IRC server in the #drill-channel.
            <br><br>
            You will receive an email when your drills are scheduled!
          </p>
          <?php
            $stmt = $mysqli->prepare("SELECT sch_ID, platform_name, training_description, sch_max, loc_description
            FROM training.schedule_requests
            INNER JOIN lookups.platform_lu ON seal_PLT = platform_id
            INNER JOIN lookups.training_lu ON sch_type = training_id
            INNER JOIN lookups.traininglocation_lu ON seal_LOC = loc_ID
            WHERE seal_ID = ?");
				    $stmt->bind_param("i", $user->data()->id);
				    $stmt->execute();
            $result = $stmt->get_result();
				    if($result->num_rows === 0) {
              $norows = 1;
              }
            else {
              $norows = 0;
            echo '<em>Here is your current training request:</em>
            <table class="table table-dark table-striped table-bordered table-hover table-responsive-md">
              <tr>
                <td>Type</td>
                <td>Platform</td>
                <td>Location</td>
                <td>Time Blocks</td>
                <td>Days of the Week</td>
                <td>Max Lessions per Week</td>
                <td colspan="2">Options</td>
              </tr>';
              while ($row = $result->fetch_assoc()) {
				            $field1name = $row['sch_ID'];
				            $field2name = $row["training_description"];
				            $field3name = $row["platform_name"];
                    $field4name = $row["sch_max"];
                    $field5name = $row["loc_description"];
              echo '<tr>
                <td>'.$field2name.'</td>
                <td>'.$field3name.'</td>
                <td>'.$field5name.'</td>
                <td></td>
                <td></td>
                <td>'.$field4name.'</td>
                <td><button type="button" class="btn btn-warning active" data-toggle="modal" data-target="#moE'.$field1name.'">Edit</button></td>
				        <td><button type="button" class="btn btn-danger active" data-toggle="modal" data-target="#mo'.$field1name.'">Delete</button></td>';
              }
            }
            echo '</table>';
            if($norows === 1) {
              echo '<p> You may only have one training request at a time.</p>';
            }
            else {
              echo '<button class="btn btn-success btn-lg active" data-target="#moNew" data-toggle="modal" type="button">New Training Request</button>';
            }
            $result->free();
          ?>
        </article>
        <div class="clearfix"></div>
      </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
  </body>
</html>
