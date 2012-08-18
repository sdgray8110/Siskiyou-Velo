<?php
$loginFail = $_GET['loginFailed'];

if ($loginFail == 'true') {
    $header = 'Login Failed';
    $pwVisibility = 'passwordOpen';
    $instructions = '<p>If you&rsquo;ve forgotten your password, please enter your email address in the forgot password form below and you&rsquo;ll receive an email with instructions to reset your password.</p>';
} else {
    $header = 'Please login to continue:';
    $pwVisibility = 'passwordClosed';
}

echo '
<h2>'.$header.'</h2>
<form id="ajaxLogin" method="" action="">
    <div class="globalFormContent">
        '.$instructions.'
        <label for="memberEmail">Email Address:</label>
        <input type="text" id="memberEmail" name="memberEmail" />

        <label for="memberPassword">Password:</label>
        <input type="password" id="memberPassword" name="memberPassword" />

        <input type="submit" id="ajaxLoginSubmit" class="submit" name="submit" value="login" />
        <input type="hidden" name="hiddenAttr" id="hiddenAttr" value="'.$_SESSION['SESS_UNIQUE'].'" />

        <p class="'.$pwVisibility.'"><a href="#" class="'.$pwVisibility.'" id="forgotPassword">Forget Your Password?</a></p>
    </div>
</form>

<div class="'.$pwVisibility.'">
    <h2>Forget Your Password?</h2>
    <form id="forgotPW" name="pw_reset" action="/pw_retrieval.php" method="post"">
        <div class="globalFormContent">
            <label for "email">Email Address</label>
            <input type="text" id="email" name="email">

            <input class="submit" type="submit" alt="Submit" name="submit" value="Reset Password"/>
        </div>
    </form>
</div>
';

?>