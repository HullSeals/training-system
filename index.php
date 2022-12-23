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
  header("Location: https://hullseals.space/trainings/scheduling/index.php");
}
