        <div id="mainContainer">
        
            <h1 class="title">Blog Administration</h1>
            
            <div class="mainBody">
                <div class="mainBody_top"></div>
                <h2>Add New Post</h2>

                <form id="profile" name="profile" action="includes/post_exec.php" method="post" onSubmit="return validate();">
                	<div id="subCont">
                        <label for title="title">Title:</label>
                        <input class="required" type="text" id="title" name="title" maxlength="31" tabindex="1" />
                        
                        <label for title="description">Description:</label>
                        <input class="required" type="text" id="description" name="description" tabindex="2" />
                        
                        <label for title="title">Keywords/Tags:<span> Separate tags with commas</span></label>
                        <input class="required" type="text" id="tags" name="tags" tabindex="3" />
                        
                        <label for title="title">Blog Post:<span> Images should be no wider than 480 pixels.</span></label>                        
						<div class="fck"><?php
                        $FCKeditor = new FCKeditor('FCKeditor1') ;
                        $FCKeditor->BasePath = '/fckeditor/' ;
						$FCKeditor->Height = '450px' ;
                        $FCKeditor->Create() ;
                        ?></div>
                    </div>
                    
                    <div id="rightCont">
                    	<h3>Categories</h3>
                            <table class="rightBody" cellpadding="0" cellspacing="0" border="0">
<?php 

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
$result = mysql_query("SELECT DISTINCT category FROM posts WHERE category != ''", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {

$category = $row["category"];
echo '
                                <tr valign="middle">
                                    <td class="label">
                                        <p>' . $category . ':</p>
                                    </td>
                                    
                                    <td class="check">
                                       <input type="radio" class="checkbox" name="category" id="news" value="' . $category . '" />
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
                        	<p>Custom Category: <input class="category" type="text" name="custom_cat" id="custom_cat" /></p>
                        </div>
                    	<div class="rightBase">                        
                        
                        </div>                    

                   
                    	<h3>Publish</h3>
                            <table class="rightBody" cellpadding="0" cellspacing="0" border="0">
                                <tr valign="middle">
                                    <td class="label">
                                        <p>Publish as Draft:</p>
                                    </td>
                                    
                                    <td class="check">
                                        <input type="checkbox" class="checkbox" name="draft" id="draft" value="1" />
                                    </td>
                                </tr>
<!--                                    
                                <tr valign="middle">
                                    <td class="label">
                                        <p>Team Blog:</p>
                                    </td>
                                    
                                    <td class="check">
                                        <input type="checkbox" class="checkbox" name="teamblog" id="teamblog" value="teamblog" /></p> 
                                    </td>
                                </tr>
-->                                    
                            </table>	                            
                    	<div class="rightBase_body">
                        	<input class="submit" type="submit" name="publish" id="publish" value="Publish" />
                        </div>
                    	<div class="rightBase">                        
                        
                        </div>
                    </div>
              	</form>

                <div class="mainBody_bottom"></div>
            </div>
                        
		</div>