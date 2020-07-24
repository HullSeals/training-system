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
if($_SESSION['faq'] != "true"){
   //send them back
   header("Location: .");
}
$answer1 = $_POST['fuel'];
$answer2 = $_POST['format'];
$answer3 = $_POST['starbright'];
$totalCorrect = 0;
    if ($answer1 == "B") { $totalCorrect++; }
    if ($answer2 == "A") { $totalCorrect++; }
    if ($answer3 == "C") { $totalCorrect++; }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../../assets/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Training: Frequently Asked Questions | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
<?php
if ($totalCorrect == 3) {
  $_SESSION['faq'] = "false";
  $moduleID=5;
  require '../../assets/ipinfo.php';
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
    <?php include '../../assets/footer.php'; ?>
</body>
</html>
