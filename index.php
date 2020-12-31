<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

if (hasPerm([4,7,8,9,10],$user->data()->id)){
header("Location: ./dashboard");
die();
}
else {
  header("Location: https://hullseals.space/error-pages/401.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../assets/includes/headerCenter.php'; ?>
    <title>Training Portal | The Hull Seals</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<script>
    $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
</head>
<body>
    <div id="home">
      <?php include '../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Welcome To The Hull Seals Training Portal</h1>
        <p>To continue, please select your course below.</p>
        <ul class="nav nav-pills nav-fill">
          <li class="nav-item">
            <a class="nav-link active" href="basic-training">Basic Seal Training</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"></a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#">Dispatcher Training</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"></a>
          </li>
          <li class="nav-item disabled">
            <a class="nav-link disabled" href="#" >Code Black Training</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"></a>
          </li>
          <li class="nav-item disabled">
            <a class="nav-link disabled" href="#">Kingfisher Training</a>
          </li>
          <?php
          if (hasPerm([4,7,8,9,10],$user->data()->id)){
          echo '<li class="nav-item disabled">
            <a class="nav-link active" href="dashboard">Trainer Dashboard</a>
          </li>';

          }
          ?>
        </ul>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../assets/includes/footer.php'; ?>
</body>
</html>
