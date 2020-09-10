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
if($_SESSION['oursite'] != "true"){
   //send them back
   header("Location: .");
}
$answer1 = $_POST['ppwk'];
$answer2 = $_POST['ircname'];
$answer3 = $_POST['howstored'];
$totalCorrect = 0;
    if ($answer1 == "D") { $totalCorrect++; }
    if ($answer2 == "A") { $totalCorrect++; }
    if ($answer3 == "B") { $totalCorrect++; }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../../../assets/includes/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Training: Our Website | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
<?php
if ($totalCorrect == 3) {
  $_SESSION['oursite'] = "false";
  $moduleID=7;
  require '../../../assets/includes/ipinfo.php';
  require '../../assets/nextSetter.php';
  echo '<h2>Congrats! You did it!</h2><br><p>Ready to go back to the <a href=".." class="btn btn-primary">menu</a>?</p>';
}
elseif ($totalCorrect < 3) {
    echo '<h2>Not Quite...</h2><br><p>Want to <a class="btn btn-primary" href=".">Rewatch the Video</a>, or go back and <a href="quiz.php" class="btn btn-primary">try the quiz again</a>?</p>';
}
else {
  die("Hacking Attempt...");
} ?>
<p><a href=".." class="btn btn-small btn-danger" style="float: right;">Back to Menu</a></p>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../../assets/includes/footer.php'; ?>
</body>
</html>
