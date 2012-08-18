<script type="text/javascript">
$(document).ready(function() {
	$('.emailSent').slideDown(1000);
	$('.emailSent').click(function() {
		$('.emailSent').slideUp(500);
	});

	$('.mainNav li').click(function() {
		$('.emailSent').hide();
	});	
});
</script>

<?php
echo '<div class="emailSent">
<h5>Email Sent at '.date('g:ia').'</h5>
<p>Click to close</p>
</div>
';
?>
