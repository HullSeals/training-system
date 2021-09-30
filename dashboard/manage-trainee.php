<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}
if (!isset($_GET['cne'])) {
  Redirect::to('index.php');
}

//Authenticaton Info
$auth = require 'auth.php';
$secret = $auth['auth'];
$key = $auth['key'];
$url = $auth['url'];
$auth = hash_hmac('sha256', $key, $secret);


//Who are we working with?
$beingManaged = $_GET['cne'];
$beingManaged = intval($beingManaged);
$thenumber1 = "1";
$thenumber2 = "2";

//SQL for the first part
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'auth', $db['port']);

//IRC SQL
$mysqlirc = new mysqli($db['server'], $db['user'], $db['pass'], 'records', $db['port']);
$stmtirc = $mysqlirc->prepare("SELECT nick FROM ircDB.anope_db_NickAlias WHERE nc = ? LIMIT 1;");
$stmtirc->bind_param("s", echousername($beingManaged));
$stmtirc->execute();
$resultirc = $stmtirc->get_result();
$stmtirc->close();
if (!isset($resultirc) || $resultirc == NULL) {
  $resultirc = "Null3";
}

//Case History
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli5 = new mysqli($db['server'], $db['user'], $db['pass'], 'records', $db['port']);
$stmt5 = $mysqli5->prepare("SELECT DISTINCT c.*, ca.dispatch
  FROM cases AS c
  INNER JOIN case_assigned AS ca ON ca.case_ID = c.case_ID
  INNER JOIN sealsudb.staff AS ss ON seal_ID = ca.seal_kf_id
  WHERE seal_id = ?");
  $stmt5->bind_param("i", $beingManaged);
$stmt5->execute();
$result5 = $stmt5->get_result();
$stmt5->close();

//Known Aliases
$mysqliAlias = new mysqli($db['server'], $db['user'], $db['pass'], 'sealsudb', $db['port']);
$stmtAlias = $mysqliAlias->prepare("SELECT seal_name, platform_name FROM staff
JOIN lookups.platform_lu AS s ON s.platform_id=platform
WHERE seal_id = ?");
$stmtAlias->bind_param("i", $beingManaged);
$stmtAlias->execute();
$resultAlias = $stmtAlias->get_result();
$stmtAlias->close();

//Perm Mod SQL
$mysqli2 = new mysqli($db['server'], $db['user'], $db['pass'], $db['db'], $db['port']);
$data=[];

//Add Perm
if (isset($_GET['add']))
{
  foreach ($_REQUEST as $key => $value)
{
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
}
  $stmt3 = $mysqli2->prepare('CALL spAddPerm(?,?,?)');
  $stmt3->bind_param('iii', $beingManaged, $user->data()->id, $data['perm']);
  $stmt3->execute();
  $stmt3->close();
  header("Location: manage-trainer.php?cne=$beingManaged");
}

//Rem Perm
if (isset($_GET['rem']))
{
  foreach ($_REQUEST as $key => $value)
{
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
}
  $stmt4 = $mysqli2->prepare('CALL spRemPerm(?,?,?)');
  $stmt4->bind_param('iii', $beingManaged, $user->data()->id, $data['perm']);
  $stmt4->execute();
  $stmt4->close();
  header("Location: manage-trainer.php?cne=$beingManaged");
}

//Promote
if (isset($_GET['promote']))
{
  foreach ($_REQUEST as $key => $value)
{
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
}
  $stmt3 = $mysqli2->prepare('CALL spAddPerm(?,?,?)');
  $stmt3->bind_param('iii', $beingManaged, $user->data()->id, $thenumber2);
  $stmt3->execute();
  $stmt3->close();

  $stmt4 = $mysqli2->prepare('CALL spRemPerm(?,?,?)');
  $stmt4->bind_param('iii', $beingManaged, $user->data()->id, $thenumber1);
  $stmt4->execute();
  $stmt4->close();
  $data = [
  		"rank" => "seal",
  		"subject" => $resultirc,
  		];
  $postdata = json_encode($data);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
  	'hmac: '. $auth
  ));
  $result = curl_exec($ch);
  curl_close($ch);
  header("Location: manage-trainer.php?cne=$beingManaged");
}

//Demote
if (isset($_GET['demote']))
{
  foreach ($_REQUEST as $key => $value)
{
    $data[$key] = strip_tags(stripslashes(str_replace(["'", '"'], '', $value)));
}
  $stmt3 = $mysqli2->prepare('CALL spAddPerm(?,?,?)');
  $stmt3->bind_param('iii', $beingManaged, $user->data()->id, $thenumber1);
  $stmt3->execute();
  $stmt3->close();

  $stmt4 = $mysqli2->prepare('CALL spRemPerm(?,?,?)');
  $stmt4->bind_param('iii', $beingManaged, $user->data()->id, $thenumber2);
  $stmt4->execute();
  $stmt4->close();
  $data = [
  		"rank" => "pup",
  		"subject" => $resultirc,
  		];
  $postdata = json_encode($data);
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
  	'hmac: '. $auth
  ));
  $result = curl_exec($ch);
  curl_close($ch);
  header("Location: manage-trainer.php?cne=$beingManaged");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="../assets/datatables.min.css"/>
  <?php include '../../assets/includes/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Manage Trainee | The Hull Seals</title>
    <script type="text/javascript" src="../assets/datatables.min.js"></script>
    <script>
    	$(document).ready(function() {
    	$('#LookupList').DataTable();
  		} );
  	</script>
