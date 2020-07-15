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
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);

$stmt2 = $mysqli->prepare("SELECT count(module_ID) AS status FROM module_progression WHERE progress = 3 AND seal_ID = ?;");
$stmt2->bind_param("i", $user->data()->id);
$stmt2->execute();
$result2 = $stmt2->get_result();
while ($extractarray = $result2->fetch_assoc()) {
  $notArray2=$extractarray['status'];
}
$stmt2->close();
if ($notArray2<8) {
  header("Location: ..");
}
elseif ($notArray2>=8) {
  require '../../assets/ipinfo.php';

  $moduleID=9;
  require '../../assets/progressChecker.php';
}
if (isset($_GET['send']))
{
  require '../../assets/nextSetter.php';
  header("Location: ..");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../assets/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Training: Conclusion | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Lorem Ipsum</h1>
        
        <video width="100%" controls id="video">
          <source src="../../assets/videos/Soothing 30 Second of Ocean Waves.mp4" type="video/mp4">
            Your Browser does not support this video. Please contact the Cybers.
          </source>
        </video>
        <br><br>
        <form method="post" action="?send">
            <div class="input-group">
              <p id="notice">Please wait for the video to complete.</p>
                <button type="submit" class="btn btn-secondary btn-block" id="btn" name="next_btn" disabled="disabled">Next</button>
            </div>
        </form>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/footer.php'; ?>
</body>
</html>
<?php require_once '../../assets/videoScripts.php'; ?>
