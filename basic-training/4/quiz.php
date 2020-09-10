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
if($_SESSION['terms'] != "true"){
   //send them back
   header("Location: .");
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../../assets/includes/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Quiz: Common Terms | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Quiz: Common Terms</h1>
        <br />
        <form action="results.php" method = "post">
          <div class="input-group mb-3">
            <p>1. What does FR mean?<br />
              <input type="radio" name="FR" id="FR" required="" value="A"> Fleet Carrier ReBCed<br>
              <input type="radio" name="FR" id="FR" required="" value="B"> Fuel Rat<br>
              <input type="radio" name="FR" id="FR" required="" value="C"> Flaming Nachos<br>
              <input type="radio" name="FR" id="FR" required="" value="D"> Friend ReBC<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>2. What does BC mean?<br />
              <input type="radio" name="BC" required="" id="BC" value="A"> Banned Client<br>
              <input type="radio" name="BC" required="" id="BC" value="B"> Bacon<br>
              <input type="radio" name="BC" required="" id="BC" value="C"> Beacon<br>
              <input type="radio" name="BC" required="" id="BC" value="D"> Bacon Cheeseburger<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>3. What does PW mean?<br />
              <input type="radio" name="PW" required="" id="PW" value="A"> Potassium Wombat<br>
              <input type="radio" name="PW" required="" id="PW" value="B"> Paperwork<br>
              <input type="radio" name="PW" required="" id="PW" value="C"> Proceeding to Wing<br>
              <input type="radio" name="PW" required="" id="PW" value="D"> Potentially Wrong System<br>
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
