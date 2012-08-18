<?php
$formPost = $_POST["headlines"];
$formForum = $_POST["forum"];

if ($formPost == '') {

echo '
<h3>Music Headlines</h3>
<form style="width:1000px;" action="hc_headlines.php" method="post">

<textarea style="width:1000px; height:250px;" name="headlines" id="headlines"></textarea>

<h3>Forum Watch</h3>

<textarea style="width:1000px; height:250px;" name="forum" id="forum"></textarea>
<input type="submit" value="submit" />
</form>';
}

else {
$find = array(
			'<p>', 
			'</a> <br />',
			'</p>',
			'  ');
$replace = array(
			'<li style="margin:0;padding:5px 5px 0 8px;list-style:none;background:url(http://www.harmony-central.com/Pix/arrow1.gif) no-repeat 2px 9px;">',
			'</a><br /><span style="color: #555555; font-size: 10px;">',
			'</span></li>
',
			' ');
			
$forumFind = array(
				'<LI>',
				'</a><p>',
				'</p></LI>',
				'	');
$forumReplace = array(
				'<li style="margin:5px 10px 0 1px;padding:0 0 3px 7px;font-size:10px;background: url(http://www.harmony-central.com/Pix/arrow1.gif) no-repeat 2px 4px;">',
				'</a> <span style="color: #555555; font-size: 10px;">',
				'</span></li>',
				''
				);

$formValue = str_replace($find, $replace, $formPost);
$forumValue = str_replace($forumFind, $forumReplace, $formForum);

echo stripslashes($formValue);
echo '
<br />------------------------<br />
';
echo stripslashes($forumValue);
}

?>