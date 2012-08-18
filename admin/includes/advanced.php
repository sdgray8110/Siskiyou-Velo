<div class="advSearch" style="display:none;">

<form name="advanced" id="advanced" method="post" action="">
<h5>Filters</h5>
<table class="filters" cellpadding="0" cellspacing="0">
<tr>
    <td width="120"><label for="status">Grouping:</label></td>
    <td width="120"><label for="advType">Membership Type:</label></td>
    <td width="120"><label for="advAge">Age:</label></td>
    <td><label for="vol">Volunteer:</label></td>
    <td><label for="rideLead">Ride Leader:</label></td>
    <td>
    	<label class="newMembers" for="newMembers">New Members:</label>
        <label class="newExpire" style="display:none;" for="newExpire">Recently Expired:</label>
	</td>
</tr>

<tr>
	<td>
<select name="status" id="status"">
    <option value="current">Current Membership</option>
    <option value="noEmail">Members w/o Email</option>
    <option value="full">Full History</option>
    <option value="officer">Current Officers</option>
</select>
    </td>

	<td>
<select name="advType">
	<option value="">All Types</option>
	<option value="I">Individual</option>
	<option value="F">Family</option>
	<option value="B">Business</option>            
</select>
    </td>
    
	<td>
<select name="advAge">
	<option value="">All Age Groups</option>
	<option value="18-35">18-35</option>
	<option value="36-45">36-45</option>
	<option value="46-55">46-55</option>
	<option value="56-65">56-65</option>
	<option value="66+">66+</option>
</select>
    </td>

	<td><input name="advVol" id="advVol" value="1" type="checkbox" title="Members Who Are Interested In Volunteering"/></td>
	<td><input name="rideLead" id="rideLead" value="1" type="checkbox" title="Members Who Are Interesting In Leading Rides" /></td>
	<td>
    	<input class="newMembers" name="newMembers" id="newMembers" value="1" type="checkbox" title="Members Who Have Joined Within 30 Days" />
		<input class="newExpire" name="newExpire" id="newExpire" value="1" type="checkbox" style="display:none;" title="Members Between 60 & 90 Days Past Expiration" />
	</td>
</tr>
</table>


<h5>Search</h5>

<table class="advSearch" width="600" cellpadding="0" cellspacing="0">
<tr>
<td width="175"><label for="advSearch">Search By:</label></td>
<td width="200"><label for="advSearchField">Search Text:</label></td>
<td>&nbsp;</td>
</tr>

<tr>
<td>
<select name="advSearch">
        <option value="email1">Primary Email</option>
	<option value="lastname">Last Name</option>
	<option value="firstname">First Name</option>
	<option value="city">City</option>            
</select>
</td>

<td><input class="search" type="text" name="advSearchField"></td>

<td><p>Search/Apply Filters &raquo;</p></td>
</tr>
</table>

</form>

</div>