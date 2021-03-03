<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$email2 = include 'email.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
if (!securePage($_SERVER['PHP_SELF'])){die();}

//IP Tracking Stuff
require '../../assets/includes/ipinfo.php';

$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training', $db['port']);

$stmt9 = $mysqli->prepare('WITH sealsCTI
AS
(
    SELECT MIN(ID), seal_ID, seal_name
    FROM sealsudb.staff
    GROUP BY seal_ID
)
SELECT email FROM sealsCTI
INNER JOIN auth.user_permission_matches AS aup ON aup.user_ID = sealsCTI.seal_ID
LEFT JOIN ircDB.anope_db_NickCore as nc2 on nc2.id = seal_ID
WHERE aup.permission_ID = 18');
$stmt9->execute();
$result9 = $stmt9->get_result();
while ($row9 = $result9->fetch_assoc()) {
  $ememail = $row9['email'];
$htmlMsg = "<h1>Greetings, Trainer!</h1><p>This email is to inform you that the head trainer has prepared the next week's lessons and wishes to notify you.</p>
<p>Please view the updated information on your <a href='https://hullseals.space/trainings/scheduling/requests.php'>Training Requests Page</a><br><br>
The Hull Seals</p>";
$message = "Greetings, CMDR Trainer!\r\n
This email is to inform you that the head trainer has prepared the next week's lessons and wishes to notify you.\r\n
Please view the updated information on your Training Requests Page (https://hullseals.space/trainings/scheduling/requests.phpTraining)\r\n
The Hull Seals";
$sender = $email2['sender'];
$senderName = $email2['senderName'];
$usernameSmtp = $email2['usernameSmtp'];
$passwordSmtp = $email2['passwordSmtp'];
$host = $email2['host'];
$port = $email2['port'];
$emailMaster = include 'vendor/autoload.php';

$mail = new PHPMailer(true);
try {
// Specify the SMTP settings.
$mail->isSMTP();
$mail->setFrom($sender, $senderName);
$mail->Username   = $usernameSmtp;
$mail->Password   = $passwordSmtp;
$mail->Host       = $host;
$mail->Port       = $port;
$mail->SMTPAuth   = true;
$mail->SMTPSecure = 'tls';

// Specify the message recipients.
$mail->addAddress($ememail);
// You can also add CC, BCC, and additional To recipients here.

// Specify the content of the message.
$mail->isHTML(true);
$mail->Subject    = "Hull Seals Training Notification";
$mail->Body          = $htmlMsg;
$mail->AltBody       = $message;
$mail->Send();
} catch (phpmailerException $e) {
echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
} catch (Exception $e) {
echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
}
}
header("Location: ./requests.php");
?>
<!DOCTYPE html>
  <html lang="en">
  <head>
      <meta content="Requested Trainings" name="description">
      <title>Requested Trainings | The Hull Seals</title>
      <?php include '../../assets/includes/headerCenter.php'; ?>
      <style>
      .center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 20%;
}
</style>
  </head>
  <body>
    <div id="home">
      <?php include '../../assets/includes/menuCode.php';?>
      <section class="introduction container">
        <article id="intro3">
          <h1>Emailing Trainers... Please Wait</h1>
<img src="EDLoader1.svg" alt="Processing..." class="center">
        </article>
        <div class="clearfix"></div>
      </section>
    </div>
    <?php include '../../assets/includes/footer.php'; ?>
  </body>
</html>
