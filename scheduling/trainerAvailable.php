<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "David Sangrey";
$pgContent = "Set Trainer Availability";
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
  $trainingList[$trainingType['training_ID']] = $trainingType['training_description'];
}

$lore = [];
$validationErrors = 0;
if (isset($_GET['cancel'])) {
  foreach ($_REQUEST as $key => $value) {
    $lore[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
  }
  if (!isset($lore["numberedt"])) {
    sessionValMessages("Error! No availability index set! Availability not canceled.");
    $validationErrors += 1;
  }
  if ($validationErrors == 0) {
    $thenumersix = '6';
    $stmt4 = $mysqli->prepare('CALL spUpdateAvailReq(?,?)');
    $stmt4->bind_param('ii', $lore['numberedt'], $thenumersix);
    $stmt4->execute();
    $stmt4->close();
    header("Location: .");
  }
}
if (isset($_GET['new'])) {
  if (!isset($_POST["platform"])) {
    sessionValMessages("Error! No platform set! Availability not saved.");
    $validationErrors += 1;
  }
  if (!isset($_POST['days'])) {
    sessionValMessages("Error! No days set! Availability not saved.");
    $validationErrors += 1;
  }
  if (!isset($_POST['times'])) {
    sessionValMessages("Error! No times set! Availability not saved.");
    $validationErrors += 1;
  }
  if ($validationErrors == 0) {
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
    $stmt = $mysqli->prepare('CALL spCreateAvailReq(?,?,?,?,?,@schID)');
    $stmt->bind_param('iiiis', $user->data()->id, $lore['type'], $lore['platform'], $lore['numLessions'], $lgd_ip);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
      foreach ($row as $r) {
        $extractArray = $r;
      }
    }
    $stmt->close();
    foreach ($daysexploded as $dayEX) {
      $stmt2 = $mysqli->prepare('CALL spCreateAvailDay(?,?)');
      $stmt2->bind_param('ii', $extractArray, $dayEX);
      $stmt2->execute();
      $stmt2->close();
    }
    foreach ($timesexploded as $timeEX) {
      $stmt3 = $mysqli->prepare('CALL spCreateAvailTime(?,?)');
      $stmt3->bind_param('ii', $extractArray, $timeEX);
      $stmt3->execute();
      $stmt3->close();
    }
    header("Location: .");
  }
}
?>
<h1>Trainer Availability</h1>
<p>
  Welcome, Trainer. You can submit your availability for Seal trainings here.<br>
  All training is held on the Hull Seals IRC server in the #drill-channel.
  <br><br>
  You will receive an email when your drills are scheduled!
</p>
<?php
if ($resultIRC['num_IRC'] === 0) { ?>
  <h4> You cannot submit a Training Request without a registered <a class="btn btn-secondary" target="_blank" href="https://hullseals.space/cmdr-management/">CMDR/Paperwork name</a>. Please fill that out before continuing!</h4>
<?php } elseif ($resultnum['num_cmdrs'] === 0) { ?>
  <h4> You cannot submit a Training Request without a registered <a class="btn btn-secondary" target="_blank" href="https://hullseals.space/cmdr-management/irc-names">IRC name</a>. Please fill that out before continuing!</h4>
  <?php } else {
  # TODO: SP this?
  $stmt = $mysqli->prepare("WITH sealsCTI
AS
(
    SELECT MIN(ID), seal_ID, seal_name
    FROM sealsudb.staff
    GROUP BY seal_ID
)
SELECT sr.sch_ID, platform_name, sch_max, sch_status,
GROUP_CONCAT(DISTINCT tt.dt_desc ORDER BY tt.dt_ID ASC SEPARATOR ', ') AS 'times',
GROUP_CONCAT(DISTINCT td.dt_desc ORDER BY td.dt_ID ASC SEPARATOR ', ') AS 'days'
FROM training.tra_avail AS sr
INNER JOIN lookups.platform_lu ON seal_PLT = platform_id
INNER JOIN training.tra_times AS st ON st.sch_ID = sr.sch_ID
INNER JOIN training.tra_days AS sd ON sd.sch_ID = sr.sch_ID
INNER JOIN training.ttime_lu AS tt ON tt.dt_ID = times_block
INNER JOIN training.tdate_lu AS td ON td.dt_ID = day_block
WHERE sr.seal_ID = ? AND sch_status NOT IN (5,6)
GROUP BY sr.sch_ID;");
  $stmt->bind_param("i", $user->data()->id);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows != 0) {
  ?>
    <em>Here is your current availability:</em>
    <table class="table table-dark table-striped table-bordered table-hover table-responsive-md">
      <tr>
        <td>Platform</td>
        <td>Time Blocks</td>
        <td>Days Avail.</td>
        <td>Max/Week</td>
        <td>Options</td>
      </tr>
      <?php
      while ($row = $result->fetch_assoc()) {
        $field1name = $row['sch_ID'];
        $field3name = $row["platform_name"];
        $field4name = $row["sch_max"];
        $field6name = $row["times"];
        $field7name = $row["days"];
      ?>
        <tr>
          <td><?= $field3name ?></td>
          <td><?= $field6name ?></td>
          <td><?= $field7name ?></td>
          <td><?= $field4name ?></td>
          <td><button type="button" class="btn btn-danger active" data-toggle="modal" data-target="#mo<?= $field1name ?>">Delete</button></td>
          <div class="modal fade" id="mo<?= $field1name ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel" style="color:black;">Cancel Training Availability?</h5><button class="close" data-dismiss="modal" type="button"><span>&times;</span></button>
                </div>
                <div class="modal-body" style="color:black;">
                  Are you sure you want to cancel this availability?
                </div>
                <div class="modal-footer">
                  <form action="?cancel" method="post">
                    <input name="numberedt" required type="hidden" value="<?= $field1name ?>"> <button class="btn btn-danger" type="submit">Yes, Cancel.</button><button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
      <?php
      }
    } ?>
    </table>
    <button class="btn btn-success btn-lg active" data-target="#moNew" data-toggle="modal" type="button">New Training Availability</button>';
  <?
  $result->free();
}
  ?>
  <div class="modal fade" id="moNew" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="color:black;">New Availability</h5><button class="close" data-dismiss="modal" type="button"><span>&times;</span></button>
        </div>
        <div class="modal-body" style="color:black;">
          <form action="?new" method="post">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text">Platform</span>
              </div>
              <select class="custom-select" id="inputGroupSelect01" name="platform" required>
                <option disabled selected value="0">
                  Choose...
                </option>
                <?php
                foreach ($platformList as $platformId => $platformName) {
                  echo '<option value="' . $platformId . '">' . $platformName . '</option>';
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
              <input type="number" name="numLessions" min="1" max="7" required>
            </div>
            <div class="modal-footer">
              <button class="btn btn-primary" type="submit">Submit</button><button class="btn btn-secondary" data-dismiss="modal" type="button">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <hr><a href="requests.php" class="btn btn-small btn-danger" style="float: right;">Go Back</a><br>
  <?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>