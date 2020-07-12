<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

$selectedMod = [];
$validationErrors = [];
if (isset($_GET['begin'])) {
    foreach ($_REQUEST as $key => $value) {
        $selectedMod[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
    }
    if (!count($validationErrors)) {
        $stmt = $mysqli->prepare('CALL sp(?,?)');
        $stmt->bind_param('ii',$selectedMod['moduleName'], $user->data()->id);
        $stmt->execute();
        foreach ($stmt->error_list as $error) {
            $validationErrors[] = 'DB: ' . $error['error'];
        }
        $stmt->close();
        header("Location: ");
    }
  }
  if (isset($_GET['continue'])) {
      foreach ($_REQUEST as $key => $value) {
          $selectedMod[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
      }
      if (!count($validationErrors)) {
          $stmt = $mysqli->prepare('CALL sp(?,?)');
          $stmt->bind_param('ii',$selectedMod['moduleName'], $user->data()->id);
          $stmt->execute();
          foreach ($stmt->error_list as $error) {
              $validationErrors[] = 'DB: ' . $error['error'];
          }
          $stmt->close();
          header("Location: ");
      }
    }
    if (isset($_GET['review'])) {
        foreach ($_REQUEST as $key => $value) {
            $selectedMod[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
        }
        if (!count($validationErrors)) {
            $stmt = $mysqli->prepare('CALL sp(?,?)');
            $stmt->bind_param('ii',$selectedMod['moduleName'], $user->data()->id);
            $stmt->execute();
            foreach ($stmt->error_list as $error) {
                $validationErrors[] = 'DB: ' . $error['error'];
            }
            $stmt->close();
            header("Location: ");
        }
      }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../../favicon.ico" rel="icon" type="image/x-icon">
    <link href="../../favicon.ico" rel="shortcut icon" type="image/x-icon">
    <meta charset="UTF-8">
    <meta content="David Sangrey" name="author">
    <meta content="hull seals, elite dangerous, distant worlds, seal team fix, mechanics, dw2" name="keywords">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0" name="viewport">
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Seal Basic Training | The Hull Seals</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <link rel="stylesheet" type="text/css" href="assets/trainercss.css" />
    <script src="https://hullseals.space/assets/javascript/allPages.js" integrity="sha384-PsQdnKGi+BdHoxLI6v+pi6WowfGtnraU6GlDD4Uh5Qw2ZFiDD4eWNTNG9+bHL3kf" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd" crossorigin="anonymous"></script>
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
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="knowledge">Knowledge Base</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="journal">Journal Reader</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="about">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://hullseals.space/users/">Login/Register</a>
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
        <?php
        if (count($validationErrors)) {
            foreach ($validationErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            echo '<br>';
        }
        ?>

        <table class="table table-dark table-striped table-bordered table-hover table-responsive-md">
          <tr>
              <td>Module</td>
              <td>Status</td>
              <td>Length</td>
              <td>Options</td>
          </tr>
          <?php
          mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
          $db = include 'assets/db.php';
          $mysqli = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);

          $stmt = $mysqli->prepare("SELECT module_name, progress_name, module_ID, progressID
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
            echo '<tr>
              <td>'.$field2name.'</td>
              <td>'.$field1name.'</td>
              <td> LengthHere</td>
              <td>';
              if ($field1name == "Not Yet Started") {
                echo '<form method="post" action="?begin"><input type="hidden" name="moduleName" value="'.$row["module_ID"].'" required><button type="submit" class="btn btn-success btn-block" id="'.$row["module_ID"].'" name="next_btn">Begin?</button>';
              }
              elseif ($field1name == "In Progress") {
                echo '<form method="post" action="?continue"><input type="hidden" name="moduleName" value="'.$row["module_ID"].'" required><button type="submit" class="btn btn-warning btn-block" id="'.$row["module_ID"].'" name="next_btn">Continue</button>';
              }
              elseif ($field1name == "Complete") {
                echo '<form method="post" action="?review"><input type="hidden" name="moduleName" value="'.$row["module_ID"].'" required><button type="submit" class="btn btn-secondary btn-block" id="'.$row["module_ID"].'" name="next_btn">Review</button>';
              }
              echo '</td>
            </tr>';
          }
          $result->free();
          ?>
        </table>
        <p>2/9 Modules Complete.</p>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <footer class="page-footer font-small">
        <div class="container">
            <div class="row">
                <div class="col-md-9 mt-md-0 mt-3">
                    <h5 class="text-uppercase">Hull Seals</h5>
                    <p><em>The Hull Seals</em> were established in January of 3305, and have begun plans to roll out galaxy-wide!</p>
					<a href="https://fuelrats.com/i-need-fuel" class="btn btn-sm btn-secondary">Need Fuel? Call the Rats!</a>
                </div>
                <hr class="clearfix w-100 d-md-none pb-3">
                <div class="col-md-3 mb-md-0 mb-3">
                    <h5 class="text-uppercase">Links</h5>

                    <ul class="list-unstyled">
                        <li><a href="https://twitter.com/HullSeals" target="_blank"><img alt="Twitter" height="20" src="../../images/twitter_loss.png" width="20"></a> <a href="https://reddit.com/r/HullSeals" target="_blank"><img alt="Reddit" height="20" src="../../images/reddit.png" width="20"></a> <a href="https://www.youtube.com/channel/UCwKysCkGU_C6V8F2inD8wGQ" target="_blank"><img alt="Youtube" height="20" src="../../images/youtube.png" width="20"></a> <a href="https://www.twitch.tv/hullseals" target="_blank"><img alt="Twitch" height="20" src="../../images/twitch.png" width="20"></a> <a href="https://gitlab.com/hull-seals-cyberseals" target="_blank"><img alt="GitLab" height="20" src="../../images/gitlab.png" width="20"></a></li>
						<li><a href="/donate">Donate</a></li>
                        <li><a href="https://hullseals.space/knowledge/books/important-information/page/privacy-policy">Privacy & Cookies Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-copyright">
            Site content copyright © 2019, The Hull Seals. All Rights Reserved. Elite Dangerous and all related marks are trademarks of Frontier Developments Inc.
        </div>
    </footer>
</body>
</html>
