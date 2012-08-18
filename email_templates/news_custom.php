<?php
$news = mysql_query("SELECT * FROM sv_newsbrief ORDER BY ID DESC LIMIT 1", $connection);
$newsRow = mysql_fetch_array($news);
$item1 = '<li>'.$newsRow["item1"].'</li>';
$item2 = '<li>'.$newsRow["item2"].'</li>';
if (!$newsRow["item3"]) { $item3 = '';}
else {$item3 = '<li>'.$newsRow["item3"].'</li>';}

$from = 'Siskiyou Velo Newsletter <newsletter@siskiyouvelo.org>';
$subject = 'Siskiyou Velo | Your '.$newsRow["issue"].' newsletter is now available.';
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
    <p>The <a href="http://www.siskiyouvelo.org/images/Newsletters/PDF/'.$newsRow["filename"].'">'.$newsRow["issue"].'</a> issue of the Siskiyou Velo newsletter has just been posted to the <a href="http://www.siskiyouvelo.org">Siskiyou Velo Website</a></p>
    
    <p style="font-weight:bold">A brief look at what&rsquo;s in this issue:</p>
    <ul style="margin-top:0; padding-top:0">
    '.$item1
    .$item2
	.$item3.'
    </ul>
    
    <p><a href="http://www.siskiyouvelo.org/images/Newsletters/PDF/'.$newsRow["filename"].'">View this issue here</a>. If you have any trouble viewing the newsletter <a href="mailto:webmaster@siskiyouvelo.org">Contact Us</a> and we&rsquo;ll get look into the problem.</p>
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