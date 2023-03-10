<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "David Sangrey";
$pgContent = "Manage Trainee";
$useIP = 0; //1 if Yes, 0 if No.

//If you have any custom scripts, CSS, etc, you MUST declare them here.
//They will be inserted at the bottom of the <head> section.
$customContent = '<script type="text/javascript" src="../assets/datatables.min.js"></script>
    <script>
    	$(document).ready(function() {
    	$(\'#LookupList\').DataTable();
  		} );
  	</script>';

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}
if (!isset($_GET['cne'])) {
  Redirect::to('index.php');
}

//Authenticaton Info
$auth = require 'auth.php';
$hmackey = $auth['key'];
$constant = $auth['constant'];
$url = $auth['url'];

//Who are we working with?
$beingManaged = $_GET['cne'];
$beingManaged = intval($beingManaged);
$thenumber1 = "1";
$thenumber2 = "2";
$beingManagedName = echousername($beingManaged);

//SQL for the first part
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'auth', $db['port']);

//IRC SQL
$mysqlirc = new mysqli($db['server'], $db['user'], $db['pass'], 'records', $db['port']);
$stmtirc = $mysqlirc->prepare("SELECT nick FROM ircDB.anope_db_NickAlias WHERE nc = ? LIMIT 1;");
$stmtirc->bind_param("s", $beingManagedName);
$stmtirc->execute();
$resultirc1 = $stmtirc->get_result();
while ($row = $resultirc1->fetch_assoc()) {
  $resultirc = $row['nick'];
}
$stmtirc->close();
if (!isset($resultirc) || $resultirc == NULL) {
  $resultirc = "Null3";
}

//Case History
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli5 = new mysqli($db['server'], $db['user'], $db['pass'], 'records', $db['port']);
$stmt5 = $mysqli5->prepare("SELECT DISTINCT c.*, ca.dispatch
  FROM cases AS c
  INNER JOIN case_assigned AS ca ON ca.case_ID = c.case_ID
  INNER JOIN sealsudb.staff AS ss ON seal_ID = ca.seal_kf_id
  WHERE seal_id = ?");
$stmt5->bind_param("i", $beingManaged);
$stmt5->execute();
$result5 = $stmt5->get_result();
$stmt5->close();

//Known Aliases
$mysqliAlias = new mysqli($db['server'], $db['user'], $db['pass'], 'sealsudb', $db['port']);
$stmtAlias = $mysqliAlias->prepare("SELECT seal_name, platform_name FROM staff
JOIN lookups.platform_lu AS s ON s.platform_id=platform
WHERE seal_id = ?");
$stmtAlias->bind_param("i", $beingManaged);
$stmtAlias->execute();
$resultAlias = $stmtAlias->get_result();
$stmtAlias->close();

//Perm Mod SQL
$mysqli2 = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);
$data = [];

//Add Perm
if (isset($_GET['add'])) {
  foreach ($_REQUEST as $key => $value) {
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
  }
  $stmt3 = $mysqli2->prepare('CALL spAddPerm(?,?,?)');
  $stmt3->bind_param('iii', $beingManaged, $user->data()->id, $data['perm']);
  $stmt3->execute();
  $stmt3->close();
  usSuccess("Added Qualification Successfully.");
  header("Location: manage-trainer.php?cne=$beingManaged");
  die();
}

//Rem Perm
if (isset($_GET['rem'])) {
  foreach ($_REQUEST as $key => $value) {
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
  }
  $stmt4 = $mysqli2->prepare('CALL spRemPerm(?,?,?)');
  $stmt4->bind_param('iii', $beingManaged, $user->data()->id, $data['perm']);
  $stmt4->execute();
  $stmt4->close();
  usSuccess("Removed Qualification Successfully.");
  header("Location: manage-trainer.php?cne=$beingManaged");
  die();
}

//Promote
if (isset($_GET['promote'])) {
  foreach ($_REQUEST as $key => $value) {
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
  }
  $stmt3 = $mysqli2->prepare('CALL spAddPerm(?,?,?)');
  $stmt3->bind_param('iii', $beingManaged, $user->data()->id, $thenumber2);
  $stmt3->execute();
  $stmt3->close();

  $stmt4 = $mysqli2->prepare('CALL spRemPerm(?,?,?)');
  $stmt4->bind_param('iii', $beingManaged, $user->data()->id, $thenumber1);
  $stmt4->execute();
  $stmt4->close();
  $data = [
    "rank" => "seal",
    "subject" => $resultirc,
  ];
  $postdata = json_encode($data);
  $hmacdata = preg_replace("/\s+/", "", $postdata);
  $auth = hash_hmac('sha256', $hmacdata, $hmackey);
  $keyCheck = hash_hmac('sha256', $constant, $hmackey);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'hmac: ' . $auth,
    'keyCheck: ' . $keyCheck
  ));
  $result = curl_exec($ch);
  curl_close($ch);
  usSuccess("Promoted " . echousername($beingManaged) . " to Seal.");
  header("Location: manage-trainer.php?cne=$beingManaged");
  die();
}

