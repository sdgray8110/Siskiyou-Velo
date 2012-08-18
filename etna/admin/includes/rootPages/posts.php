<?php 

function shortenText($text) {
$chars = 100;
$text = $text." ";
$text = substr($text,0,$chars);
$text = substr($text,0,strrpos($text,' '));
if (substr($text, -4) == '</p>') {
		$text = substr_replace($text, '', -4) . ' ...</p>';
}

elseif (substr($text, -4) == '</li>') {
		$text = substr_replace($text, '', -4) . ' ...</li>';
}		

else {
$text = $text . ' ...';
}

return $text;
}

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!db_select) {
	die("Database selection failed: " . mysql_error());
} 
?>

<?php
$result = mysql_query("SELECT * FROM posts ORDER BY ID DESC LIMIT 15", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

echo '
        <div id="mainContainer">
        
            <h1 class="title">Blog Administration</h1>
            
            <div class="mainBody">
                <div class="mainBody_top"></div>
                <h2>Edit Post</h2>
                	<div id="subCont">
					<ul class="postList">';

while ($row = mysql_fetch_array($result)) {
$title = $row["title"];
$postID = $row["ID"];
$description = shortenText($row["post"]);
$author = $row["username"];
$tags = array("<center>","</center>");
$replace = array("","");
$description = str_replace($tags, $replace, $description);
echo '
						<li>
							<h3><a href="/admin/?pageID=editpost&entry='.$postID.'">'.$title.'</a></h3>
							<p><strong>Author</strong>: '.$author.'</p>
							'.$description.'</p>
						</li>

';

}			
echo'       
					</ul>    
			</div>
                    
                    <div id="rightCont">               
                    	<h3>Delete Post(s)</h3>
                            <table class="rightBody" cellpadding="0" cellspacing="0" border="0">
							<form id="deleteLinks" name="deleteLinks" method="post" action="includes/post_edit_exec.php">';
$result1 = mysql_query("SELECT * FROM posts ORDER BY ID DESC LIMIT 15", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}							
while ($row1 = mysql_fetch_array($result1)) {

$title1 = $row1["title"];
$postID1 = $row1["ID"];

echo '							
                                <tr valign="middle">
                                    <td class="label">
                                        <p>' . $title1 . '</p>
                                    </td>
                                    
                                    <td class="check">
                                        <input type="checkbox" class="checkbox" name="delete" id="delete" value="' . $postID1 . '" />
                                    </td>
                                </tr>';

}
?>

<?php
//Close connection
mysql_close($connection);

?>                                    
                            </table>	                            
                    	<div class="rightBase_body">
                        	<input class="submit" type="submit" name="delete_button" id="delete_button" value="Delete" />
                        </div>
                    	<div class="rightBase">                        
                        
                        </div>
                    </div>
              	</form>

                <div class="mainBody_bottom"></div>
            </div>
                        
		</div>