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
  <?php include '../../assets/header.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
</head>
<body>
    <div id="home">
      <?php include '../../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h1>Quiz: TopicNameHere</h1>
        <br />
        <form action="results.php" method = "post">
          <div class="input-group mb-3">
            <p>1. Who is your least favorite Admin?<br />
              <input aria-label="Least Favorite Admin" type="radio" name="faveadmin" id="faveadmin" required="" value="A">Drebin<br>
              <input aria-label="Least Favorite Admin" type="radio" name="faveadmin" id="faveadmin" required="" value="B">Rixxan<br>
              <input aria-label="Least Favorite Admin" type="radio" name="faveadmin" id="faveadmin" required="" value="C">Akastus<br>
              <input aria-label="Least Favorite Admin" type="radio" name="faveadmin" id="faveadmin" required="" value="D">MiddleNate<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>2. What is your Quest?<br />
              <input aria-label="What is your Quest" type="radio" name="quest" required="" id="quest" value="A">MPHG? Really?<br>
              <input aria-label="What is your Quest" type="radio" name="quest" required="" id="quest" value="B">What? I Don&#39;t know that!<br>
              <input aria-label="What is your Quest" type="radio" name="quest" required="" id="quest" value="C">I just want to sleep<br>
              <input aria-label="What is your Quest" type="radio" name="quest" required="" id="quest" value="D">I seek the Holy Grail<br>
            </p>
          </div>
          <div class="input-group mb-3">
            <p>3. What... is the Capital of Assyria?<br />
              <input aria-label="What is the Capital of Assyria?" type="radio" name="capital" required="" id="capital" value="A">Well, I don&#39;t know that!<br>
              <input aria-label="What is the Capital of Assyria?" type="radio" name="capital" required="" id="capital" value="B">Assur<br>
              <input aria-label="What is the Capital of Assyria?" type="radio" name="capital" required="" id="capital" value="C">Nineveh<br>
              <input aria-label="What is the Capital of Assyria?" type="radio" name="capital" required="" id="capital" value="D">Ni!<br>
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
