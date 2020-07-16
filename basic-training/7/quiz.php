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
if($_SESSION['oursite'] != "true"){
   //send them back
   header("Location: .");
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../assets/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Quiz: Our Website | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Quiz: Our Website</h1>
        <br />
        <form action="results.php" method = "post">
          <div class="input-group mb-3">
            <p>1. What should you do if a CMDR does not appear on the paperwork form?<br />
              <input type="radio" name="ppwk" id="ppwk" required="" value="A"> Manually enter the name even if it doesn't pop up.<br>
              <input type="radio" name="ppwk" id="ppwk" required="" value="B"> Put the missing name in the Notes.<br>
              <input type="radio" name="ppwk" id="ppwk" required="" value="C"> Put "Other_Civ_CMDRs" in the field.<br>
              <input type="radio" name="ppwk" id="ppwk" required="" value="D"> Both B and C.<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>2. In order to have permissions assigned in IRC, where must a user register an IRC alias?<br />
              <input type="radio" name="ircname" required="" id="ircname" value="A"> The IRC alias sub-form of CMDR Management<br>
              <input type="radio" name="ircname" required="" id="ircname" value="B"> Your username is your registered alias.<br>
              <input type="radio" name="ircname" required="" id="ircname" value="C"> Any CMDR Name registered in the CMDR Management form is used.<br>
              <input type="radio" name="ircname" required="" id="ircname" value="D"> Names must be assigned by the CyberSeals<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>3. Are paperwork cases recorded by registered CMDR or for your whole accounts?<br />
              <input type="radio" name="howstored" required="" id="howstored" value="A"> All data is anonymized - we don't save people's names.<br>
              <input type="radio" name="howstored" required="" id="howstored" value="B"> All cases are recorded under your account, regardless of alias name.<br>
              <input type="radio" name="howstored" required="" id="howstored" value="C"> Cases are recorded under each CMDR name individually.<br>
              <input type="radio" name="howstored" required="" id="howstored" value="D"> It depends on your user preferences.<br>
            </p>
          </div>
          <button class="btn btn-primary" type="submit">Submit</button>
        </form>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/footer.php'; ?>
</body>
</html>
