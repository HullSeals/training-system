<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "David Sangrey";
$pgContent = "Training Scheduling";
$useIP = 1; //1 if Yes, 0 if No.

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training', $db['port']);
$platformList = [];
$res = $mysqli->query('SELECT * FROM lookups.platform_lu ORDER BY platform_id');
while ($platform = $res->fetch_assoc()) {
  $platformList[$platform['platform_id']] = $platform['platform_name'];
}

//Check PW and IRC Names
$stmt2 = $mysqli->prepare("SELECT COUNT(seal_name) AS num_cmdrs FROM sealsudb.staff WHERE seal_ID = ? AND del_flag != True");
$stmt2->bind_param("is", $user->data()->id, $user->data()->username);
$stmt2->execute();
$resultnum = $stmt2->get_result();
$resultnum = $resultnum->fetch_assoc();

$stmt3 = $mysqli->prepare('SELECT count(nick) as num_irc FROM ircDB.anope_db_NickAlias
INNER JOIN ircDB.anope_db_NickCore AS nc ON nc.display = nc
WHERE nc = ?');
$stmt3->bind_param("s", $user->data()->username);
$stmt3->execute();
$resultIRC = $stmt3->get_result();
$resultIRC = $resultIRC->fetch_assoc();

$typeList = [];
$res2 = $mysqli->query('SELECT * FROM lookups.training_lu ORDER BY training_ID');
while ($trainingType = $res2->fetch_assoc()) {
  if ($trainingType['training_description'] == 'Seal') {
    continue;
  }
  if ($trainingType['training_description'] == 'Accelerated Otter Pipeline') {
    continue;
  }
  $trainingList[$trainingType['training_ID']] = $trainingType['training_description'];
}
$validationErrors = [];
$lore = [];

if (isset($_GET['cancel'])) {
  foreach ($_REQUEST as $key => $value) {
    $lore[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
  }
  if (!count($validationErrors)) {
    $thenumersix = '6';
    $stmt4 = $mysqli->prepare('CALL spUpdateTrainingReq(?,?)');
    $stmt4->bind_param('ii', $lore['numberedt'], $thenumersix);
    $stmt4->execute();
    foreach ($stmt4->error_list as $error) {
      $validationErrors[] = 'DB: ' . $error['error'];
    }
    $stmt4->close();
    header("Location: .");
  }
}
if (isset($_GET['new'])) {
  if (!isset($_POST['days'])) {
    echo "No Days Set. Please try again!";
  } elseif (!isset($_POST['times'])) {
    echo "No Times Set. Please try again!";
  } elseif (!isset($_POST['type']) || $_POST['type'] == "Choose...") {
    echo "No Type Set. Please try again!";
  } elseif (!isset($_POST['platform']) || $_POST['platform'] == "Choose...") {
    echo "No Platform Set. Please try again!";
  } else {
    $daysboxes = $_POST['days'];
    $daysimploded = implode(',', $daysboxes);
    $daysexploded = explode(',', $daysimploded);
    $timesboxes = $_POST['times'];
    $timesimploded = implode(',', $timesboxes);
    $timesexploded = explode(',', $timesimploded);
    foreach ($_REQUEST as $key => $value) {
      if ($key != 'days' && $key != 'times') {
        $lore[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
      }
    }
    if (!count($validationErrors)) {
      $stmt = $mysqli->prepare('CALL spCreateTrainingReq(?,?,?,?,?,@schID)');
      $stmt->bind_param('iiiis', $user->data()->id, $lore['type'], $lore['platform'], $lore['numLessions'], $lgd_ip);
      $stmt->execute();
      foreach ($stmt->error_list as $error) {
        $validationErrors[] = 'DB: ' . $error['error'];
      }
      $result = mysqli_stmt_get_result($stmt);
      while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
        foreach ($row as $r) {
          $extractArray = $r;
        }
      }
      $stmt->close();
      foreach ($daysexploded as $dayEX) {
        $stmt2 = $mysqli->prepare('CALL spCreateTrainingDay(?,?)');
        $stmt2->bind_param('ii', $extractArray, $dayEX);
        $stmt2->execute();
        $stmt2->close();
      }
      foreach ($timesexploded as $timeEX) {
        $stmt3 = $mysqli->prepare('CALL spCreateTrainingTime(?,?)');
        $stmt3->bind_param('ii', $extractArray, $timeEX);
        $stmt3->execute();
        $stmt3->close();
      }
      header("Location: .");
    }
  }
}
?>
<h1>Pup Signup Form</h1>
<p>
  Welcome, CMDR. You can request to be scheduled for Seal trainings here.<br>
  Remember, you may be asked to serve as a stand-in client for another pup. All training is held on the Hull Seals IRC server in the #drill-channel.
  <br><br>
  <a href="https://time.is/UTC" target="_blank">Click Here</a> for a UTC conversion guide. You will receive an email when your drills are scheduled!
</p>
<?php
if ($resultnum['num_cmdrs'] === 0) { ?>
  <h4> You cannot submit a Training Request without a registered <a class="btn btn-secondary" target="_blank" href="https://hullseals.space/cmdr-management/">CMDR/Paperwork name</a>. Please fill that out before continuing!</h4>
<?php } elseif ($resultIRC['num_IRC'] === 0) { ?>
  <h4> You cannot submit a Training Request without a registered <a class="btn btn-secondary" target="_blank" href="https://hullseals.space/cmdr-management/irc-names">IRC name</a>. Please fill that out before continuing!</h4>
  <?php } else {
  $stmt = $mysqli->prepare("
            WITH sealsCTI
AS
(
    SELECT MIN(ID), seal_ID, seal_name
    FROM sealsudb.staff
    GROUP BY seal_ID
)
SELECT sr.sch_ID, platform_name, training_description, sch_max, sch_status, CONCAT(sch_nextdate, ', ', sch_nexttime) AS sch_next, ss2.seal_name AS trainer, sch_confirmed,
GROUP_CONCAT(DISTINCT tt.dt_desc ORDER BY tt.dt_ID ASC SEPARATOR ', ') AS 'times',
GROUP_CONCAT(DISTINCT td.dt_desc ORDER BY td.dt_ID ASC SEPARATOR ', ') AS 'days'
FROM training.schedule_requests AS sr
INNER JOIN lookups.platform_lu ON seal_PLT = platform_id
INNER JOIN lookups.training_lu ON sch_type = training_id
INNER JOIN training.sch_times AS st ON st.sch_ID = sr.sch_ID
INNER JOIN training.sch_days AS sd ON sd.sch_ID = sr.sch_ID
INNER JOIN training.ttime_lu AS tt ON tt.dt_ID = times_block
INNER JOIN training.tdate_lu AS td ON td.dt_ID = day_block
LEFT JOIN sealsCTI AS ss2 ON ss2.seal_ID = sr.sch_nextwith
WHERE sr.seal_ID = ? AND sch_status NOT IN (5,6)
GROUP BY sr.sch_ID;");
  $stmt->bind_param("i", $user->data()->id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows != 0) { ?>
    <em>Here is your current training request:</em>
    <table class="table table-dark table-striped table-bordered table-hover table-responsive-md">
      <tr>
        <td>Type</td>
        <td>Platform</td>
        <td>Time Blocks</td>
        <td>Days Avail.</td>
        <td>Max/Week</td>
        <td>Next Lesson</td>
        <td>Next Trainer</td>
        <td>Confirmed?</td>
        <td>Options</td>
      </tr>
      <?
      while ($row = $result->fetch_assoc()) {
        $field1name = $row['sch_ID'];
        $field2name = $row["training_description"];
        $field3name = $row["platform_name"];
        $field4name = $row["sch_max"];
        $field6name = $row["times"];
        $field7name = $row["days"];
        if ($row["sch_next"] == NULL) {
          $field9name = "Not Scheduled Yet";
        } else {
          $field9name = $row["sch_next"];
        }
        if ($row["trainer"] == NULL) {
          $field10name = "Not Assigned Yet";
        } else {
          $field10name = $row["trainer"];
        }
        if ($row["sch_confirmed"] == 0) {
          $field11name = "No";
        } else {
          $field11name = "Yes";
        } ?>
        <tr>
          <td><?= $field2name ?></td>
          <td><?= $field3name ?></td>
          <td><?= $field6name ?></td>
          <td><?= $field7name ?></td>
          <td><?= $field4name ?></td>
          <td><?= $field9name ?></td>
          <td><?= $field10name ?></td>
          <td><?= $field11name ?></td>
          <td><button type="button" class="btn btn-danger active" data-toggle="modal" data-target="#mo<?= $field1name ?>">Delete</button></td>
          <div class="modal fade" id="mo<?= $field1name ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel" style="color:black;">Cancel Training Request?</h5><button class="close" data-dismiss="modal" type="button"><span>&times;</span></button>
                </div>
                <div class="modal-body" style="color:black;">
                  Are you sure you want to cancel this training request?
                </div>
                <div class="modal-footer">
                  <form action="?cancel" method="post">
                    <input name="numberedt" required type="hidden" value="<?= $field1name ?>"> <button class="btn btn-danger" type="submit">Yes, Cancel.</button><button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php    }
    }
    echo '</table>';
    if ($result->num_rows === 0) { ?>
        <button class="btn btn-success btn-lg active" data-target="#moNew" data-toggle="modal" type="button">New Training Request</button>
      <?php } else { ?>
        <p> You may only have one training request at a time.</p>
    <?php }
    $result->free();
  }
    ?>
    <div class="modal fade" id="moNew" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel" style="color:black;">New Scheduling Request</h5><button class="close" data-dismiss="modal" type="button"><span>&times;</span></button>
          </div>
          <div class="modal-body" style="color:black;">
            <form action="?new" method="post">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Type of Training?</span>
                </div>
                <select class="custom-select" id="inputGroupSelect01" name="type" required>
                  <option disabled selected value="4">
                    Choose...
                  </option>
                  <?php
                  if (hasPerm([2], $user->data()->id)) {
                    foreach ($trainingList as $ttypeId => $ttypeName) {
                      echo '<option value="' . $ttypeId . '">' . $ttypename . '</option>';
                    }
                  } else { ?>
                    <option value=1 checked>Seal Basic Training</option>
                    <option value=6 checked>Accelerated Otter Pipeline</option>
                  <?php } ?>
                </select>
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Platform</span>
                </div>
                <select class="custom-select" id="inputGroupSelect01" name="platform" required>
                  <option disabled selected value="4">
                    Choose...
                  </option>
                  <?php
                  foreach ($platformList as $platformId => $platformName) {
                    echo '<option value="' . $platformId . '">' . $platformname . '</option>';
                  }
                  ?>
                </select>
              </div>
              <em>What days of the week are you available for Training?</em><br>
              <input type="checkbox" id="day1" name="days[]" value="1">
              <label for="day1"> Monday</label><br>
              <input type="checkbox" id="day2" name="days[]" value="2">
              <label for="day2"> Tuesday</label><br>
              <input type="checkbox" id="day3" name="days[]" value="3">
              <label for="day3"> Wednesday</label><br>
              <input type="checkbox" id="day4" name="days[]" value="4">
              <label for="day4"> Thursday</label><br>
              <input type="checkbox" id="day5" name="days[]" value="5">
              <label for="day5"> Friday</label><br>
              <input type="checkbox" id="day6" name="days[]" value="6">
              <label for="day6"> Saturday</label><br>
              <input type="checkbox" id="day7" name="days[]" value="7">
              <label for="day7"> Sunday</label><br>
              <em>What time blocks are you available for Training?</em> (All Times UTC)<br>
              <input type="checkbox" id="time1" name="times[]" value="1">
              <label for="time1"> 00:00-03:59</label><br>
              <input type="checkbox" id="time2" name="times[]" value="2">
              <label for="time2"> 04:00-07:59</label><br>
              <input type="checkbox" id="time3" name="times[]" value="3">
              <label for="time3"> 08:00-11:59</label><br>
              <input type="checkbox" id="time4" name="times[]" value="4">
              <label for="time4"> 12:00-15:59</label><br>
              <input type="checkbox" id="time5" name="times[]" value="5">
              <label for="time5"> 16:00-19:59</label><br>
              <input type="checkbox" id="time6" name="times[]" value="6">
              <label for="time6"> 20:00-23:59</label><br>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text">Max Number of Lessions per Week</span>
                </div>
                <input type="number" name="numLessions" min="1" max="4" required>
              </div>
              <div class="modal-footer">
                <button class="btn btn-primary" type="submit">Submit</button><button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>