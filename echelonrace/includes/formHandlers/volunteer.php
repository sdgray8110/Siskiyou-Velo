<?php

function radioValue($array) {
    $str = '';

    foreach ($array as $value) {
        $str == '' ? $sep = '' : $sep = ', ';

        if ($value != '') {
            $str = $str . $sep . $value;
        }
    }

    return $str;
}

$days = array($_POST['daysSunday'], $_POST['daysMonday'], $_POST['daysTuesday'], $_POST['daysWednesday'], $_POST['daysThursday'], $_POST['daysFriday'], $_POST['daysSaturday']);
$type = array($_POST['workStart'], $_POST['workRegistration'], $_POST['workSetup'], $_POST['workSponsor'], $_POST['workTrail'], $_POST['workStaff'], $_POST['workOfficial']);

$userInfo = '
<p><strong>Contact Info:</strong><br />
Name: <strong>'.$_POST['username'].'</strong></br>
Address Line 1: <strong>'.$_POST['address1'].'</strong></br>
Address Line 2: <strong>'.$_POST['address2'].'</strong></br>
City: <strong>'.$_POST['city'].'</strong></br>
State: <strong>'.$_POST['state'].'</strong></br>
ZIP: <strong>'.$_POST['zip'].'</strong></br>
Phone: <strong>'.$_POST['phone'].'</strong></br>
Email: <strong>'.$_POST['email'].'</strong></br>
Preferred Contact Time: <strong>'.$_POST['contactTime'].'</strong></p>
';

$preferences = '
<p><strong>Have you previously done volunteer work for Echelon Events LLC.</strong><br>
'.$_POST['previousEvents'].'</p>

<p><strong>Where did you find out about this opportunity?</strong><br>
'.$_POST['howLearned'].'</p>


<p><strong>What days of the week are you available?</strong><br>
'.
radioValue($days)
.'</p>

<p><strong>What areas of work would you be interested in (check all that apply)?</strong><br>
'.
radioValue($type)
.'</p>';

$comments = '
<p><strong>Comments:</strong><br>
'.
$_POST['comments']
.'</p>';

$from = 'Echelon Events <info@echelonrace.com>';
$to = 'echelonevents@live.com';
$subject = 'Echelon Events | Volunteer Request';
$headers = 'From: '.$from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$message =  $userInfo . $preferences . $comments;


mail($to, $subject, $message, $headers);

header('Location: http://www.echelonrace.com/volunteer.php?complete=true');
exit;

?>