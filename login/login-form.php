<?php 

if ($_GET['pw'] != 'reset') {
echo '
<form style="margin:10px 0 0 0;" id="validate" name="signup" action="login/login-exec.php" method="post">
<dl>
<dt><label for "username">Username</label></dt>
<dd><input type="text" id="username" tabindex="4" name="username"></dd>
<dt><label for "Password">Password</label></dt>
<dd><input type="text" id="password" tabindex="5" name="password"></dd>
</dl>
<input class="submit" type="submit" tabindex="6" alt="Submit" name="submit" value="Login &raquo;"/>

</form>

<hr noshade="noshade" color="#CCCCCC" size="1" />';
}

echo '
<p><strong>Forget Your Password?</strong><br />If you&rsquo;ve forgotten your password, enter your email address in the form below and we&rsquo;ll email you with instructions to reset it.</p>

<form style="margin:10px 0 0 0;" id="profile" name="pw_reset" action="pw_retrieval.php" method="post" onSubmit="return validate();">
<dl>
<dt><label for "email">Email Address</label></dt>
<dd><input type="text" class="required email" id="email" tabindex="7" name="email"></dd>
</dl>
<input class="submit" type="submit" tabindex="8" alt="Submit" name="submit" value="Reset Password &raquo;"/>

</form>
';
?>