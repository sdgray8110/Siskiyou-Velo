<div class="loginBar">

<p>
<?php 
$dow = date(l);
$month = date(F);
$date = date(d);
$year = date(Y);

echo $dow, ", ", $month, " ", $date, ", ", $year;
?>
</p>

<?php
	if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
    echo ' 
         <form id="login" name="signup" action="login/login-exec.php" method="post">
            <input type="text" id="username" tabindex="1" name="username" placeholder="Email Address">

            <input type="password" id="password" tabindex="2" name="password" placeholder="Password">
            <input class="submit" type="submit" tabindex="3" alt="Submit" name="submit" value="Login &raquo;"/>
          </form>
          ';

    }

	elseif (trim($_SESSION['SESS_OFFICER']) == 'Webmaster') {
		$cdate = mktime(0, 0, 0, 2, 9, 2009, 0);
		$today = time();
		$difference = $cdate - $today;
		if ($difference < 0) { $difference = 0; }

		echo '<p style="float:right;">You are logged in as: ' . $_SESSION['SESS_FIRST_NAME']  . ' | <a href="logout.php">Logout</a> | <a href="member-profile.php">Member Profile</a> | <span title="Complete rollover to new site deadline &amp; goal for STRICT diet observance">'. floor($difference/60/60/24).' days remaining</span></p>';
	}

    else {
    	echo '<p style="float:right;">You are logged in as: ' . $_SESSION['SESS_FIRST_NAME']  . ' | <a href="logout.php">Logout</a> | <a href="member-profile.php">Member Profile</a></p>';    
    }
?>

</div>

	<div id="header">
        <h1>Siskiyou Velo</h1>
        <img class="slogan" src="images/slogan.png" width="289" height="40" border="0" alt="Southern Oregon&rsquo;s Premier Bicycle Club" />
    </div>
