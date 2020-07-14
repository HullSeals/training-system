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
require '../../assets/ipinfo.php';

$moduleID=2;
require '../../assets/progressChecker.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../assets/header.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Training: What is a Seal? | The Hull Seals</title>
</head>
<body>
    <div id="home">
        <header>
            <nav class="navbar container navbar-expand-lg navbar-expand-md navbar-dark" role="navigation">
                <a class="navbar-brand" href="#"><img src="../../../../images/emblem_scaled.png" height="30" class="d-inline-block align-top" alt="Logo"> Hull Seals</a>

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
        <h1>Lorem Ipsum</h1>
        <p>The Fitnessgram Pacer Test is...</p>
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
        <br />
        <a href=".." class="btn btn-danger btn-block" id="back_btn" name="back_btn">Go Back</a>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/footer.php'; ?>
</body>
</html>
<script>
var timeTracking = {
	watchedTime: 0,
	currentTime: 0
};
var lastUpdated = 'currentTime';
video.addEventListener('timeupdate', function() {
	if (!video.seeking) {
		if (video.currentTime > timeTracking.watchedTime) {
			timeTracking.watchedTime = video.currentTime;
			lastUpdated = 'watchedTime';
		}
		//tracking time updated  after user rewinds
		else {
			timeTracking.currentTime = video.currentTime;
			lastUpdated = 'currentTime';
		}
	}
});
// prevent user from seeking
video.addEventListener('seeking', function() {
	// guard against infinite recursion:
	// user seeks, seeking is fired, currentTime is modified, seeking is fired, current time is modified, ....
	var delta = video.currentTime - timeTracking.watchedTime;
	if (delta > 0) {
		video.pause();
		//play back from where the user started seeking after rewind or without rewind
		video.currentTime = timeTracking[lastUpdated];
		video.play();
	}
});
</script>
<script>
var video = document.getElementById("video");
var button = document.getElementById("btn")
var notice = document.getElementById("notice")
video.addEventListener("ended", function() {
   button.disabled = false;
   notice.hidden = true;
}, true);
</script>
