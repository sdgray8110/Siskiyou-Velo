<?php 
$getID = $_GET['id'];
include('includes/db_connect.php');

$emails = mysql_query("SELECT * FROM wp_users WHERE DateExpire = (DATE_ADD(curdate(), INTERVAL -1 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY wp_users . lastname", $connection);
$default = '39b0d86a2144a5cba348f7ec1557e20e';

while ($emailRow = mysql_fetch_array($emails)) {
$email = $emailRow['email1'];
$email2 = $emailRow['email2'];
$name = $emailRow['firstname'].' '.$emailRow['lastname'];
$expire = date('n/d/Y', strtotime($emailRow["DateExpire"]));
$address = $emailRow['address'];
$city = $emailRow['city'];
$state = $emailRow['state'];
$zip = $emailRow['zip'];
$phone = $emailRow['phone'];

echo '<table width="780" border="0" cellpadding="0" cellspacing="0" align="center" style="font-family:arial; margin-top:5px;">
<tr valign="top">
    <td rowspan="7" style="font-size:12px; padding:2px;">
    <h2 style="font-size:18px; margin:0;">To Siskiyou Velo Member(s) '.$name.' :</h2>
	<p>It may have escaped your notice that your renewal date of <strong>'.$expire.'</strong> has slipped by assuming that my record keeping is accurate. We hope that you will renew your membership in the Velo. You can renew your membership by downloading the <a href="http://www.siskiyouvelo.org/images/PDF/JointheVelo.pdf">Velo Membership Form</a> and sending it in.</p>

	<p>Sincerely,<br />
	Don Parker<br />
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

<p style="font-size:11px; color:#666; font-family:arial; width:700px; margin:10px auto;">You are receiving this email because you are a member of the Siskiyou Velo Cycling Club. If you wish to modify your email subscriptions, you can do so via your member profile on the <a href="http://www.siskiyouvelo.org">Siskiyou Velo website</a>. <a href="http://www.siskiyouvelo.org/unsubscribe.php?email='.$email.'">Click Here</a> to unsubscribe this address from all Siskiyou Velo email contacts.</p>';

}

?>