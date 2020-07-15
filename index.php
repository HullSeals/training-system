<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'assets/headerCenter.php'; ?>
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
      <?php include 'assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Welcome to the Hull Seals training portal.</h1>
        <p>To continue, please select your course below.</p>
        <div class="btn-group" role="group">
        <a href="basic-training" class="btn btn-lg btn-primary">Basic Seal Training</a>
        <button type="button" class="btn btn-secondary btn-lg" data-toggle="tooltip" data-placement="top" title="Coming Soon!">Dispatcher Training</button>
        <button type="button" class="btn btn-secondary btn-lg" data-toggle="tooltip" data-placement="top" title="Coming Soon!">Code Black Training</button>
        <button type="button" class="btn btn-secondary btn-lg" data-toggle="tooltip" data-placement="top" title="Coming Soon!">Kingfisher Training</button>
        <?php
        if (hasPerm([4,7,8,9,10],$user->data()->id)){
        echo '<button type="button" class="btn btn-info btn-lg" data-toggle="tooltip" data-placement="top" title="Coming Soon!">Trainer Dashboard</button>';
        }
        ?>
      </div>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include 'assets/footer.php'; ?>
</body>
</html>