</head>
<body>
    <div id="home">
      <?php include '../../assets/includes/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h2>Welcome, <?php echo echousername($user->data()->id); ?>.</h2>
        <p>You are managing user: <em><?php echo echousername($beingManaged);?></em> <a href="manage.php" class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
        <br>
        <h3>Registered CMDRs</h3>
        <br>
        <div class="table-responsive-md">
        <table class="table table-hover table-dark table-bordered table-striped" id="remPerms">
          <thead>
          <tr>
            <th>CMDR Name</th>
            <th>CMDR Platform</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($resultAlias->num_rows === 0) {
            echo '<tr>
            <td>No CMDRs.</td>
            <td>Remind them to Register!</td>
            </tr>';
          }
          else {
            while ($rowAlias=$resultAlias->fetch_assoc()) {
              $fieldAliasName=$rowAlias["seal_name"];
              $fieldAliasPLT=$rowAlias["platform_name"];
            echo '<tr>
            <td>'.$fieldAliasName.'</td>
            <td>'.$fieldAliasPLT.'</td>
            </tr>';
          }
        }
          $resultAlias->free();
        ?>
      </tbody>
      </table></div>
        <br>
        <h3>Permission Management</h3>
        <br>
        <?php
          if (hasPerm([1],$beingManaged)) { ?>
            <div class="table-responsive-md">
            <table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover" id="pup">
              <thead>
              <tr>
                  <th> <font face="Arial">Core Level</font> </th>
                  <th> <font face="Arial">Modify To</font> </th>
              </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Pup</td>
                  <td><form method="post" action="?promote&cne=<?php echo $beingManaged; ?>">
            <button type="submit" class="btn btn-secondary" id="promote" name="promote">Seal</button>
          </form></td>
                </tr>
              </tbody>
            </table>
            </div>
          <?php }
          elseif (hasperm([2],$beingManaged)) { ?>
            <div class="table-responsive-md">
            <table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover" id="seal">
              <thead>
              <tr>
                  <th> <font face="Arial">Core Level</font> </th>
                  <th> <font face="Arial">Modify To</font> </th>
              </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Seal</td>
                  <td><form method="post" action="?demote&cne=<?php echo $beingManaged; ?>">
            <button type="submit" class="btn btn-secondary" id="promote" name="promote">Pup</button>
          </form></td>
                </tr>
              </tbody>
            </table>
            </div>
        <?php  } ?>
        <div class="table-responsive-md">
        <table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover" id="secondaries">
          <thead>
          <tr>
              <th> <font face="Arial">Optional Qualifications</font> </th>
              <th> <font face="Arial">Action</font> </th>
          </tr>
          </thead>
          <tbody>
            <tr>
              <td>KingFisher</td>
              <td><form method="post" action="?<?php if (hasPerm([3],$beingManaged)) {
                echo "rem";
              }
              else {
                echo "add";
              } ?>&cne=<?php echo $beingManaged; ?>">
              <input type="hidden" name="perm" value="3">
        <button type="submit" class="btn btn-secondary" id="kingfishersub" name="kingfishersub"><?php if (hasPerm([3],$beingManaged)) {
          echo "Remove";
        }
        else {
          echo "Add";
        } ?></button>
      </form></td>
            </tr>
            <tr>
              <td>Dispatcher</td>
              <td><form method="post" action="?<?php if (hasPerm([6],$beingManaged)) {
                echo "rem";
              }
              else {
                echo "add";
              } ?>&cne=<?php echo $beingManaged; ?>">
              <input type="hidden" name="perm" value="6">
        <button type="submit" class="btn btn-secondary" id="dispatchsub" name="dispatchsub"><?php if (hasPerm([6],$beingManaged)) {
          echo "Remove";
        }
        else {
          echo "Add";
        } ?></button>
      </form></td>
            </tr>
          </tbody>
        </table>
        </div>
    <br>
    <h3>Cases Assigned</h3>
    <br>
    <div class="table-responsive-md">
    <table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover" id="LookupList">
      <thead>
      <tr>
          <th> <font face="Arial">Case ID</font> </th>
          <th> <font face="Arial">Case Date</font> </th>
          <th> <font face="Arial">CMDR Type?</font> </th>
      </tr>
      </thead>
    <?php
    if ($result5->num_rows === 0) {
      echo '<tr>
      <td>No Rescues</td>
      <td>No Rescues</td>
      <td>No Rescues</td>
      </tr>';
    }
    else {
      while ($row5 = $result5->fetch_assoc()) {
        $field15name = $row5["case_ID"];
        $field25name = $row5["case_created"];
        $field35name = $row5["dispatch"];
        echo '<tr>
                  <td>'.$field15name.'</td>
                  <td>'.$field25name.'</td>
                  <td>';
                  if ($field35name == "1") {
                    echo 'Dispatcher';
                  }
                  elseif ($field35name == "0") {
                    echo 'Seal';
                  }
                  echo '</td>
                </tr>';
    }
  }
    echo '</table></div>';
    $result5->free();
?>
      <br>
      <p><a href="manage.php" class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
</body>
</html>
