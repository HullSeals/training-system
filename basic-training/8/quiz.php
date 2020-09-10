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
if($_SESSION['3pt'] != "true"){
   //send them back
   header("Location: .");
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../../assets/includes/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Quiz: Third Party Tools | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Quiz: Third Party Tools</h1>
        <br />
        <form action="results.php" method = "post">
          <div class="input-group mb-3">
            <p>1. What is the most common Seal use for EDSM?<br />
              <input type="radio" name="EDSM" id="EDSM" required="" value="A">Drebin<br>
              <input type="radio" name="EDSM" id="EDSM" required="" value="B">Rixxan<br>
              <input type="radio" name="EDSM" id="EDSM" required="" value="C">Akastus<br>
              <input type="radio" name="EDSM" id="EDSM" required="" value="D">MiddleNate<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>2. For optimal Spansh routing, what percentage should your efficiency be set to?<br />
              <input type="radio" name="Spansh" required="" id="Spansh" value="A"> 100%<br>
              <input type="radio" name="Spansh" required="" id="Spansh" value="B"> 80%<br>
              <input type="radio" name="Spansh" required="" id="Spansh" value="C"> 60%<br>
              <input type="radio" name="Spansh" required="" id="Spansh" value="D"> 20%<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>3. How is EDDB commonly used by Seals?<br />
              <input type="radio" name="EDDB" required="" id="EDDB" value="A"> Tracking Expeditions across the galaxy.<br>
              <input type="radio" name="EDDB" required="" id="EDDB" value="B"> Finding nearby stations for Repairs.<br>
              <input type="radio" name="EDDB" required="" id="EDDB" value="C"> Mapping Seal rescues.<br>
              <input type="radio" name="EDDB" required="" id="EDDB" value="D"> Maintaining an active star map of the known galaxy.<br>
            </p>
          </div>
          <button class="btn btn-primary" type="submit">Submit</button>
        </form>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../../assets/includes/footer.php'; ?>
</body>
</html>
