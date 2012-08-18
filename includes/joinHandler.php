<?php
$rootContext = '/home/gray8110/public_html/';
include($rootContext . 'includes/db_connect.php');
$pageRef = $_POST['pageRef'];
$emailSearch = $_POST['checkForEmail'];


// Check For Existing Account //
if ($pageRef == 'checkEmail') {
    $result = mysql_query("SELECT * FROM wp_users WHERE email1 = '$emailSearch'", $connection);
    $rowCount = mysql_num_rows($result);

    if ($rowCount != 0) {
        while ($row = mysql_fetch_array($result)) {
            echo '

                <h1>Join The Siskiyou Velo Bicycle Club Today</h1>
                <h2 class="memberDetails">We found an existing account account tied to this email address</h2>
                <ul class="memberDetails join">
                    <li>
                        <p class="ident">Account Name:</p>
                        <p>'.$row[firstname].' '.$row[lastname].'</p>
                    </li>

                    <li>
                        <p class="ident">Primary Email Address: </p>
                        <p>'.$row[email1].'</p>
                    </li>
                </ul>

                <form id="passwordConfirm" name="passwordConfirmTest" action="../includes/joinHandler.php" method="post">
                    <p>If this account belongs to you, please verify your password to renew the account. If you are unsure of your password, <a href="mailto:webmaster@siskiyouvelo.org">Contact Our Webmaster</a></p>
                    <label for="">Password:</label>
                    <input type="password" id="joinPasswordConfirm" name="joinPasswordConfirm" />
                    <input type="hidden" name="email" value="'.$row[email1].'" />
                    <input type="hidden" name="pageRef" value="checkPassword" />
                    <input type="submit" class="submit" value="Continue" id="nextStepConfirmPassword" />
                </form>

                <div>
                    <form id="joinForm" name="joinEmailTest" action="">
                        <p>If you would like to join using another email address, please enter your email address below:</p>
                        <label for="">Email Address:</label>
                        <input type="text" id="joinEmail" name="checkForEmail" />
                        <input type="hidden" name="pageRef" value="checkEmail" />
                        <input type="submit" class="submit" value="Join" id="nextStep" />
                    </form>
                </div>
            ';
        }
    }

    else {
        header("location: ../member-profile1.php?email=".$emailSearch);
        exit();
    }
}

// If Existing Account verify password is correct and redirect to renewal page //
else if ($pageRef == 'checkPassword') {
    $enteredEmail = $_POST['email'];
    $enteredPW = $_POST['joinPasswordConfirm'];
    $result = mysql_query("SELECT * FROM wp_users WHERE email1 = '$enteredEmail'", $connection);

    while ($member = mysql_fetch_array($result)) {
        if (md5($enteredPW) == $member['user_pass']) {
            //Login Successful
            session_start();
            session_regenerate_id();
			$_SESSION['SESS_MEMBER_ID'] = $member['ID'];
			$_SESSION['SESS_FIRST_NAME'] = $member['firstname'];
			$_SESSION['SESS_LAST_NAME'] = $member['lastname'];
			$_SESSION['SESS_EMAIL'] = $member['email1'];
			$_SESSION['SESS_OFFICER'] = $member['officer'];
            $_SESSION['SESS_TITLE'] = 'Club Member' ? $member['officer'] == '' : $member['officer'] == '';

            session_write_close();

            header("location: ../member-profile.php?renewal=yes&expired=ok");
            exit();            
        }
        else {
			//Login failed
			header("location: ../login-failed.php");
			exit();
        }
    }
}
?>