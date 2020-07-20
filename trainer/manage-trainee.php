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

//Who are we working with?
$beingManaged = $_GET['cne'];
$beingManaged = intval($beingManaged);

//SQL for the first part
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = include '../assets/db.php';
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'auth', $db['port']);

//Existing Perms
$stmt = $mysqli->prepare("SELECT u.name AS name, permission_id
FROM permissions AS u
JOIN user_permission_matches AS s ON s.permission_id = u.ID
WHERE user_id = ?
AND permission_id IN (1,2,3,4,5,6,16,17)
ORDER BY permission_id ASC;");
$stmt->bind_param("i", $beingManaged);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

//Unassigned Perms
$stmt2 = $mysqli->prepare("SELECT u.name AS name
FROM permissions AS u
JOIN user_permission_matches AS s ON s.permission_id = u.ID
WHERE user_id = ?
AND permission_id IN (1,2,3,4,6,16,17)
ORDER BY permission_id ASC;");
$stmt2->bind_param("i", $beingManaged);
$stmt2->execute();
$result2 = $stmt2->get_result();
$stmt2->close();

//Awful, awful badness. TODO. Fix.
$perm1=0;
$perm2=0;
$perm3=0;
$perm4=0;
$perm6=0;
$perm16=0;
$perm17=0;

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
  $stmt3->bind_param('iii', $beingManaged, $user->data()->id, $data['permAdded']);
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
  $stmt4->bind_param('iii', $beingManaged, $user->data()->id, $data['permRemoved']);
  $stmt4->execute();
  $stmt4->close();
  header("Location: manage-trainer.php?cne=$beingManaged");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include '../assets/headerCenter.php'; ?>
    <meta content="Welcome to the Hull Seals, Elite Dangerous's Premier Hull Repair Specialists!" name="description">
    <title>Manage Trainee | The Hull Seals</title>
    <link rel="stylesheet" type="text/css" href="datatables.min.css"/>
    <script type="text/javascript" src="datatables.min.js"></script>
    <script>
    	$(document).ready(function() {
    	$('#LookupList').DataTable();
  		} );
  	</script>
</head>
<body>
    <div id="home">
      <?php include '../assets/menuCode.php';?>
        <section class="introduction container">
	    <article id="intro3">
        <h2>Welcome, <?php echo echousername($user->data()->id); ?>.</h2>
        <p>You are managing user: <em><?php echo echousername($beingManaged);?></em></p>
        <br>
        <table class="table table-hover table-dark table-bordered table-striped table-responsive-sm" id="remPerms">
          <thead>
          <tr>
            <th>Existing Permissions</th>
            <th>Remove Permission?</th>
          </tr>
        </thead>
        <tbody>
        <?php
          while ($row = $result->fetch_assoc()) {
            $field1name = $row["name"];
            $field2name = $row["permission_id"];
            if ($row["permission_id"]==1) {
              $perm1 = 1;
            }
            if ($row["permission_id"]==2) {
              $perm2 = 1;
            }
            if ($row["permission_id"]==3) {
              $perm3 = 1;
            }
            if ($row["permission_id"]==4) {
              $perm4 = 1;
            }
            if ($row["permission_id"]==6) {
              $perm6 = 1;
            }
            if ($row["permission_id"]==16) {
              $perm16 = 1;
            }
            if ($row["permission_id"]==17) {
              $perm17 = 1;
            }
            echo '<tr>
            <td>'.$field1name.'</td>
            <td><form method="post" action="?rem&cne='.$beingManaged.'">
  		      <input type="hidden" name="permRemoved" value="'.$field2name.'">
            <button type="submit" class="btn btn-secondary" id="remove" name="remove">Remove</button>
          </form></td>
            </tr>';
          }
          $result->free();
        ?>
      </tbody>
      </table>
      <br>
      <table class="table table-hover table-dark table-bordered table-striped table-responsive-sm" id="addPerms">
        <thead>
        <tr>
          <th>Unassigned Permissions</th>
          <th>Add Permission?</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if ($perm1==0) {
          echo '<tr>
          <td>Pup</td>
          <td><form method="post" action="?add&cne='.$beingManaged.'">
		      <input type="hidden" name="permAdded" value="1">
          <button type="submit" class="btn btn-secondary" id="add" name="add">Add</button>
        </form></td>
          </tr>';
        }
        if ($perm2==0) {
          echo '<tr>
          <td>Seal</td>
          <td><form method="post" action="?add&cne='.$beingManaged.'">
		      <input type="hidden" name="permAdded" value="2">
          <button type="submit" class="btn btn-secondary" id="add" name="add">Add</button>
        </form></td>
          </tr>';
        }
        if ($perm3==0) {
          echo '<tr>
          <td>Kingfisher</td>
          <td><form method="post" action="?add&cne='.$beingManaged.'">
		      <input type="hidden" name="permAdded" value="3">
          <button type="submit" class="btn btn-secondary" id="add" name="add">Add</button>
        </form></td>
          </tr>';
        }
        if ($perm4==0) {
          echo '<tr>
          <td>Trainer</td>
          <td><form method="post" action="?add&cne='.$beingManaged.'">
		      <input type="hidden" name="permAdded" value="4">
          <button type="submit" class="btn btn-secondary" id="add" name="add">Add</button>
        </form></td>
          </tr>';
        }
        if ($perm6==0) {
          echo '<tr>
          <td>Dispatcher</td>
          <td><form method="post" action="?add&cne='.$beingManaged.'">
		      <input type="hidden" name="permAdded" value="6">
          <button type="submit" class="btn btn-secondary" id="add" name="add">Add</button>
        </form></td>
          </tr>';
        }
        if ($perm16==0) {
          echo '<tr>
          <td>Walrus</td>
          <td><form method="post" action="?add&cne='.$beingManaged.'">
		      <input type="hidden" name="permAdded" value="17">
          <button type="submit" class="btn btn-secondary" id="add" name="add">Add</button>
        </form></td>
          </tr>';
        }
        if ($perm17==0) {
          echo '<tr>
          <td>ChemSeal</td>
          <td><form method="post" action="?add&cne='.$beingManaged.'">
		      <input type="hidden" name="permAdded" value="17">
          <button type="submit" class="btn btn-secondary" id="add" name="add">Add</button>
        </form></td>
          </tr>';
        }
        ?>
    </tbody>
    </table>
    <br>
    <?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli5 = new mysqli($db['server'], $db['user'], $db['pass'], 'records', $db['port']);
$stmt5 = $mysqli5->prepare("SELECT c.*, ca.dispatch
  FROM cases AS c
  INNER JOIN case_assigned AS ca ON ca.case_ID = c.case_ID
  INNER JOIN sealsudb.staff AS ss ON ss.seal_id = ca.seal_kf_id
  WHERE seal_id = ?");
  $stmt5->bind_param("i", $beingManaged);
$stmt5->execute();
$result5 = $stmt5->get_result();
if($result5->num_rows === 0) exit('No Rescues');
echo '<table border="5" cellspacing="2" cellpadding="2" class="table table-dark table-striped table-bordered table-hover table-sm table-responsive-sm" id="LookupList">
      <thead>
      <tr>
          <th> <font face="Arial">Case ID</font> </th>
          <th> <font face="Arial">Case Date</font> </th>
          <th> <font face="Arial">CMDR Type?</font> </th>
      </tr>
      </thead>';
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
    echo '</table>';
    $result5->free();
?>
      <br>
      <p><a href="." class="btn btn-small btn-danger" style="float: right;">Go Back</a></p>
      </article>
            <div class="clearfix"></div>
        </section>
    </div>
    <?php include '../assets/footer.php'; ?>
</body>
</html>
