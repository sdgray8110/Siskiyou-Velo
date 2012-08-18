<h1>Notify the Siskiyou Velo of local cycling hazards</h1>
<p style="margin-bottom:10px; padding-bottom:10px; border-bottom:1px dotted #ccc;">If you have encountered a hazard cycling in the region that you would like the Siskiyou Velo to be aware of, please use the form below to notify us. We can't guarantee a resolution on every problem, but all concerns will be researched and reported to the appropriate authorities.</p>
    
<form id='profile' name='profile' action='includes/formHandlers/hazards.php' method='post' onSubmit='return validate();'>

<dl><dt><label for='name'>Name:</label></dt>
<dd><input type='text' class='required'  minlength='2' name='name' id='name' /></dd></dl>

<dl><dt><label for='honeypot'>Email Address:</label></dt>
<dd><input type='text' class='required email' name='honeypot' id='honeypot' /></dd></dl>

<dl style="display:none;"><dt><label for='email'>Email Address:</label></dt>
<dd><input type='text' name='email' id='email' /></dd></dl>

<dl><dt><label for='hazard'>Type of Hazard:</label></dt>
    <dd>
        <select name="hazard" id="hazard" class="hazardsselect">
            <option value="General Road Hazard" selected="selected">General Road Hazard</option>
            <option value="Dangerous Intersection">Dangerous Intersection</option>
            <option value="Poorly Designed Roadway">Poorly Designed Roadway</option>
            <option value="Road Repairs Needed">Road Repairs Needed</option>
            <option value="Excessive Loose Gravel">Excessive Loose Gravel</option>
            <option value="Dangerous Driver">Dangerous Driver</option>
            <option value="Other - See Description">Other - Please Describe Below</option>
        </select>
    </dd>
</dl>

<dl><dt><label for='comments'>Describe the Cycling Hazard:</label></dt>
<dt><textarea name="comments" id="comments" class="required"></textarea></dt></dl>

<dl><dt><input class='submit' type='submit' alt='Submit' name='submit' value='Submit'/></dt></dl>
</form>