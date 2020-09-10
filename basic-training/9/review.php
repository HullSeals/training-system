<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

if(!isset($_SESSION))
  {
    session_start();
  }
if($_SESSION['conclusion'] != "true"){
   //send them back
   header("Location: .");
}
$moduleID=9;
require '../../../assets/includes/ipinfo.php';
require '../../assets/nextSetter.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../../assets/includes/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Conclusion: Basic Training | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h2>Congrats, CMDR!</h2>
        <p>Congrats on completing this section of your Seal Training. <br><br> This course represents one half of your training to become qualified as one of Elite's most dedicated responders: The Seals.<br><br>
          After completing this course, you'll need to undertake a drill, ran by our trainers, in order to make sure that you have all the skills and knowledge required in order to best serve our clients.<br><br>
          In order to progress, you'll need to sign up for a drill and follow the instructions in the video you just watched. Here are some of the links you'll need. It's strongly advised you record these for later.
        </p>
        <div class="list-group" style="max-width: 60%; text-align:center;">
          <a href="https://hullseals.space/knowledge/books/how-to-join/page/how-to-join-the-hull-seals" target="_blank" class="list-group-item list-group-item-action list-group-item-dark">"How to Join the Seals" Wiki Page</a>
          <a href="https://hullseals.space/SuperSecretInviteLink" target="_blank" class="list-group-item list-group-item-action list-group-item-dark">Our Discord</a>
          <a href="http://hullse.al/tSignUp" target="_blank" class="list-group-item list-group-item-action list-group-item-dark">Drill Signup Form</a>
          <a href="https://hullse.al/SOP" target="_blank" class="list-group-item list-group-item-action list-group-item-dark">Seal SOP</a>
          <a href="certificate" target="_blank" class="list-group-item list-group-item-action list-group-item-primary">Print your Completion Certificate!</a>
        </div>
        <br>
        <p><a href=".." class="btn btn-small btn-danger" style="float: right;">Done? Go back to Basic Training Home</a></p>
        <br>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../../assets/includes/footer.php'; ?>
  </body>
</html>
