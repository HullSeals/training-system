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
if($_SESSION['breakdown'] != "true"){
   //send them back
   header("Location: .");
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../../assets/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Quiz: Case Breakdown | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Quiz: Case Breakdown</h1>
        <br />
        <form action="results.php" method = "post">
          <div class="input-group mb-3">
            <p>1. When should you call for jumps?<br />
              <input type="radio" name="whencall" id="whencall" required="" value="A"> After the Dispatcher has issued a general call.<br>
              <input type="radio" name="whencall" id="whencall" required="" value="B"> As soon as the system is declared.<br>
              <input type="radio" name="whencall" id="whencall" required="" value="C"> After all of clientinfo has been verified.<br>
              <input type="radio" name="whencall" id="whencall" required="" value="D"> Don't - Dispatch will assign seals they know are close-by.<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>2. What should you do if you are unable to go on a call?<br />
              <input type="radio" name="nocando" required="" id="nocando" value="A"> Report that you have seen the case but can't go in the general chat channel.<br>
              <input type="radio" name="nocando" required="" id="nocando" value="B"> Report to Dispatch via private message.<br>
              <input type="radio" name="nocando" required="" id="nocando" value="C"> Report to Dispatch you are unable to go.<br>
              <input type="radio" name="nocando" required="" id="nocando" value="D"> Don't put any message in the channels.<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>3. What color is a case with 25% hull?<br />
              <input type="radio" name="color" required="" id="color" value="A"> Code Black<br>
              <input type="radio" name="color" required="" id="color" value="B"> Code Green<br>
              <input type="radio" name="color" required="" id="color" value="C"> Code Amber<br>
              <input type="radio" name="color" required="" id="color" value="D"> Code Red<br>
              <input type="radio" name="color" required="" id="color" value="E"> Code Blue<br>
            </p>
          </div>
          <button class="btn btn-primary" type="submit">Submit</button>
        </form>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <footer class="page-footer font-small">
        <div class="container">
            <div class="row">
                <div class="col-md-9 mt-md-0 mt-3">
                    <h5 class="text-uppercase">Hull Seals</h5>
                    <p><em>The Hull Seals</em> were established in January of 3305, and have begun plans to roll out galaxy-wide!</p>
					<a href="https://fuelrats.com/i-need-fuel" class="btn btn-sm btn-secondary">Need Fuel? Call the Rats!</a>
                </div>
                <hr class="clearfix w-100 d-md-none pb-3">
                <div class="col-md-3 mb-md-0 mb-3">
                    <h5 class="text-uppercase">Links</h5>

                    <ul class="list-unstyled">
                        <li><a href="https://twitter.com/HullSeals" target="_blank"><img alt="Twitter" height="20" src="../../../images/twitter_loss.png" width="20"></a> <a href="https://reddit.com/r/HullSeals" target="_blank"><img alt="Reddit" height="20" src="../../../images/reddit.png" width="20"></a> <a href="https://www.youtube.com/channel/UCwKysCkGU_C6V8F2inD8wGQ" target="_blank"><img alt="Youtube" height="20" src="../../../images/youtube.png" width="20"></a> <a href="https://www.twitch.tv/hullseals" target="_blank"><img alt="Twitch" height="20" src="../../../images/twitch.png" width="20"></a> <a href="https://gitlab.com/hull-seals-cyberseals" target="_blank"><img alt="GitLab" height="20" src="../../../images/gitlab.png" width="20"></a></li>
						<li><a href="/donate">Donate</a></li>
                        <li><a href="https://hullseals.space/knowledge/books/important-information/page/privacy-policy">Privacy & Cookies Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            Site content copyright © 2019, The Hull Seals. All Rights Reserved. Elite Dangerous and all related marks are trademarks of Frontier Developments Inc.
        </div>
    </footer>
</body>
</html>
