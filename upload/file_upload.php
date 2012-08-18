<?php
$target = $_SERVER['DOCUMENT_ROOT'] . "/upload/files/";
$filename = basename( $_FILES['uploaded']['name']) ;
$target = $target . $filename;
$ok=1;

//This is our size condition
if ($uploaded_size > 100000000)
{
echo "Your file is too large.<br>";
$ok=0;
}

//This is our limit file type condition
if ($uploaded_type =="text/php")
{
echo "No PHP files<br>";
$ok=0;
}

//Here we check that $ok was not set to 0 by an error
if ($ok==0)
{
Echo "Sorry your file was not uploaded";
}

//If everything is ok we try to upload it
else
{
    echo 'file uploaded to: <br /> http://www.siskiyouvelo.org/upload/files/' .  $filename;

if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
{
    header("location: ?uploaded=true");
    exit();
}
else { ?>

<p>Sorry, there was a problem uploading your file.</p>

    <?php }
}

?>