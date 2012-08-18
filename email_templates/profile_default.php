<?php

$from = 'Siskiyou Velo Membership <membership@siskiyouvelo.org>';
$subject = 'Siskiyou Velo | Membership Profiles';
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
<p>We have modified the Member Profile to provide information that we hope will lead to some ideas for addressing the needs of club members in a more meaningful way. This data is strictly private, and will only be used by the Officers of your club. We had 33 members (out of 300) update as result of the first e-mail, 3 more on the second and I would like to see at least 50 more update as result of this one. So, hammerhead&rsquo;s slow down, moderate&rsquo;s maintain your pace and mellow&rsquo;s, please pick it up just a notch for a minute or two, and let&rsquo;s attack this like we do a head wind - as a cohesive group. HELP!</p>
 
<p>Some questions we want to answer:</p>

<ol> 
<li>Are we holding meetings in the right place to be accessible to the membership?</li>
<li>Are we offering the right pace mix for our rides?</li>
<li>As a group, are we getting older, younger, staying the same?</li>
<li>What are the interests of the members of the club?</li>
</ol>

<p>To access your profile, please visit <a href=&quot;http://www.siskiyouvelo.org&quot;>www.siskiyouvelo.org</a>, enter you e-mail and password (see the sidebar for more information), go to Members Only, choose Membership Profile and then just go down the line. It only takes a couple of minutes. Please be sure to provide information in each category and then hit SAVE when you are done.</p>
 
<p>I will be pulling membership data over the weekend, so please update your profile by Friday, January 15 to be represented in the information.</p>
 
<p>I appreciate the &quot;PULL&quot;,</p>
 
<p>Ron Zell, VP Membership</p>
    </td>
	
	<td width="320">
	
	<table cellpadding="0" celspacing="0" style="border:1px solid #000;">
	<tr>
	<td colspan="2" style="border-top:1px solid #000; ">
	<h2 style="font-size:18px; margin:0; background:#000; color:#fff; padding: 0 3px;">Your Membership Details</h2>
	<h3 style="font-size:16px; color:red; margin:0;  padding: 0 3px;">Renewal Date: '.$expire.'</h3>
	
	<p style="font-size:11px;  padding: 0 3px 10px 3px; margin:0;">If any of these details need to be updated, login to the <a href="http://www.siskiyouvelo.org">Siskiyou Velo Website</a> using the primary email address (<em>listed below</em>) and password, <strong>greensprings</strong> (<em>all lowercase</em>).</p>
	
	<p style="font-size:11px;  padding: 0 3px 10px 3px;  margin:0;">Once you login, access your Member Profile page to update these details. Additionally, for the security of your account, we would encourage you to change your password. Contact our <a href="mailto:membership@siskiyouvelo.org">Membership VP</a> if you have any questions or encounter any problems.</p>
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