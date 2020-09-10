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
        <h3>Trainer Resources</h3>
      <div class="btn-group" style="display:flex;" role="group">
        <a href="manage.php" class="btn btn-lg btn-primary" style="max-width: 33%;">Manage Seals</a>
        <button type="button" class="btn btn-secondary disabled" style="max-width: 33%;">          </button>
        <a href="paperwork-list.php" class="btn btn-lg btn-primary" style="max-width: 33%;">Paperwork Review</a>
      </div>
      <br>
      <br>
      <h3>Links for Pups</h3>
      <div class="btn-group" style="display:flex;" role="group">
        <a href="../basic-training" class="btn btn-lg btn-primary" style="max-width: 33%;">Basic Training</a>
        <button type="button" class="btn btn-secondary disabled" style="max-width: 33%;">          </button>
        <a href="../training-paperwork" class="btn btn-lg btn-primary" style="max-width: 33%;">Drill Paperwork</a>
      </div>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
</body>
</html>
