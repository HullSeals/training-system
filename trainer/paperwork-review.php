<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training_records', $db['port']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
 <title>Drill Paperwork Review | The Hull Seals</title>
 <meta content="David Sangrey" name="author">
 <?php include '../assets/headerCenter.php'; ?>
</head>
<body>
    <div id="home">
      <?php include '../assets/menuCode.php';?>
      <section class="introduction container">
    <article id="intro3">
    </article>
    <div class="clearfix"></div>
</section>
</div>
<?php include '../assets/footer.php'; ?>
</body>
</html>
