<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

session_start();
if($_SESSION['terms'] != "true"){
   //send them back
   header("Location: .");
}
$answer1 = $_POST['faveadmin'];
$answer2 = $_POST['quest'];
$answer3 = $_POST['capital'];
$totalCorrect = 0;
    if ($answer1 == "B") { $totalCorrect++; }
    if ($answer2 == "D") { $totalCorrect++; }
    if ($answer3 == "A") { $totalCorrect++; }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../../assets/header.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Training: NameOfVideoHere | The Hull Seals</title>
</head>
<body>
    <div id="home">
        <header>
            <nav class="navbar container navbar-expand-lg navbar-expand-md navbar-dark" role="navigation">
                <a class="navbar-brand" href="#"><img src="../../../images/emblem_scaled.png" height="30" class="d-inline-block align-top" alt="Logo"> Hull Seals</a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/https://hullseals.space">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/knowledge">Knowledge Base</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/journal">Journal Reader</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/https://hullseals.space/users/">Login/Register</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <section class="introduction">
	    <article id="intro3">
<?php
if ($totalCorrect == 3) {
  $_SESSION['terms'] = "false";
  echo '<h2>Congrats! You did it!</h2><br><p>Ready to go back to the <a href=".." class="btn btn-primary">menu</a>?</p>';
}
elseif ($totalCorrect < 3) {
    echo '<h2>Not Quite...</h2><br><p>Want to <a class="btn btn-primary" href=".">Rewatch the Video</a>, or go back and <a href="quiz.php" class="btn btn-primary">try the quiz again</a>?</p>';
}
else {
  die("Hacking Attempt...");
} ?>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/footer.php'; ?>
</body>
</html>
