<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

//DB Connection Info
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../assets/includes/headerCenter.php'; ?>
    <title>Lesson Scheduling | The Hull Seals</title>
    <meta content="Lesson Scheduling System" name="description">
</head>
<body>
    <div id="home">
      <?php include '../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Edit Availability</h1>
        <p>Please update your availability below.</p>
        <br>
        <p><a href=".." class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
</body>
</html>
