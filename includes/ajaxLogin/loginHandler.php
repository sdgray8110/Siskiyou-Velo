<?php
$rootContext = '/home/gray8110/public_html/';
include($rootContext . 'includes/db_connect.php');

$enteredEmail = $_POST['memberEmail'];
$enteredPW = $_POST['memberPassword'];
$redirectPath = $_POST['redirectPath'];
$hiddenAttr = $_POST['hiddenAttr'];
$result = mysql_query("SELECT * FROM wp_users WHERE email1 = '$enteredEmail'", $connection);

if (!mysql_num_rows($result)) {
    header('location: /includes/ajaxLogin/loginForm.php?loginFailed=true');
    exit();
}

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

        if ($member['officer'] == '') {$_SESSION['SESS_TITLE'] = 'Club Member';}
        else {$_SESSION['SESS_TITLE'] = $member['officer'];}

        session_write_close();
        header('location: ' . $redirectPath . $hiddenAttr . '&memberID=' . $member['ID']);
        exit();
    } else {
        header('location: /includes/ajaxLogin/loginForm.php?loginFailed=true');
        exit();
    }
}
?>