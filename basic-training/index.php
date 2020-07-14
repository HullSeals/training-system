<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);

$stmt2 = $mysqli->prepare("SELECT count(module_ID) AS status FROM module_progression WHERE progress = 3 AND seal_ID = ?;");
$stmt2->bind_param("i", $user->data()->id);
$stmt2->execute();
$result2 = $stmt2->get_result();
while ($extractarray = $result2->fetch_assoc()) {
  $notArray2=$extractarray['status'];
}
$stmt2->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../assets/header.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Seal Basic Training | The Hull Seals</title>
</head>
<body>
    <div id="home">
        <header>
            <nav class="navbar container navbar-expand-lg navbar-expand-md navbar-dark" role="navigation">
                <a class="navbar-brand" href="#"><img src="../../images/emblem_scaled.png" height="30" class="d-inline-block align-top" alt="Logo"> Hull Seals</a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/https://hullseals.space">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/knowledge">Knowledge Base</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/journal">Journal Reader</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/https://hullseals.space/users/">Login/Register</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <section class="introduction">
	    <article id="intro3">
        <h2>Welcome, <?php echo echousername($user->data()->id); ?>.</h2>
        <p>Please select a module below, or check your completion status.</p>
        <br>
        <table class="table table-dark table-striped table-bordered table-hover table-responsive-md">
          <tr>
              <td>Module</td>
              <td>Status</td>
              <td>Length</td>
              <td>Options</td>
          </tr>
          <?php
          $stmt = $mysqli->prepare("SELECT module_name, progress_name, module_ID, progressID, length
          FROM training.module_progression As mp
          JOIN training.modules_lu AS ml ON ml.moduleID = mp.module_ID
          JOIN training.progression_lu AS pl ON pl.progressID = mp.progress
          WHERE seal_ID = ?;");
          $stmt->bind_param("i", $user->data()->id);
          $stmt->execute();
          $result = $stmt->get_result();

          while ($row = $result->fetch_assoc()) {
            $field1name = $row["progress_name"];
            $field2name = $row["module_name"];
            $field3name = $row["length"];
            if ($row["module_name"] == 'Conclusion') {
              continue;
            }
            echo '<tr>
              <td>'.$field2name.'</td>
              <td>'.$field1name.'</td>
              <td>'.$field3name.' Minutes</td>
              <td>';
              if ($field1name == "Not Yet Started") {
                echo '<a href="'.$row["module_ID"].'" class="btn btn-success btn-block" id="'.$row["module_ID"].'" name="next_btn">Begin?</a>';
              }
              elseif ($field1name == "In Progress") {
                echo '<a href="'.$row["module_ID"].'" class="btn btn-warning btn-block" id="'.$row["module_ID"].'" name="next_btn">Continue</a>';
              }
              elseif ($field1name == "Complete") {
                echo '<a href="'.$row["module_ID"].'" class="btn btn-secondary btn-block" id="'.$row["module_ID"].'" name="next_btn">Review</a>';
              }
              echo '</td>
            </tr>';
          }
          echo '<tr><td>Conclusion</td>';
          if ($notArray2<8) {
            echo '<td>Locked</td>';
          }
          elseif ($notArray2==8) {
            echo '<td>Ready</td>';
          }
          elseif ($notArray2 == 9) {
            echo '<td>Complete</td>';
          }
          echo '<td>6 Minutes</td>';
          if ($notArray2<8) {
            echo '<td><a href="#" class="btn btn-danger btn-block disabled" id="9" name="next_btn">Complete Previous Modules First</a></td>';
          }
          elseif ($notArray2==8) {
            echo '<td><a href="9" class="btn btn-success btn-block" id="9" name="next_btn">Begin?</a></td>';
          }
          elseif ($notArray2==9) {
            echo '<td><a href="9" class="btn btn-secondary btn-block" id="9" name="next_btn">Review</a></td>';
          }
          echo '</tr>';
          $result->free();
          ?>
        </table>
        <p><?php echo $notArray2;?>/9 Modules Complete.</p>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../assets/footer.php'; ?>
</body>
</html>
