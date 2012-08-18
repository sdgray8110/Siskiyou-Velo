<?php
$target = "/home/gray8110/public_html/images/Newsletters/PDF/";
$target = $target . basename( $_FILES['uploaded']['name']) ;
$ok=1;

$inTwoMonths = 60 * 60 * 24 * 60 + time(); 
setcookie("fileName", basename( $_FILES['uploaded']['name']), time()+3600, "/", ".siskiyouvelo.org");

//This is our size condition
if ($uploaded_size > 10000000)
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
if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target))
{

//Calculate 60 days in the future
//seconds * minutes * hours * days + current time

			session_write_close();
			header("location: ../post_news_briefs.php");
			exit();
}
else
{
echo "Sorry, there was a problem uploading your file.";
}
}
?>