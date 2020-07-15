<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}
if (!isset($_GET['cne'])) {
  Redirect::to('index.php');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../assets/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Manage Trainee | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h2>Welcome, <?php echo echousername($user->data()->id); ?>.</h2>
        <p>You are managing user: <em><?php echo $_GET['cne'];?></em></p>
        <br>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../assets/footer.php'; ?>
</body>
</html>
