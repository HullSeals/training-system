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
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training', $db['port']);

$statusList = [];
$res3 = $mysqli->query('SELECT * FROM tstatus_lu ORDER BY st_ID');
while ($statusType = $res3->fetch_assoc())
{
    $statusList[$statusType['st_ID']] = $statusType['st_desc'];
}
$trainerList = [];
$res4 = $mysqli->query('WITH sealsCTI
AS
(
    SELECT MIN(ID), seal_ID, seal_name
    FROM sealsudb.staff
    GROUP BY seal_ID
)
SELECT seal_name, seal_ID FROM sealsCTI
INNER JOIN auth.user_permission_matches AS aup ON aup.user_ID = sealsCTI.seal_ID
WHERE aup.permission_ID = 4;');
while ($trainerType = $res4->fetch_assoc())
{
    $trainerList[$trainerType['seal_ID']] = $trainerType['seal_name'];
}

?>
<!DOCTYPE html>
  <html lang="en">
  <head>
      <meta content="Requested Trainings" name="description">
      <title>Requested Trainings | The Hull Seals</title>
      <?php include '../../assets/includes/headerCenter.php'; ?>
      <style>
      .separator {
  display: flex;
  align-items: center;
  text-align: center;
}

.separator::before,
.separator::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid #000;
}

.separator:not(:empty)::before {
  margin-right: .25em;
}

.separator:not(:empty)::after {
  margin-left: .25em;
}
</style>
  </head>
  <body>
    <div id="home">
      <?php include '../../assets/includes/menuCode.php';?>
      <section class="introduction container">
        <article id="intro3">
          <h1>All Requested Drills</h1>
          <p>
            Welcome, Trainer. Here are all current Seal training requests:
          </p>
          <?php
            $stmt = $mysqli->prepare("WITH sealsCTI
AS
(
    SELECT MIN(ID), seal_ID, seal_name
    FROM sealsudb.staff
    GROUP BY seal_ID
)
SELECT sr.sch_ID, platform_name, training_description, sch_max, st_desc, seal_name, CONCAT(sch_nextdate, ',', sch_nexttime) AS sch_next, sch_nextwith,
GROUP_CONCAT(DISTINCT tt.dt_desc ORDER BY tt.dt_ID ASC SEPARATOR ', ') AS 'times',
GROUP_CONCAT(DISTINCT td.dt_desc ORDER BY td.dt_ID ASC SEPARATOR ', ') AS 'days'
FROM training.schedule_requests AS sr
INNER JOIN lookups.platform_lu ON seal_PLT = platform_id
INNER JOIN lookups.training_lu ON sch_type = training_id
INNER JOIN training.sch_times AS st ON st.sch_ID = sr.sch_ID
INNER JOIN training.sch_days AS sd ON sd.sch_ID = sr.sch_ID
INNER JOIN training.ttime_lu AS tt ON tt.dt_ID = times_block
INNER JOIN training.tdate_lu AS td ON td.dt_ID = day_block
INNER JOIN training.tstatus_lu AS tl ON tl.st_ID = sch_status
INNER JOIN sealsCTI AS ss ON ss.seal_ID = sr.seal_ID
WHERE sch_status NOT IN (5,6)
GROUP BY sr.sch_ID;");
				    $stmt->execute();
            $result = $stmt->get_result();
				    if($result->num_rows === 0) {
              echo '<em>No Active Training Requests</em>';
              }
            else {
              $norows = 0;
            echo '<em>All Active Training Requests:</em>
            <table class="table table-dark table-striped table-bordered table-hover table-responsive-md">
              <tr>
                <td>CMDR</td>
                <td>Platform</td>
                <td>Type</td>
                <td>Blocks</td>
                <td>Days</td>
                <td>Max / Week</td>
                <td>Status</td>
                <td>Next Scheduled</td>
                <td>With</td>
                <td>Options</td>
              </tr>';
              while ($row = $result->fetch_assoc()) {
                if (!isset($row['seal_name'])) {
                  $field1name = "ERROR!";
                }
                else {
                  $field1name = $row['seal_name'];
                }
				            $field2name = $row["platform_name"];
                    $field3name = $row["training_description"];
                    $field4name = $row["times"];
                    $field5name = $row["days"];
                    $field6name = $row["sch_max"];
                    $field7name = $row["sch_ID"];
                    $field8name = $row["st_desc"];
                    if ($row["sch_next"] == NULL) {
                      $field9name = "No Drill Scheduled";
                    }
                    else {
                      $field9name = $row["sch_next"];
                    }
                    if ($row["sch_nextwith"] == NULL) {
                      $field10name = "No Drill Scheduled";
                    }
                    else {
                      $field10name = $row["sch_next"];
                    }
              echo '<tr>
              <td>'.$field1name.'</td>
              <td>'.$field2name.'</td>
              <td>'.$field3name.'</td>
              <td>'.$field4name.'</td>
              <td>'.$field5name.'</td>
              <td>'.$field6name.'</td>
              <td>'.$field8name.'</td>
              <td>'.$field9name.'</td>
              <td>'.$field10name.'</td>
				      <td><button type="button" class="btn btn-warning active" data-toggle="modal" data-target="#mo'.$field7name.'">Options</button></td>';
              echo '
              <div aria-hidden="true" class="modal fade" id="mo'.$field7name.'" tabindex="-1">
		            <div class="modal-dialog modal-dialog-centered">
			             <div class="modal-content">
				               <div class="modal-header">
					                  <h5 class="modal-title" id="exampleModalLabel" style="color:black;">Modify Scheduling Request</h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				               </div>
				               <div class="modal-body" style="color:black;">
					                  <form action="?setTraining" method="post">
                              <label for="date'.$field7name.'">Next Drill Date: </label>
                              <input type="date" id="date'.$field7name.'" name="date'.$field7name.'">
                                &nbsp;
                              <label for="time'.$field7name.'">Next Drill Time: </label>
                              <input type="time" id="time'.$field7name.'" name="time'.$field7name.'">
                              <input name="numberedt" required="" type="hidden" value="'.$field7name.'">
                              &nbsp;
                              <label>Choose Trainer: </label>
                              <select name="tname" required="">
                                   <option disabled selected value="1">
                                         Choose...
                                   </option>';
                                      foreach ($trainerList as $trainerId => $trainerName) {
                                            echo '<option value="' . $trainerId . '"' . ($trainerType['status'] == $trainerId ? ' checked' : '') . '>' . $trainerName . '</option>';
                                      }
                              echo '</select>

                                <br>
                              <button class="btn btn-primary" type="submit">Update Drill Time</button>
                            </form>
                            <div class="separator">OR</div><br>
                            <form action="?setStatus" method="post">
                              <input name="numberedt" required="" type="hidden" value="'.$field7name.'">
                              <label>Update Training Status: </label>
                              <select name="tstatus" required="">
                                   <option disabled selected value="1">
                                         Choose...
                                   </option>';
                                      foreach ($statusList as $statusId => $statusName) {
                                            echo '<option value="' . $statusId . '"' . ($statusType['status'] == $statusId ? ' checked' : '') . '>' . $statusName . '</option>';
                                      }
                              echo '</select>
                              <button class="btn btn-primary" type="submit">Update Training Status</button>
                            </form>
				               </div>
				               <div class="modal-footer">
						                 <button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
				               </div>
			             </div>
		            </div>
	            </div>';
            }
            }
            echo '</table>';
            $result->free();
          ?>
        </article>
        <div class="clearfix"></div>
      </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
  </body>
</html>
