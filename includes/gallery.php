<ul class="gallery_demo_unstyled">
<?php
function listFilesInDir($start_dir)
        {
        
        /*
        returns an array of files in $start_dir (not recursive)
        */
                
        $files = array();
        $dir = opendir($start_dir);
        while(($myfile = readdir($dir)) !== false)
                {
                if($myfile != '.' && $myfile != '..' && !is_file($myfile) && $myfile != 'resource.frk' && !eregi('^Icon',$myfile) )
                        {
                        $files[] = $myfile;
                        }
                }
        closedir($dir);
        return $files;
        }
?>

<?
$dir = 'images/rides/'.$images;
$images = listFilesInDir($dir);
$position = 1;
foreach($images as $key => $fileName)
        {	
		if ($position == 1) {echo "<li class='active'><img src=".$dir."/".$fileName." /></li>
		";}
        else {echo "<li><img src=".$dir."/".$fileName." /></li>
		";}
		$position +1;
        }
?>

</ul>
<div id="main_image"><center></div></center>