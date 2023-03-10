<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "David Sangrey";
$pgContent = "Drill Paperwork Review";
$useIP = 0; //1 if Yes, 0 if No.

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}
if (!isset($_GET['cne'])) {
  Redirect::to('paperwork-list.php');
}

//Who are we working with?
$beingManaged = $_GET['cne'];
$beingManaged = intval($beingManaged);

//DB Info
$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training_records', $db['port']);

//All Case Info
$stmtCaseInfo = $mysqli->prepare("SELECT client_nm, canopy_breach, current_sys, platform_name,
    hull_stat, status_name, color_name, notes, case_created
FROM cases AS c
    JOIN case_seal AS cs ON cs.case_ID = c.case_ID
    JOIN case_history AS ch ON ch.ch_ID = c.last_ch_id
    JOIN lookups.status_lu AS slu ON slu.status_id = ch.case_stat
    JOIN lookups.platform_lu AS plu ON plu.platform_id = c.platform
    JOIN lookups.case_color_lu AS ccl ON ccl.color_id = ch.code_color
WHERE c.case_ID = ?;");
$stmtCaseInfo->bind_param("i", $beingManaged);
$stmtCaseInfo->execute();
$resultCaseInfo = $stmtCaseInfo->get_result();
$stmtCaseInfo->close();
//$rowCaseInfo = $resultCaseInfo->fetch_assoc();
//All Assigned Seals
$stmtAssigned = $mysqli->prepare("WITH sealsCTI
AS
(
    SELECT MIN(ID), seal_ID, seal_name
    FROM sealsudb.staff
    GROUP BY seal_ID
)
SELECT seal_name, dispatch, support, self_dispatch
FROM case_assigned AS ca
    JOIN sealsCTI AS ss ON ss.seal_ID = ca.seal_kf_id
WHERE case_ID = ?;");
$stmtAssigned->bind_param("i", $beingManaged);
$stmtAssigned->execute();
$resultAssigned = $stmtAssigned->get_result();
$stmtAssigned->close();
//$rowAssigned = $resultAssigned->fetch_assoc();
?>
<h2>Welcome, <?= echousername($user->data()->id); ?>.</h2>
<p>You are Reviewing Drill Paperwork for Case # <?= $beingManaged; ?> <a href="paperwork-list.php" class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
<br>
<h3>Case Info</h3>
<br>
<h5>Basic Information</h5>
<table class="table table-hover table-dark table-responsive-md table-bordered table-striped">
  <thead>
    <tr>
      <th>Client Name</th>
      <th>System</th>
      <th>Platform</th>
      <th>Paperwork Filed</th>
    </tr>
  </thead>
  <tbody>
    <?php
    while ($rowCaseInfo = $resultCaseInfo->fetch_assoc()) {
      echo '<tr>
        <td>' . $rowCaseInfo["client_nm"] . '</td>
        <td>' . $rowCaseInfo["current_sys"] . '</td>
        <td>' . $rowCaseInfo["platform_name"] . '</td>
        <td>' . $rowCaseInfo["case_created"] . '</td>
      </tr>' ?>
  </tbody>
</table>
<br>
<h5>Situation Information</h5>
<table class="table table-hover table-dark table-responsive-md table-bordered table-striped">
  <thead>
    <tr>
      <th>Canopy Status</th>
      <th>Hull Status</th>
      <th>Case Color</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <?php
      if ($rowCaseInfo["canopy_breach"] == 0) {
        echo '<td>Intact</td>';
      } elseif ($rowCaseInfo["canopy_breach"] == 1) {
        echo '<td>Broken</td>';
      } else {
        echo '<td>ERROR!</td>';
      }
      ?>
      <td><?= $rowCaseInfo["hull_stat"] ?></td>
      <td><?= $rowCaseInfo["color_name"] ?></td>
    </tr>
  </tbody>
</table>
<br>
<h5>Case Notes</h5>
<table class="table table-hover table-dark table-responsive-md table-bordered table-striped">
  <thead>
    <tr>
      <th>Notes</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?= $rowCaseInfo["notes"] ?></td>
    </tr>
  <?php }
    $resultCaseInfo->free();
  ?>
  </tbody>
</table>
<br>
<h3>Responder Information</h3>
<table class="table table-hover table-dark table-responsive-md table-bordered table-striped">
  <thead>
    <tr>
      <th>Responder</th>
      <th>Responder Type</th>
      <th>Self-Dispatched?</th>
    </tr>
  </thead>
  <tbody>
    <?php
    while ($rowAssigned = $resultAssigned->fetch_assoc()) {
      $field1name = $rowAssigned["seal_name"];
      $field2name = $rowAssigned["dispatch"];
      $field3name = $rowAssigned["support"];
      $field4name = $rowAssigned["self_dispatch"];
      echo '<tr>
             <td>' . $field1name . '</td>';
      if ($rowAssigned["dispatch"] == 0 && $rowAssigned["support"] == 0) {
        echo '<td>Primary Seal</td>';
      } elseif ($rowAssigned["dispatch"] == 1 && $rowAssigned["support"] == 0) {
        echo '<td>Dispatcher</td>';
      } elseif ($rowAssigned["dispatch"] == 0 && $rowAssigned["support"] == 1) {
        echo '<td>Supporting Seal</td>';
      }
      if ($rowAssigned["self_dispatch"] == 0) {
        echo '<td>No</td>';
      } else {
        echo '<td>Yes</td>';
      }
      echo '</tr>';
    }
    $resultAssigned->free();
    ?>
  </tbody>
</table>
<h4>To modify incorrect information, please contact the CyberSeals.</h4>
<br>
<p><a href="paperwork-list.php" class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
<br>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>