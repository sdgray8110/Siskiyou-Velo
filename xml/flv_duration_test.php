<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>
<body>

<?php

$file = "http://img3.musiciansfriend.com/dbase/media/rmTest/test.flv";

function mbmGetFLVDuration($file){
	// read file
		$handle = fopen($file, "rb");
		$contents = '';
		while (!feof($handle)) {
		$contents .= fread($handle, 8192);
		}
		fclose($handle);

		if (strlen($contents) > 3){
			if (substr($contents,0,3) == "FLV"){
				$taglen = hexdec(bin2hex(substr($contents,strlen($contents)-3)));
				if (strlen($contents) > $taglen){
					$duration = hexdec(bin2hex(substr($contents,strlen($contents)-$taglen,3)));
					return $duration;
				}
			}
		}
	
	return false;
}


	echo mbmGetFLVDuration($file);

?>

</body>
</html>
