<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}
if (!isset($_GET['cne'])) {
  Redirect::to('index.php');
}
$beingManaged = $_GET['cne'];
$beingManaged = intval($beingManaged);

$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training', $db['port']);

$stmt2 = $mysqli->prepare('SELECT sch_ID, seal_ID FROM training.schedule_requests WHERE sch_ID = ?');
$stmt2->bind_param('i', $beingManaged);
$stmt2->execute();
$result2 = $stmt2->get_result();
while ($row2 = $result2->fetch_assoc()) {
$stmt2->close();

$seal_ID = $row2['seal_ID'];
$sch_ID = $row2['sch_ID'];
}
if ($user->data()->id == $seal_ID) {
  $stmt = $mysqli->prepare('call spTrainConfirm(?)');
  $stmt->bind_param('i', $sch_ID);
  $stmt->execute();
  $stmt->close();
  require_once 'trainerEmail.php';
}
else {
  header("Location: https://hullseals.space/error-pages/401.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../assets/includes/headerCenter.php'; ?>
    <title>Lesson Confirmed | The Hull Seals</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
</head>
<body>
    <div id="home">
      <?php include '../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Lesson Confirmed.</h1>
        <p>Thank you, CMDR. We look forward to seeing you soon!</p>
        <p>You may now close the tab.</p>
          </li>
        </ul>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
</body>
</html>
