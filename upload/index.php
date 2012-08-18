<?php if (!$_GET['uploaded']) { ?>

<form id="profile" action="file_upload.php" enctype="multipart/form-data" action="includes/file_upload.php" method="POST">
    <label for title="title">Choose File:</label>
    <input style="background:#fff;" class="required" type="file" id="uploaded" name="uploaded" tabindex="1" />
    <input class="submit" type="submit" name="upload" id="upload" value="Upload File" />

</form>
    
<?php } else { ?>
    
<p>Your file has been uploaded.</p>
    
<? } ?>