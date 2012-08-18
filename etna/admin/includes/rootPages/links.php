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
$result = mysql_query("SELECT * FROM links ORDER BY name ASC", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

echo '
        <div id="mainContainer">
        
            <h1 class="title">Blog Administration</h1>
            
            <div class="mainBody">
                <div class="mainBody_top"></div>
                <h2>Add New Link</h2>

                <form id="admin">
                	<div id="subCont">
                        <label for title="title">Link Name:</label>
                        <input class="text" type="text" id="title" name="title" tabindex="1" />
                        
                        <label for title="description">Web Address: <span>Please use full URL.</span></label>
                        <input class="text" type="text" id="description" name="description" tabindex="2" />
                        
						<input class="mainSubmit" type="submit" name="submit" id="submit" value="Submit" />
				</form>						
                    </div>
                    
                    <div id="rightCont">               
                    	<h3>Delete Link(s)</h3>
                            <table class="rightBody" cellpadding="0" cellspacing="0" border="0">
							<form id="deleteLinks">';
while ($row = mysql_fetch_array($result)) {

$name = $row["name"];
$url = $row["url"];

echo '							
                                <tr valign="middle">
                                    <td class="label">
                                        <p title="' . $url . '">' . $name . '</p>
                                    </td>
                                    
                                    <td class="check">
                                        <input type="checkbox" title="' . $url . '" class="checkbox" name="' . $name . '" id="' . $name . '" value="' . $name . '" />
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
                        	<input class="submit" type="submit" name="delete" id="delete" value="Delete" />
                        </div>
                    	<div class="rightBase">                        
                        
                        </div>
                    </div>
              	</form>

                <div class="mainBody_bottom"></div>
            </div>
                        
		</div>