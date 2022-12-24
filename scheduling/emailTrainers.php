<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email2 = include 'email.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Declare Title, Content, Author
$pgAuthor = "David Sangrey";
$pgContent = "Email Trainers";
$useIP = 1; //1 if Yes, 0 if No.

$customContent = '<style>
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 20%;
}
</style>';

//UserSpice Required
require_once '../../users/init.php';  //make sure this path is correct!
require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
  die();
}
?>

<h1>Emailing Trainers... Please Wait</h1>
<img src="EDLoader1.svg" alt="Processing..." class="center">

<?
$db = include '../assets/db.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($db['server'], $db['user'], $db['pass'], 'training', $db['port']);
# TODO: SP?
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
WHERE aup.permission_ID = 4');
$stmt9->execute();
$result9 = $stmt9->get_result();
while ($row9 = $result9->fetch_assoc()) {
  $ememail = $row9['email'];
  $htmlMsg = "<h1>Greetings, Trainer!</h1><p>This email is to inform you that the head trainer has prepared the next week's lessons and wishes to notify you.</p>
<p>Please view the updated information on your <a href='https://hullseals.space/trainings/scheduling/requests.php'>Training Requests Page</a><br><br>
The Hull Seals</p>";
  $message = "Greetings, CMDR Trainer!\r\n
This email is to inform you that the head trainer has prepared the next week's lessons and wishes to notify you.\r\n
Please view the updated information on your Training Requests Page (https://hullseals.space/trainings/scheduling/requests.php)\r\n
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
    // Specify the content of the message.
    $mail->isHTML(true);
    $mail->Subject       = "Hull Seals Training Notification";
    $mail->Body          = $htmlMsg;
    $mail->AltBody       = $message;
    $mail->Send();
    $valmsg = "Email Sent Successfully";
  } catch (phpmailerException $e) {
    $valmsg = "An error occurred. {$e->errorMessage()}"; //Catch errors from PHPMailer.
  } catch (Exception $e) {
    $valmsg = "Email not sent. {$mail->ErrorInfo}"; //Catch errors from Amazon SES.
  }
}
sessionValMessages("", "", $valmsg);
header("Location: ./requests.php");
require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php'; ?>