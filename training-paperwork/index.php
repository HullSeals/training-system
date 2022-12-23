<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "David Sangrey";
$pgContent = "File Drill Paperwork";
$useIP = 1; //1 if Yes, 0 if No.

$customContent = '<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
 <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/bootstrap-tokenfield.js"></script>
 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tokenfield/0.12.0/css/bootstrap-tokenfield.min.css">
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
 <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
 <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js" integrity="sha384-Q9RsZ4GMzjlu4FFkJw4No9Hvvm958HqHmXI9nqo5Np2dA/uOVBvKVxAvlBQrDhk4" crossorigin="anonymous"></script>';

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training_records', $db['port']);
$platformList = [];
$res = $mysqli->query('SELECT * FROM lookups.platform_lu ORDER BY platform_id');
while ($platformInfo = $res->fetch_assoc()) {
  $platformList[$platformInfo['platform_id']] = $platformInfo['platform_name'];
}

$statusList = [];
$res = $mysqli->query('SELECT * FROM lookups.status_lu ORDER BY status_id');
while ($casestat2 = $res->fetch_assoc()) {
  if ($casestat2['status_name'] == 'Open') {
    continue;
  }
  if ($casestat2['status_name'] == 'On Hold') {
    continue;
  }
  if ($casestat2['status_name'] == 'Delete Case') {
    continue;
  }

  $statusList[$casestat2['status_id']] = $casestat2['status_name'];
}

$stmt3 = $mysqli->prepare("SELECT COUNT(seal_name) AS num_cmdrs FROM sealsudb.staff WHERE seal_ID = ? AND del_flag != True");
$stmt3->bind_param("i", $user->data()->id);
$stmt3->execute();
$resultnum = $stmt3->get_result();
$resultnum = $resultnum->fetch_assoc();

