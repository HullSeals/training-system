<?php
$answer1 = $_POST['faveadmin'];
$answer2 = $_POST['quest'];
$answer3 = $_POST['capital'];
$totalCorrect = 0;
    if ($answer1 == "B") { $totalCorrect++; }
    if ($answer2 == "D") { $totalCorrect++; }
    if ($answer3 == "A") { $totalCorrect++; }
    echo $totalCorrect;
if ($totalCorrect == 3) {
  echo "Congrats! You did it! Ready to move on to the next section, or back to the menu?";
}
elseif ($totalCorrect < 3) {
  echo "not quite... want to go back and try again, or rewatch the video?";
}
elseif ($totalCorrect > 3) {
  echo "how the hell?";
}
?>
