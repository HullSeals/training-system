<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
 <title>Drill Paperwork | The Hull Seals</title>
 <meta content="David Sangrey" name="author">
 <?php include '../assets/headerCenter.php'; ?>
</head>
<body>
    <div id="home">
      <?php include '../assets/menuCode.php';?>
      <section class="introduction container">
    <article id="intro3">
				<h1 style="text-align: center;">Drill Paperwork</h1>
				<h5 class="text-success">Thank you for submitting your paperwork, Seal!</h5>
        <p>Please inform your Trainer.</p>
      </article>
      <div class="clearfix"></div>
  </section>
  </div>
  <?php include '../assets/footer.php'; ?>
  </body>
  </html>
