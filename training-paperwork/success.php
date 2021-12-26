<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "";
$pgContent = "";
$useIP = 0;
//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
				<h1 style="text-align: center;">Drill Paperwork</h1>
				<h5 class="text-success">Thank you for submitting your paperwork, Seal!</h5>
        <p>Please inform your Trainer.</p>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
