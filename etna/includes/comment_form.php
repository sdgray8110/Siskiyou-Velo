<form style="clear:both;" id="profile" name="profile" action="includes/comments_post.php" method="post" onSubmit="return validate();">

<p>
<input type="text" class="required" minlength="2" name="author" id="author" value="" size="22" tabindex="1" />

<label for="author"><small>Name </small></label></p>

<p><input type="text" name="email" id="email" class="required email" size="22" tabindex="2" />
<label for="email"><small>Email (will not be published) 
</small></label>
</p>

<p><input type="text" name="url" id="url" class="url" value="" size="22" tabindex="3" />
<label for="url"><small>Website</small></label></p>

<p><textarea name="comment" id="comment" cols="50" rows="10" tabindex="4"></textarea></p>

<p><input name="submit" type="submit" id="submit" tabindex="5" value="Submit" />

</p>

</form>
