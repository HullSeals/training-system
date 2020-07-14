<?php
if (!isset ($moduleID)) {
  die('Hacking Attempt...');
}
$updatedValue = 3;
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);
$stmt2 = $mysqli->prepare('CALL spProgressUpdate(?,?,?,?)');
$stmt2->bind_param("iiis", $user->data()->id, $moduleID, $updatedValue, $lgd_ip);
$stmt2->execute();
$stmt2->close();
?>