//Demote
if (isset($_GET['demote'])) {
  foreach ($_REQUEST as $key => $value) {
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
  }
  $stmt3 = $mysqli2->prepare('CALL spAddPerm(?,?,?)');
  $stmt3->bind_param('iii', $beingManaged, $user->data()->id, $thenumber1);
  $stmt3->execute();
  $stmt3->close();

  $stmt4 = $mysqli2->prepare('CALL spRemPerm(?,?,?)');
  $stmt4->bind_param('iii', $beingManaged, $user->data()->id, $thenumber2);
  $stmt4->execute();
  $stmt4->close();
  $data = [
    "rank" => "pup",
    "subject" => $resultirc,
  ];
  $postdata = json_encode($data);
  $hmacdata = preg_replace("/\s+/", "", $postdata);
  $auth = hash_hmac('sha256', $hmacdata, $hmackey);
  $keyCheck = hash_hmac('sha256', $constant, $hmackey);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'hmac: ' . $auth,
    'keyCheck: ' . $keyCheck
  ));
  $result = curl_exec($ch);
  curl_close($ch);
  usSuccess("Demoted " . echousername($beingManaged) . " to Pup.");
  header("Location: manage-trainer.php?cne=$beingManaged");
  die();
}
?>
<h2>Welcome, <?= echousername($user->data()->id); ?>.</h2>
<p>You are managing user: <em><?= echousername($beingManaged); ?></em> <a href="manage.php" class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
<br>
<h3>Registered CMDRs</h3>
<br>
<div class="table-responsive-md">
  <table class="table table-hover table-dark table-bordered table-striped" id="remPerms">
    <thead>
      <tr>
        <th>CMDR Name</th>
        <th>CMDR Platform</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($resultAlias->num_rows === 0) { ?>
        <tr>
          <td>No CMDRs.</td>
          <td>Remind them to Register!</td>
        </tr>
      <?php } else {
        while ($rowAlias = $resultAlias->fetch_assoc()) {
          echo '<tr><td>' . $rowAlias["seal_name"] . '</td>
            <td>' . $rowAlias["platform_name"] . '</td></tr>';
        }
      }
      $resultAlias->free();
      ?>
    </tbody>
  </table>
</div>
<br>
<h3>Permission Management</h3>
<br>
<?php
if (hasPerm([1], $beingManaged)) { ?>
  <div class="table-responsive-md">
    <table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover" id="pup">
      <thead>
        <tr>
          <th>
            <font face="Arial">Core Level</font>
          </th>
          <th>
            <font face="Arial">Modify To</font>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Pup</td>
          <td>
            <form method="post" action="?promote&cne=<?= $beingManaged; ?>">
              <button type="submit" class="btn btn-secondary" id="promote" name="promote">Seal</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
<?php } elseif (hasperm([2], $beingManaged)) { ?>
  <div class="table-responsive-md">
    <table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover" id="seal">
      <thead>
        <tr>
          <th>
            <font face="Arial">Core Level</font>
          </th>
          <th>
            <font face="Arial">Modify To</font>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Seal</td>
          <td>
            <form method="post" action="?demote&cne=<?= $beingManaged; ?>">
              <button type="submit" class="btn btn-secondary" id="promote" name="promote">Pup</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
<?php  } ?>
<div class="table-responsive-md">
  <table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover" id="secondaries">
    <thead>
      <tr>
        <th>
          <font face="Arial">Optional Qualifications</font>
        </th>
        <th>
          <font face="Arial">Action</font>
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>KingFisher</td>
        <td>
          <form method="post" action="?
          <?php if (hasPerm([3], $beingManaged)) {
            echo "rem";
          } else {
            echo "add";
          } ?>&cne=<?= $beingManaged; ?>">
            <input type="hidden" name="perm" value="3">
            <button type="submit" class="btn btn-secondary" id="kingfishersub" name="kingfishersub">
              <?php if (hasPerm([3], $beingManaged)) {
                echo "Remove";
              } else {
                echo "Add";
              } ?></button>
          </form>
        </td>
      </tr>
      <tr>
        <td>Dispatcher</td>
        <td>
          <form method="post" action="?
          <?php if (hasPerm([6], $beingManaged)) {
            echo "rem";
          } else {
            echo "add";
          } ?>&cne=<?= $beingManaged; ?>">
            <input type="hidden" name="perm" value="6">
            <button type="submit" class="btn btn-secondary" id="dispatchsub" name="dispatchsub">
              <?php if (hasPerm([6], $beingManaged)) {
                echo "Remove";
              } else {
                echo "Add";
              } ?></button>
          </form>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<br>
<h3>Cases Assigned</h3>
<br>
<div class="table-responsive-md">
  <table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover" id="LookupList">
    <thead>
      <tr>
        <th>
          <font face="Arial">Case ID</font>
        </th>
        <th>
          <font face="Arial">Case Date</font>
        </th>
        <th>
          <font face="Arial">CMDR Type?</font>
        </th>
      </tr>
    </thead>
    <?php
    if ($result5->num_rows === 0) { ?>
      <tr>
        <td>No Rescues</td>
        <td>No Rescues</td>
        <td>No Rescues</td>
      </tr>
    <?php } else {
      while ($row5 = $result5->fetch_assoc()) {
        echo '<tr><td>' . $row5["case_ID"] . '</td>
                <td>' . $row5["case_created"] . '</td>
                <td>';
        if ($row5["dispatch"] == "1") {
          echo 'Dispatcher';
        } elseif ($row5["dispatch"] == "0") {
          echo 'Seal';
        }
        echo '</td></tr>';
      }
    }
    $result5->free();
    ?>
  </table>
</div>
<br>
<p><a href="manage.php" class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>