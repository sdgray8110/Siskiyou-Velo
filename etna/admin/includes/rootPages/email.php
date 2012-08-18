<?php 
$addy = $_SESSION['SESS_EMAIL'];
$addyname = $_SESSION["SESS_FIRST_NAME"] . ' ' . $_SESSION["SESS_LAST_NAME"];
$from = $addyname.' <'.$addy.'>';

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
$result = mysql_query("SELECT * FROM users WHERE email != '' ORDER BY lastname ASC", $connection);

if (!$result) {
   die("Database query failed: " . mysql_error());
}
?>

        <div id="mainContainer">
        
            <h1 class="title">Blog Administration</h1>
            
            <div class="mainBody">
                <div class="mainBody_top"></div>
                <h2>Email Team Member(s)</h2>

                <form id="profile" name="profile" action="includes/send_email.php" method="post" onSubmit="return validate();">
                	<div id="subCont">
                        <label for subject="subject">Subject:</label>
                        <input class="required" type="text" id="subject" name="subject" tabindex="1" /> 
                        
                        <label for emailbody="emailbody">Email Body:</label>                        
						<div class="fck"><?php
                        $FCKeditor = new FCKeditor('FCKeditor1') ;
                        $FCKeditor->BasePath = '/fckeditor/' ;
						$FCKeditor->Height = '450px' ;
                        $FCKeditor->Create() ;
                        ?></div>
                        
						<input class="mainSubmit" type="submit" name="submit" id="submit" value="Send Email" />
                    </div>

<script>
$(document).ready(function() {
    $("#select_all_col_managers").click(function() {
        $("#col_manager_list").each(function(){
            $("#col_manager_list option").attr("selected","selected"); });
    }) ;

    $("#unselect_all_col_managers").click(function() {
        $("#col_manager_list").each(function(){
            $("#col_manager_list option").removeAttr("selected"); });
    }) ;

});                    
</script>
                    <div id="rightCont">               
                    	<h3>Choose Recipient(s):</h3>
                            <table class="rightBody" cellpadding="0" cellspacing="0" border="0">
                                <tr valign="middle">
                                    <td class="check">
                                    <p>Control + Click to select multiple riders.</p>
                                    <select class="select_multiple" id="col_manager_list" multiple="multiple" name="email[]">
                                    <?php
                                    while ($row = mysql_fetch_array($result)) {
                                    $riderID = $row["id"];
                                    $name = $row["firstname"] . ' ' . $row["lastname"];
                                    $email = $row["firstname"] . ' ' . $row["lastname"] .'<' .$row["email"]. '>';
                                    
                                    echo' 
									<option id="email" value="'.$email.'">'.$name.'</option>';
}
echo
									'</select></td></tr></table>
                    	<div class="rightBase_body">
                        	<button type="button" class="submit" id="select_all_col_managers">Select All</button>
                        </div>
                    	<div class="rightBase">                        
                        
                        </div>
                    </div>
                    <input type="hidden" name="from" value="'.$from.'" />
              	</form>

                <div class="mainBody_bottom"></div>
            </div>
		</div>';
?>		