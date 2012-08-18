<?php

$from = 'Siskiyou Velo Membership <membership@siskiyouvelo.org>';
$subject = 'Siskiyou Velo | Thank You For Renewing';
$headers = 'From: '.$from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion() .
	'MIME-Version: 1.0' . "\r\n" .
	'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$message = '
<html>
<head>
  <title>' . $subject . '</title>
</head>
<body>

<table width="780" border="0" cellpadding="0" cellspacing="0" align="center" style="font-family:arial; margin-top:5px;">
<tr valign="top">
    <td rowspan="7" style="font-size:12px; padding:2px;">
    <h2 style="font-size:18px; margin:0;">To Siskiyou Velo Member(s) '.$name.':</h2>
	<p>Thank you for renewing your membership in the Siskiyou Velo. An electronic membership card is <a href="http://www.siskiyouvelo.org/member_card.php?id='.$memberID.'">available here</a>. Print this out and it can be used at participating bike shops to receive a 10% discount.</p>
	<p>Please review your membership details to the right and follow the instructions to update them if appropriate.</p>

	<p>Sincerely,<br />
	'.$membershipVP.'<br />
	VP Membership, Siskiyou Velo</p>
    </td>
	
	<td width="320">
	
	<table cellpadding="0" celspacing="0" style="border:1px solid #000;">
	<tr>
	<td colspan="2" style="border-top:1px solid #000; ">
	<h2 style="font-size:18px; margin:0; background:#000; color:#fff; padding: 0 3px;">Your Membership Details</h2>
	<h3 style="font-size:16px; color:red; margin:0;  padding: 0 3px;">Renewal Date: '.$expire.'</h3>
	
	<p style="font-size:11px;  padding: 0 3px 10px 3px; margin:0;">If any of these details need to be updated, login to the <a href="http://www.siskiyouvelo.org">Siskiyou Velo Website</a> using your primary email address (<em>listed below</em>) and password. If you have forgotten your password, it <a href="http://www.siskiyouvelo.org/login-failed.php">this page</a> contains instructions to reset it. Contact our <a href="mailto:membership@siskiyouvelo.org">Membership VP</a> if you have any questions or encounter any problems.</p>
	</td>
	</tr>
	
	<tr style="background:#eee; font-size:11px; border-top:1px #fff;"><td width="120" style="padding: 0 3px; border-bottom:2px solid #fff;"><strong>Name</strong>:</td><td width="200" style="border-bottom:2px solid #fff; border-left:2px solid #fff; padding: 0 3px;">'.$name.'</td></tr>
	<tr style="background:#eee; font-size:11px;"><td style="padding: 0 3px;border-bottom:2px solid #fff;"><strong>Primary Email</strong>:</td><td style="border-bottom:2px solid #fff; border-left:2px solid #fff; padding: 0 3px;">'.$email.'</td></tr>
	<tr style="background:#eee; font-size:11px;"><td style="padding: 0 3px;border-bottom:2px solid #fff;"><strong>Secondary Email</strong>:</td><td style="border-bottom:2px solid #fff; border-left:2px solid #fff; padding: 0 3px;">'.$email2.'</td></tr>
	
	<tr valign="top" style="background:#eee; font-size:11px;">
		<td rowspan="2" style="padding: 0 3px;"><strong>Address</strong>:</td>
		<td style="border-left:2px solid #fff; padding: 0 3px;">'.$address.'</td></tr>
	<tr style="background:#eee; font-size:11px;"><td style=" border-left:2px solid #fff; padding: 0 3px;">'.$city.', '.$state.' '.$zip.'</td></tr>
	
	<tr valign="top" style="background:#eee; font-size:11px;">
	<td style="border-top:2px solid #fff; padding: 0 3px;"><strong>Phone</strong>:</td><td style="border-top:2px solid #fff; border-left:2px solid #fff; padding: 0 3px;">'.$phone.'</p></td>
	</tr>
	</table>
	
	</td>
</tr>
</table>

<p style="font-size:11px; color:#666; font-family:arial; width:700px; margin:10px auto;">You are receiving this email because you are a member of the Siskiyou Velo Cycling Club. If you wish to modify your email subscriptions, you can do so via your member profile on the <a href="http://www.siskiyouvelo.org">Siskiyou Velo website</a>. <a href="http://www.siskiyouvelo.org/unsubscribe.php?email='.$email.'">Click Here</a> to unsubscribe this address from all Siskiyou Velo email contacts.</p>

</body>
</html>
';
?>