<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link href="https://hullseals.space/favicon.ico" rel="icon" type="image/x-icon">
  <link href="https://hullseals.space/favicon.ico" rel="shortcut icon" type="image/x-icon">
  <meta charset="UTF-8">
  <meta content="David Sangrey" name="author">
  <meta content="hull seals, elite dangerous, distant worlds, seal team fix, mechanics, dw2" name="keywords">
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0" name="viewport">
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Trainer Management Dashboard | The Hull Seals</title>

    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha384-/LjQZzcpTzaYn7qWqRIWYC5l8FWEZ2bIHIz0D73Uzba4pShEcdLdZyZkI4Kv676E" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="datatables.min.css"/>
    <script type="text/javascript" src="datatables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://hullseals.space/trainings/assets/trainerCentercss.css" />
    <script>
    $(document).ready(function() {
    $('#PupList').DataTable();
} );</script>
</head>
<body>
    <div id="home">
      <?php include '../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h2>Welcome, <?php echo echousername($user->data()->id); ?>.</h2>
        <br>
        <table class="table table-hover table-dark table-responsive-md table-bordered table-striped" id="PupList">
          <thead>
          <tr>
              <th>Username</th>
              <th>Primary Name</th>
              <th>Modules Completed</th>
              <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $stmt = $mysqli->prepare("SELECT username, seal_name, SUM(CASE WHEN progress = 3 THEN 1 ELSE 0 END) AS completed
          FROM auth.users AS u
          JOIN sealsudb.staff AS s ON s.seal_ID = u.ID
          JOIN training.module_progression AS m ON m.seal_ID = u.ID
          WHERE s.primary = TRUE
          GROUP BY u.ID;");
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) {
            $field1name = $row["username"];
            $field2name = $row["seal_name"];
            $field3name = $row["completed"];
            echo '<tr>
              <td>'.$field2name.'</td>
              <td>'.$field1name.'</td>
              <td>'.$field3name.'</td>
              <td><a href="manage-trainee.php?cne='.$field1name.'" class="btn btn-warning active">Manage CMDR</a></td>
            </tr>';
          }
          $result->free();
          ?>
        </tbody>
        </table>
        <br>
        <p><a href=".." class="btn btn-small btn-danger" style="float: right;">Go To Training Home</a></p>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../assets/footer.php'; ?>
</body>
</html>
