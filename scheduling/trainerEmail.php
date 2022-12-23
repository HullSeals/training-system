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
SELECT platform_name, training_description, ss.seal_name, sch_nextdate, sch_nexttime, ss2.seal_name AS trainer, nc2.email
FROM training.schedule_requests AS sr
INNER JOIN lookups.platform_lu ON seal_PLT = platform_id
INNER JOIN lookups.training_lu ON sch_type = training_id
INNER JOIN sealsCTI AS ss ON ss.seal_ID = sr.seal_ID
LEFT JOIN sealsCTI AS ss2 ON ss2.seal_ID = sr.sch_nextwith
LEFT JOIN ircDB.anope_db_NickCore as nc2 on nc2.id = ss2.seal_ID
WHERE sch_ID = ?');
$stmt5->bind_param('i', $sch_ID);
$stmt5->execute();
$result2 = $stmt5->get_result();
while ($row2 = $result2->fetch_assoc()) {
    $emplatform = $row2['platform_name'];
    $emdesc = $row2['training_description'];
    $emname =  $row2['seal_name'];
    $emdate = $row2['sch_nextdate'];
    $emtime = $row2['sch_nexttime'];
    $emtrainer = $row2['trainer'];
    $ememail = $row2['email'];
}
$htmlMsg = "<h1>Greetings, CMDR " . $emtrainer . "!</h1><p>This email is to inform you that your Pup has confirmed their next lesson! Here are the details:</p>
<ul>
<li>Training Type: " . $emdesc . "</li>
<li>Training Date: " . $emdate . "</li>
<li>Training Time: " . $emtime . " UTC</li>
<li>Training Platform: " . $emplatform . "</li>
<li>Pup: CMDR " . $emname . "</li>
</ul>
<p>No further action is required from you at this time.
<br><br>
The Hull Seals</p>";
$message = "Greetings, CMDR " . $emtrainer . "!

This email is to inform you that your Pup has confirmed their next lesson! Here are the details:\r\n
Training Type: " . $emdesc . "\r\n
Training Date: " . $emdate . "\r\n
Training Time: " . $emtime . " UTC\r\n
Training Platform: " . $emplatform . "\r\n
Pup: " . $emname . "\r\n
No further action is required from you at this time.\r\n
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
    $mail->Subject    = "Hull Seals Trainer Notification";
    $mail->Body          = $htmlMsg;
    $mail->AltBody       = $message;
    $mail->Send();
} catch (phpmailerException $e) {
    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
} catch (Exception $e) {
    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
}
