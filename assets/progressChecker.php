<?php
if (!isset ($moduleID)) {
  die('Hacking Attempt...');
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);
$checkProg = $mysqli->prepare('SELECT progress FROM module_progression WHERE module_ID = ? AND seal_ID = ?;');
$checkProg->bind_param('ii', $moduleID, $user->data()->id);
$checkProg->execute();
$progRes = $checkProg->get_result();
while ($extractarray = $progRes->fetch_assoc()) {
  $progressionStatus=$extractarray['progress'];
}
$updatedValue = 2;
$checkProg->close();
if ($progressionStatus==1) {
  $stmt = $mysqli->prepare('CALL spProgressUpdate(?,?,?,?)');
  $stmt->bind_param("iiis", $user->data()->id, $moduleID, $updatedValue, $lgd_ip);
  $stmt->execute();
  $stmt->close();
}
?>
