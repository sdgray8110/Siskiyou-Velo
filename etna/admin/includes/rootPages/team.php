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

        <div id="mainContainer">
        
            <h1 class="title">Blog Administration</h1>
            
            <div class="mainBody">
                <div class="mainBody_top"></div>
                <h2>Edit Your Profile</h2>
                
<?php
$memberID = $_SESSION['SESS_MEMBER_ID'];
$result = mysql_query("SELECT * FROM users WHERE ID = $memberID", $connection);

if (!result) {
   die("Database query failed: " . mysql_error());
}

while ($row = mysql_fetch_array($result)) {
$firstname = $row["firstname"];
$lastname = $row["lastname"];
$email = $row["email"];
$city = $row["city"];
$road = $row["road"];
$mtn = $row["mtn"];
$cx = $row["cx"];
$obra = $row["obra"];
$uscf = $row["uscf"];

echo '                

                <form id="profile" name="profile" action="includes/team_update.php" method="post" onSubmit="return validate();">
                	<div id="subCont">
                        <label for title="title">First Name:</label>
                        <input class="required" type="text" id="firstname" name="firstname" value="'.$firstname.'" tabindex="1" />
                        
                        <label for title="description">Lastname:</label>
                        <input class="required" type="text" id="lastname" name="lastname" value="'.$lastname.'" tabindex="2" />
                        
                        <label for title="title">Email Address:</label>
                        <input class="required email" type="text" id="email" name="email" value="'.$email.'" tabindex="3" />
                        
                        <label for title="title">City, State:</label>
                        <input class="required" type="text" id="city" name="city" value="'.$city.'" tabindex="3" />                     

                    </div>
                    
                    <div id="rightCont">
                       	<h3>Racing Details</h3>
                            <table class="rightBody" cellpadding="0" cellspacing="0" border="0">
                                <tr valign="middle">
                                    <td class="label">
                                        <p>Road Category:</p>
                                    </td>
                                    
                                    <td class="check">
										<input class="racing" type="text" name="road" id="road" value="'.$road.'" />
                                    </td>
                                </tr>
                                    
                                <tr valign="middle">
                                    <td class="label">
                                        <p>Mountain Category:</p>
                                    </td>
                                    
                                    <td class="check">
										<input class="racing" type="text" name="mtn" id="mtn" value="'.$mtn.'" />
                                    </td>
                                </tr>
                                
                                <tr valign="middle">
                                    <td class="label">
                                        <p>CX Category:</p>
                                    </td>
                                    
                                    <td class="check">
										<input class="racing" type="text" name="cx" id="cx" value="'.$cx.'" />
                                    </td>
                                </tr>
                                
                                <tr valign="middle">
                                    <td class="label">
                                        <p>OBRA ID:</p>
                                    </td>
                                    
                                    <td class="check">
										<input class="racing" type="text" name="obra" id="obra" value="'.$obra.'" />
                                    </td>
                                </tr>
                                
                                <tr valign="middle">
                                    <td class="label">
                                        <p>USCF License:</p>
                                    </td>
                                    
                                    <td class="check">
										<input class="racing" type="text" name="uscf" id="uscf" value="'.$uscf.'" />
                                    </td>
                                </tr>                                                                                                
                                    
                            </table>	                            
                    	<div class="rightBase_body">
                        	<input class="submit" type="submit" name="publish" id="publish" value="Publish" />
                        </div>
                    	<div class="rightBase">                      
';
}
?>                        
                        </div>
                    </div>
              	</form>

                <div class="mainBody_bottom"></div>
            </div>
                        
		</div>
        
<?php
//Close connection
mysql_close($connection);

?>          