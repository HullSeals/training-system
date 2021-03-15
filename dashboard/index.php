<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../assets/includes/headerCenter.php'; ?>
    <title>Trainer Dashboard | The Hull Seals</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
</head>
<body>
    <div id="home">
      <?php include '../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Welcome, Trainer</h1>
        <p>Please choose your option.</p>
        <br>
        <ul class="list-group list-group-horizontal-sm">
        <a href="manage.php" class="list-group-item list-group-item-action">Manage Seals</a>
        <a href="paperwork-list.php" class="list-group-item list-group-item-action">Drill Paperwork Review</a>
        <a href="../training-paperwork" class="list-group-item list-group-item-action">Drill Paperwork</a>
        <a href="../scheduling/requests.php" class="list-group-item list-group-item-action">Drill Management Dashboard</a>
        <a href="../scheduling/index.php" class="list-group-item list-group-item-action">Drill Signup Form</a>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
</body>
</html>
