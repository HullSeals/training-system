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
if($_SESSION['equipment'] != "true"){
   //send them back
   header("Location: .");
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../../assets/includes/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
</head>
<body>
    <div id="home">
      <?php include '../../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Quiz: Equipment</h1>
        <br />
        <form action="results.php" method = "post">
          <div class="input-group mb-3">
            <p>1. What rating of modules (A-E) is preferred for Repair Limpet Controllers?<br />
              <input type="radio" name="rating" id="rating" required="" value="A"> A-Rated<br>
              <input type="radio" name="rating" id="rating" required="" value="B"> B-Rated<br>
              <input type="radio" name="rating" id="rating" required="" value="C"> C-Rated<br>
              <input type="radio" name="rating" id="rating" required="" value="D"> D-Rated<br>
              <input type="radio" name="rating" id="rating" required="" value="E"> E-Rated<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>2. What is the minimum tons of cargo capacity to have?<br />
              <input type="radio" name="minCargo" required="" id="minCargo" value="A"> At Least 4 Tons<br>
              <input type="radio" name="minCargo" required="" id="minCargo" value="B"> Minimum 8 Tons<br>
              <input type="radio" name="minCargo" required="" id="minCargo" value="C"> 2 Tons or More<br>
              <input type="radio" name="minCargo" required="" id="minCargo" value="D"> More than 16 Tons<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>3. How are cases assigned to Seals?<br />
              <input type="radio" name="howAssigned" required="" id="howAssigned" value="A"> First come, first serve.<br>
              <input type="radio" name="howAssigned" required="" id="howAssigned" value="B"> Combination of both distance and jumps<br>
              <input type="radio" name="howAssigned" required="" id="howAssigned" value="C"> Jumps to Client<br>
              <input type="radio" name="howAssigned" required="" id="howAssigned" value="D"> Distance to Client<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>4. What size life support is advised?<br />
              <input type="radio" name="LifeSupport" required="" id="LifeSupport" value="A"> A-Rated Life Support<br>
              <input type="radio" name="LifeSupport" required="" id="LifeSupport" value="B"> D-Rated Life Support<br>
              <input type="radio" name="LifeSupport" required="" id="LifeSupport" value="C"> Either A or D-Rated Life Support<br>
              <input type="radio" name="LifeSupport" required="" id="LifeSupport" value="D"> E-Rated Life Support<br>
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
