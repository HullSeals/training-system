<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "David Sangrey";
$pgContent = "Manage Trainee";
$useIP = 0; //1 if Yes, 0 if No.

$preContent = '<link rel="stylesheet" type="text/css" href="../assets/datatables.min.css"/>
    <script type="text/javascript" src="../assets/datatables.min.js"></script>';

$customContent = '<script>
    $(document).ready(function() {
    $(\'#PupList\').DataTable();
} );</script>';

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);
?>
<h2>Welcome, <?= echousername($user->data()->id); ?>.</h2>
<p><a href="." class="btn btn-small btn-danger" style="float: right;">Back to Dashboard</a></p>
<br>
<br>
<table class="table table-hover table-dark table-responsive-md table-bordered table-striped" id="PupList">
  <thead>
    <tr>
      <th>Primary Name</th>
      <th>Options</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $stmt = $mysqli->prepare("SELECT s.seal_ID AS ID, seal_name
          FROM sealsudb.staff AS s
          GROUP BY s.seal_ID;");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $field2name = $row["seal_name"];
      $field4name = $row["ID"]; ?>
      <tr>
        <td><?= $field2name ?></td>
        <td><a href="manage-trainee.php?cne=<?= $field4name ?>" class="btn btn-warning active">Manage CMDR</a></td>
      </tr>
    <?php }
    $result->free();
    ?>
  </tbody>
</table>
<br>
<h4>To manage a Staff Member, please contact the CyberSeals.</h4>
<br>
<a href="." class="btn btn-small btn-danger" style="float: right;">Back to Dashboard</a></p>
<br>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>