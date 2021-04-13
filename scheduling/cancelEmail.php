<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$email2 = include 'email.php';

$stmt5 = $mysqli->prepare('WITH sealsCTI
AS
(
SELECT MIN(ID), seal_ID, seal_name
FROM sealsudb.staff
GROUP BY seal_ID
)
SELECT platform_name, training_description, ss.seal_name, sch_nextdate, sch_nexttime, ss2.seal_name AS trainer, email, sch_ID
FROM training.schedule_requests AS sr
INNER JOIN lookups.platform_lu ON seal_PLT = platform_id
INNER JOIN lookups.training_lu ON sch_type = training_id
INNER JOIN sealsCTI AS ss ON ss.seal_ID = sr.seal_ID
LEFT JOIN sealsCTI AS ss2 ON ss2.seal_ID = sr.sch_nextwith
INNER JOIN ircDB.anope_db_NickCore as nc on nc.id = sr.seal_ID
WHERE sch_ID = ?');
$stmt5->bind_param('i', $lore['numberedt3']);
$stmt5->execute();
$result2 = $stmt5->get_result();
while ($row2 = $result2->fetch_assoc()) {
$emnumber = $row2['sch_ID'];
$emplatform = $row2['platform_name'];
$emdesc = $row2['training_description'];
$emname =  $row2['seal_name'];
$emdate = $row2['sch_nextdate'];
$emtime = $row2['sch_nexttime'];
$emtrainer = $row2['trainer'];
$ememail = $row2['email'];
}
$htmlMsg = "<h1>Greetings, CMDR ". $emname ."!</h1><p>This email is to inform you that your training request with The Hull Seals has been cancelled.</p>
<p>The most common reason for this is missing multiple lessons that you have scheduled and confirmed. In order to continue your training, you will need to submit a new training availability request. If you have any questions, please contact HSR Unknown at unknownwolfdev@gmail.com<br><br>
The Hull Seals</p>";
$message = "Greetings, CMDR " . $emname . "!

This email is to inform you that your training request with The Hull Seals has been cancelled. The most common reason for this is missing multiple lessons that you have scheduled and confirmed. In order to continue your training, you will need to submit a new training availability request. If you have any questions, please contact HSR Unknown at unknownwolfdev@gmail.com\r\n
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
?>