$validationErrors = 0;
$data = [];
if (isset($_GET['send'])) {
  foreach ($_REQUEST as $key => $value) {
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
  }
  if (strlen($data['client_nm']) > 45) {
    sessionValMessages("CMDR name too long. Please try again.");
    $validationErrors += 1;
  }
  if (strlen($data['curr_sys']) > 100) {
    sessionValMessages("System name too long. Please try again.");
    $validationErrors += 1;
  }
  if ($data['hull'] > 100 || $data['hull'] < 1) {
    sessionValMessages("Error! Invalid hull set! Please try again.");
    $validationErrors += 1;
  }
  $data['cb'] = isset($data['cb']);
  if (isset($data['dispatched'])) {
    $data['dispatched'] = isset($data['dispatched']);
  } else {
    $data['dispatched'] = 0;
  }
  if (!isset($platformList[$data['platypus']])) {
    sessionValMessages("Error! No platform set! Please try again.");
    $validationErrors += 1;
  }
  if (!isset($statusList[$data['case_stat']])) {
    sessionValMessages("Error! No case status set! Please try again.");
    $validationErrors += 1;
  }
  if (!isset($lgd_ip)) {
    sessionValMessages("Error! Unable to log IP Address! Please contact the Cyberseals.");
    $validationErrors += 1;
  }
  if ($data['dispatched'] == 0 && (!isset($data['dispatcher']) || empty($data['dispatcher']))) {
    sessionValMessages("Please set the Dispatchers and try again.");
    $validationErrors += 1;
  }
  if ($validationErrors == 0) {
    $stmt = $mysqli->prepare('CALL spTempCreateHSCaseCleaner(?,?,?,?,?,?,?,?,?,?,@caseID)');
    $stmt->bind_param('ssiiiiisis', $data['client_nm'], $data['curr_sys'], $data['hull'], $data['cb'], $data['platypus'], $data['case_stat'], $data['dispatched'], $data['notes'], $user->data()->id, $lgd_ip);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
      foreach ($row as $r) {
        $extractArray = $r;
      }
    }
    $stmt->close();
    $disparray = explode(", ", $data['dispatcher']);
    foreach ($disparray as $dispNM) {
      $thenumber1 = 1;
      $stmt2 = $mysqli->prepare('CALL spCreateCaseAssigned(?,?,?,?)');
      $stmt2->bind_param('isii', $extractArray, $dispNM, $thenumber1, $thenumber1);
      $stmt2->execute();
      $stmt2->close();
    }
    $osarray = explode(", ", $data['other_seals']);
    foreach ($osarray as $osNM) {
      $stmt3 = $mysqli->prepare('CALL spCreateCaseAssigned(?,?,?,?)');
      $thenumber1 = 1;
      $thenumber2 = 2;
      $stmt3->bind_param('isii', $extractArray, $osNM, $thenumber1, $thenumber2);
      $stmt3->execute();
      $stmt3->close();
    }
    header("Location: success.php");
  }
}
?>
<h1 style="text-align: center;">DRILL - DRILL - DRILL</h1>
<h5>Complete for Drill Cases Only. Do NOT complete for Seal repairs.</h5>
<hr>
<?php
if ($resultnum['num_cmdrs'] === 0) { ?>
  <div class="alert alert-danger" role="alert">
    <h2> You cannot file paperwork without a valid CMDR registered.</h2><a href="https://hullseals.space/cmdr-management/" class="alert-link" target="_blank">Click Here</a> to set one, then refresh the page.
  </div>
<?php } else { ?>
  <form action="?send" method="post">
    <div class="input-group mb-3">
      <p>Your ID has been logged as <?= echousername($user->data()->id); ?>. You will be entered as the Lead Seal.</p>
      <p>Do not enter yourself as either a Dispatcher or another Seal.</p>
    </div>
    <div class="input-group mb-3">
      <input class="form-control" name="client_nm" pattern="[\x20-\x7A]+" minlength="3" placeholder="Client Name" title="The Client name in standard characters" required type="text" value="<?= $data['client_nm'] ?? '' ?>">
    </div>
    <div class="input-group mb-3">
      <input class="form-control" name="curr_sys" pattern="[\x20-\x7A]+" minlength="3" placeholder="System" title="The System name in standard characters" required type="text" value="<?= $data['curr_sys'] ?? '' ?>">
    </div>
    <div class="input-group mb-3">
      <input class="form-control" max="100" min="0" pattern="[0-9]" name="hull" placeholder="Starting Hull %" required type="number" value="<?= $data['hull'] ?? '' ?>">
    </div>
    <div class="input-group mb-3">
      <label class="input-group-text text-primary"><input name="cb" type="checkbox" value="1" data-toggle="toggle" data-on="Canopy Breached" data-off="Canopy Not Breached" data-onstyle="danger" data-offstyle="success"> </label>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Platform</span>
      </div><select class="custom-select" id="inputGroupSelect01" name="platypus" required>
        <?php foreach ($platformList as $platformId => $platformName) {
          echo '<option value="' . $platformId . '">' . $platformName . '</option>';
        } ?>
      </select>
    </div>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Was the Case Successful?</span>
      </div><select class="custom-select" id="inputGroupSelect01" name="case_stat" required>
        <?php foreach ($statusList as $statusId => $statusName) {
          echo '<option value="' . $statusId . '">' . $statusName . '</option>';
        } ?>
      </select>
    </div>
    <div class="input-group mb-3">
      <label class="input-group-text text-primary"><input name="dispatched" type="checkbox" value="1" data-toggle="toggle" data-on="Self-Dispatched" data-off="Dispatched Case" data-onstyle="danger" data-offstyle="success"></label>
    </div>
    <div class="input-group mb-3">
      <input class="form-control" id="dispatcher" name="dispatcher" placeholder="Who was Dispatching? (If None, Leave Blank)" type="text" value="<?= $data['dispatcher'] ?? '' ?>">
    </div>
    <div class="input-group mb-3">
      <input class="form-control" id="other_seals" name="other_seals" placeholder="Other Seals on the Case? (If None, Leave Blank)" type="text" value="<?= $data['other_seals'] ?? '' ?>">
    </div>
    <div class="input-group mb-3">
      <textarea required minlength="10" pattern="[\x20-\x7F]+" class="form-control" name="notes" placeholder="Notes (Required).
          Suggested notes include:
          - Distance Traveled
          - Unique or Unusual details about the repair
          - Number of Limpets used, Client Ship Type, or other details." rows="5"><?= $data['notes'] ?? '' ?>
</textarea>
    </div><button class="btn btn-primary" type="submit">Submit</button>
  </form>
<?php } ?>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
<script type="text/javascript">
  $('#other_seals').tokenfield({
    autocomplete: {
      source: function(request, response) {
        jQuery.get("fetch.php", {
          query: request.term
        }, function(data) {
          data = $.parseJSON(data);
          response(data);
        });
      },
      delay: 100
    },
  });
</script>
<script type="text/javascript">
  $('#dispatcher').tokenfield({
    autocomplete: {
      source: function(request, response) {
        jQuery.get("fetch.php", {
          query: request.term
        }, function(data) {
          data = $.parseJSON(data);
          response(data);
        });
      },
      delay: 100
    },
  });
</script>