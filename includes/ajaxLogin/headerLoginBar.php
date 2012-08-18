<?php
if ($_GET['ajax'] === 'true') {
    $ajaxClass = ' hidden';
}

$noSession = (!isset($_SESSION['SESS_MEMBER_ID']) && !$ajaxClass);
$invalidSession = (trim($_SESSION['SESS_MEMBER_ID']) == '' && !$ajaxClass);

if (!$ajaxClass) {
    $loginName = 'as: ' . $_SESSION['SESS_FIRST_NAME'];
} else {
    $loginName = ':';
}

if ($noSession || $invalidSession) {
    echo '
         <form id="login" name="signup" action="login/login-exec.php" method="post">
            <input type="text" id="username" tabindex="1" name="username" value="Email Address" ONFOCUS="clearDefault(this)">
            <input type="text" id="password" tabindex="2" name="password" value="Password" ONFOCUS="clearDefault(this)">
            <input class="submit" type="submit" tabindex="3" alt="Submit" name="submit" value="Login &raquo;"/>
          </form>
          ';

    }

    else {
    	echo '<p class="loggedIn'.$ajaxClass.'">You are logged in' . $loginName . ' | <a href="logout.php">Logout</a> | <a href="member-profile.php">Member Profile</a></p>';
    }
?>