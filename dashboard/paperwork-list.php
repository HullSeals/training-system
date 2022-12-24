<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "David Sangrey";
$pgContent = "Drill Paperwork Review";
$useIP = 0; //1 if Yes, 0 if No.

$preContent = '<link rel="stylesheet" type="text/css" href="../assets/datatables.min.css"/>
<script type="text/javascript" src="../assets/datatables.min.js"></script>';

$customContent = '<script>
$(document).ready(function() {
      $(\'#PaperworkList\').DataTable({
        "order": [
          [0, \'desc\' ]]
          });
      });
</script>';

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training_records', $db['port']);
?>
<h2>Welcome, <?= echousername($user->data()->id); ?>.</h2>
<p><a href="." class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
<br>
<br>
<table class="table table-hover table-dark table-responsive-md table-bordered table-striped" id="PaperworkList">
  <thead>
    <tr>
      <th>Case ID</th>
      <th>Drill Client</th>
      <th>System</th>
      <th>Platform</th>
      <th>Date</th>
      <th>Options</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $stmt = $mysqli->prepare("WITH sealsCTI
AS
(
    SELECT MIN(ID), seal_ID, seal_name
    FROM sealsudb.staff' . $field
    GROUP BY seal_ID
)
SELECT c.case_ID, seal_name, client_nm, current_sys, platform_name, case_created
FROM sealsCTI AS cti
    JOIN case_assigned AS ca ON ca.seal_kf_id = cti.seal_ID
    JOIN cases AS c ON c.case_ID = ca.case_ID
    JOIN case_seal AS cs ON cs.case_ID = c.case_ID
    JOIN lookups.platform_lu AS plu ON plu.platform_id = c.platform
WHERE ca.dispatch IS FALSE AND ca.support IS FALSE");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $field1name = $row["case_ID"];
      $field2name = $row["client_nm"];
      $field3name = $row["current_sys"];
      $field4name = $row["platform_name"];
      $field5name = $row["case_created"]; ?>
      <tr>
        <td><?= $field1name ?></td>
        <td><?= $field2name ?></td>
        <td><?= $field3name ?></td>
        <td><?= $field4name ?></td>
        <td><?= $field5name ?></td>
        <td><a href="paperwork-review.php?cne=<?= $field1name ?>" class="btn btn-warning active">Review Case</a></td>
      </tr>
    <?php }
    $result->free();
    ?>
  </tbody>
</table>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>