<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "";
$pgContent = "";
$useIP = 0; //1 if Yes, 0 if No.

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])){die();}
?>
        <h1>Welcome, Trainer</h1>
        <p>Please choose your option.</p>
        <br>
        <ul class="list-group list-group-horizontal-sm">
        <a href="manage.php" class="list-group-item list-group-item-action">Manage Seals</a>
        <a href="paperwork-list.php" class="list-group-item list-group-item-action">Drill Paperwork Review</a>
        <a href="../training-paperwork" class="list-group-item list-group-item-action">Drill Paperwork</a>
        <a href="../scheduling/requests.php" class="list-group-item list-group-item-action">Drill Management Dashboard</a>
        <a href="../scheduling/index.php" class="list-group-item list-group-item-action">Drill Signup Form</a>
<?php require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>
