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
if($_SESSION['whatsaseal'] != "true"){
   //send them back
   header("Location: .");
}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../../../assets/includes/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Quiz: What is a Seal? | The Hull Seals</title>
</head>
<body>
    <div id="home">
      <?php include '../../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Quiz: What is a Seal?</h1>
        <br />
        <form action="results.php" method = "post">
          <div class="input-group mb-3">
            <p>1. Do the Seals charge for our services?<br />
              <input type="radio" name="charge" id="charge" required="" value="A"> Yes.<br>
              <input type="radio" name="charge" id="charge" required="" value="B"> No.<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>2. Are the procedures in the SOP required at all times?<br />
              <input type="radio" name="SOPr" required="" id="SOPr" value="A"> Yes, at all times.<br>
              <input type="radio" name="SOPr" required="" id="SOPr" value="B"> No, they are only suggestions.<br>
              <input type="radio" name="SOPr" required="" id="SOPr" value="C"> Yes, even out of date versions.<br>
              <input type="radio" name="SOPr" required="" id="SOPr" value="D"> No, but they are the best guide and are strongly encouraged.<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>3. Where can you view the updated SOPs?<br />
              <input type="radio" name="nSOP" required="" id="nSOP" value="A"> In the knowledge base.<br>
              <input type="radio" name="nSOP" required="" id="nSOP" value="B"> In IRC, by asking for a copy from a Trainer.<br>
              <input type="radio" name="nSOP" required="" id="nSOP" value="C"> By Emailing the Seal admins.<br>
              <input type="radio" name="nSOP" required="" id="nSOP" value="D"> There is no written SOP. It is all guidance and advice shared among Seals.<br>
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
