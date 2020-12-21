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
$typeList = [];
$res2 = $mysqli->query('SELECT * FROM lookups.training_lu ORDER BY training_ID');
while ($trainingType = $res2->fetch_assoc())
{
    $trainingList[$trainingType['training_ID']] = $trainingType['training_description'];
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
            $stmt = $mysqli->prepare("SELECT sr.sch_ID, platform_name, training_description, sch_max, loc_description,
GROUP_CONCAT(distinct tt.dt_desc ORDER BY tt.dt_ID ASC  SEPARATOR ', ') AS 'times',
GROUP_CONCAT(distinct td.dt_desc ORDER BY td.dt_ID ASC SEPARATOR ', ') AS 'days'
FROM training.schedule_requests as sr
INNER JOIN lookups.platform_lu ON seal_PLT = platform_id
INNER JOIN lookups.training_lu ON sch_type = training_id
INNER JOIN lookups.traininglocation_lu ON seal_LOC = loc_ID
INNER JOIN training.sch_times as st ON st.sch_ID = sr.sch_ID
INNER JOIN training.sch_days as sd ON sd.sch_ID = sr.sch_ID
INNER JOIN training.ttime_lu as tt ON tt.dt_ID = times_block
INNER JOIN training.tdate_lu as td ON td.dt_ID = day_block
WHERE seal_ID = ?;");
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
                    $field6name = $row["times"];
                    $field7name = $row["days"];
              echo '<tr>
                <td>'.$field2name.'</td>
                <td>'.$field3name.'</td>
                <td>'.$field5name.'</td>
                <td>'.$field6name.'</td>
                <td>'.$field7name.'</td>
                <td>'.$field4name.'</td>
                <td><button type="button" class="btn btn-warning active" data-toggle="modal" data-target="#moE'.$field1name.'">Edit</button></td>
				        <td><button type="button" class="btn btn-danger active" data-toggle="modal" data-target="#mo'.$field1name.'">Delete</button></td>';
              echo '
              <div aria-hidden="true" class="modal fade" id="mo'.$field1name.'" tabindex="-1">
		            <div class="modal-dialog modal-dialog-centered">
			             <div class="modal-content">
				               <div class="modal-header">
					                  <h5 class="modal-title" id="exampleModalLabel" style="color:black;">Cancel Training Request?</h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				               </div>
				               <div class="modal-body" style="color:black;">
					                  Are you sure you want to cancel this training request?
				               </div>
				               <div class="modal-footer">
					                  <form action="?cancel" method="post">
						                      <input name="numberedt" required="" type="hidden" value="'.$field1name.'"> <button class="btn btn-danger" type="submit">Yes, Cancel.</button><button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
					                  </form>
				               </div>
			             </div>
		            </div>
	            </div>';

              echo '
              <div aria-hidden="true" class="modal fade" id="moE'.$field1name.'" tabindex="-1">
            		<div class="modal-dialog modal-dialog-centered">
            			<div class="modal-content">
            				<div class="modal-header">
            					<h5 class="modal-title" id="exampleModalLabel" style="color:black;">Edit Scheduling Request</h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            				</div>
            				<div class="modal-body" style="color:black;">
            					<form action="?edit" method="post">

            						<div class="modal-footer">
            							<button class="btn btn-primary" type="submit">Submit</button><button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
            						</div>
            					</form>
            				</div>
            			</div>
            		</div>
            	</div>';              }
            }
            echo '</table>';
            //if($norows === 1) {
              echo '<button class="btn btn-success btn-lg active" data-target="#moNew" data-toggle="modal" type="button">New Training Request</button>';
            //} TODO: remove these comments before live.
            //else {
              echo '<p> You may only have one training request at a time.</p>';
            //}
            $result->free();
          ?>
          <div aria-hidden="true" class="modal fade" id="moNew" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel" style="color:black;">New Scheduling Request</h5><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="color:black;">
                  <form action="?new" method="post">
                    <em>Where would you like to be Trained?</em><br>
                        <input type="radio" id="bubble" name="location" value="1">
                        <label for="bubble">Request Training in the Bubble</label><br>
                        <input type="radio" id="colonia" name="location" value="2">
                        <label for="colonia">Request Training in Colonia</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                 <span class="input-group-text">Type of Training?</span>
                            </div>
                            <select class="custom-select" id="inputGroupSelect01" name="type" required="">
                                 <option disabled selected value="4">
                                       Choose...
                                 </option>
                                 <?php
                                    foreach ($trainingList as $ttypeId => $ttypeName) {
                                          echo '<option value="' . $ttypeId . '"' . ($trainingType['type'] == $ttypeName ? ' checked' : '') . '>' . $ttypeName . '</option>';
                                    }
                                  ?>
                            </select>
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                 <span class="input-group-text">Platform</span>
                            </div>
                            <select class="custom-select" id="inputGroupSelect01" name="platform" required="">
                                 <option disabled selected value="4">
                                       Choose...
                                 </option>
                                 <?php
                                    foreach ($platformList as $platformId => $platformName) {
                                          echo '<option value="' . $platformId . '"' . ($trainingType['platform'] == $platformId ? ' checked' : '') . '>' . $platformName . '</option>';
                                    }
                                  ?>
                            </select>
                        </div>
                        <em>What days of the week are you available for Training?</em><br>
                        <input type="checkbox" id="day1" name="day1" value="1">
                        <label for="day1"> Monday</label><br>
                        <input type="checkbox" id="day2" name="day2" value="2">
                        <label for="day1"> Tuesday</label><br>
                        <input type="checkbox" id="day3" name="day3" value="3">
                        <label for="day1"> Wednesday</label><br>
                        <input type="checkbox" id="day4" name="day4" value="4">
                        <label for="day1"> Thursday</label><br>
                        <input type="checkbox" id="day5" name="day5" value="5">
                        <label for="day1"> Friday</label><br>
                        <input type="checkbox" id="day6" name="day6" value="6">
                        <label for="day1"> Saturday</label><br>
                        <input type="checkbox" id="day7" name="day7" value="7">
                        <label for="day1"> Sunday</label><br>
                        <em>What time blocks are you available for Training?</em> (All Times UTC)<br>
                        <input type="checkbox" id="time1" name="time1" value="1">
                        <label for="time1"> 00:00-03:59</label><br>
                        <input type="checkbox" id="time2" name="time2" value="2">
                        <label for="time1"> 04:00-07:59</label><br>
                        <input type="checkbox" id="time3" name="time3" value="3">
                        <label for="time1"> 08:00-11:59</label><br>
                        <input type="checkbox" id="time4" name="time4" value="4">
                        <label for="time1"> 12:00-15:59</label><br>
                        <input type="checkbox" id="time5" name="time5" value="5">
                        <label for="time1"> 16:00-19:59</label><br>
                        <input type="checkbox" id="time6" name="time6" value="6">
                        <label for="time1"> 20:00-23:59</label><br>
                      <div class="input-group mb-3">
		                      <div class="input-group-prepend">
			                         <span class="input-group-text">Max Number of Lessions per Week</span>
		                      </div>
                          <input type="number" id = "numLessions" min="1" max="4">
	                    </div>
                    <div class="modal-footer">
                      <button class="btn btn-primary" type="submit">Submit</button><button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </article>
        <div class="clearfix"></div>
      </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
  </body>
</html>
