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
  <?php include '../assets/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Trainer Management Dashboard | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h2>Welcome, <?php echo echousername($user->data()->id); ?>.</h2>
        <br>
        <table class="table table-dark table-striped table-bordered table-hover table-responsive-md">
          <tr>
              <td>Seal Username</td>
              <td>Primary CMDR Name</td>
              <td>Modules Completed</td>
              <td>Options</td>
          </tr>
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
