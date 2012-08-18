<?php
$disciplines = array('Road Race:Road','Stage Race/Omnium:Stage','Time Trial:Time Trial','Criterium:Criterium','Mountain Bike:Mountain Bike','Downhill:Downhill','Cyclocross:Cyclocross','Non-OBRA:Non-OBRA');

echo '

        <div id="mainContainer">

            <h1 class="title">Blog Administration</h1>

            <div class="mainBody">
                <div class="mainBody_top"></div>
                <h2>Add New Race/Event</h2>

                <form id="profile" name="profile" action="includes/new_event_post.php" method="post" onSubmit="return validate();">
                	<div id="subCont">
                        <label for title="title">Race/Event Name:</label>
                        <input class="required" type="text" id="race" name="race" tabindex="1" />

                        <label for title="description">Date:<span> Format MM/DD/YY</span></label>
                        <input class="required" type="text" id="date" name="date" tabindex="2" />

                        <label for title="title">Website:</label>
                        <input class="url" type="text" id="website" name="website" tabindex="3" />
                    </div>

                    <div id="rightCont">
                    	<h3>Event Type</h3>
                            <table class="rightBody" cellpadding="0" cellspacing="0" border="0">
';

                        foreach($disciplines as $discipline) {
                            $discipline = explode(':', $discipline);
                            echo '
                                <tr valign="middle">
                                    <td class="label">
                                        <p>'.$discipline[0].':</p>
                                    </td>

                                    <td class="check">
                                       <input type="radio" class="checkbox" name="discipline" id="discipline" value="'.$discipline[1].'" />
                                    </td>
                                </tr>
                            ';
                        }

echo '
                            </table>
                    	<div class="rightBase_body">
                       		<input class="submit" type="submit" name="publish" id="publish" value="Publish" />
                        </div>
                    	<div class="rightBase"></div>
                    </div>
              	</form>

                <div class="mainBody_bottom"></div>
            </div>

		</div>
';