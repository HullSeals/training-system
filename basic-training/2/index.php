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

if (isset($_GET['send']))
{
  $_SESSION['whatsaseal'] = "true";
  header("Location: quiz.php");
}
require '../../../assets/includes/ipinfo.php';

$moduleID=2;
require '../../assets/progressChecker.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../../assets/includes/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Training: What is a Seal? | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>What is a Seal?</h1>
        <video width="100%" controls id="video">
          <source src="../../assets/videos/Soothing 30 Second of Ocean Waves.mp4" type="video/mp4">
            Your Browser does not support this video. Please contact the Cybers.
          </source>
        </video>
        <br><br>
        <p id="notice">Please wait for the video to complete.</p>
        <form method="post" action="?send">
            <div class="input-group">
              <div class="btn-group" role="group">
                <a href=".." class="btn btn-danger btn-lg" id="back_btn" name="back_btn">Go Back</a>
                <button type="submit" class="btn btn-secondary btn-lg" id="btn" name="next_btn" disabled="disabled">Next</button>
              </div>
            </div>
        </form>
        <br />
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../../assets/includes/footer.php'; ?>
</body>
</html>
<?php require_once '../../assets/videoScripts.php'; ?>
