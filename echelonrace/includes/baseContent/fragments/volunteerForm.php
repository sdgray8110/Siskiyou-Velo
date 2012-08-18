<?php
echo '
<h3>Interested In Volunteering? We can always use more hands!</h3>

<form id="'.$pageData[0].'" method="post" action="'.$formHandlers.$pageData[1].'.php">
<p>Echelon Events LLC. is seeking volunteers to help us with our bike races. Please fill in the information below to indicate how you would like to become involved.</p>

<fieldset class="blockTextarea">
    <label for="username">Name</label>
    <input type="text" id="username" name="username" value="" />

    <label for="address1">Address Line 1</label>
    <input type="text" id="address1" name="address1" value="" />

    <label for="address2">Address Line 2</label>
    <input type="text" id="address2" name="address2" value="" />

    <label for="city">City</label>
    <input type="text" id="city" name="city" value="" />

    <label for="state">State</label>
    <input type="text" id="state" name="state" value="" />

    <label for="zip">Postal/ZIP Code</label>
    <input type="text" id="zip" name="zip" value="" />

    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone" value="" />

    <label for="email">Email Address</label>
    <input type="text" id="email" name="email" value="" />

    <label for="contactTime">Best Time to Contact</label>
    <select id="contactTime" name="contactTime">
        <option value="Morning">Morning</option>
        <option value="Afternoon">Afternoon</option>
        <option value="Evening">Evening</option>
    </select>
</fieldset>

<h5>Have you previously done volunteer work for Echelon Events LLC.</h5>
<fieldset class="multiInlineRadio">
<label for="prevYes">Yes</label>
<input type="radio" id="prevYes" name="previousEvents" value="yes" />

<label for="prevNo">No</label>
<input type="radio" id="prevNo" name="previousEvents" value="no" />
</fieldset>

<h5>Where did you find out about this opportunity?</h5>
<fieldset class="inlineBlockRadio">
<label for="howFlyer">Flyer or posting</label>
<input type="radio" id="howFlyer" name="howLearned" value="flyer" />

<label for="howFriend">Friend or family</label>
<input type="radio" id="howFriend" name="howLearned" value="friend" />

<label for="howNews">Newspaper advertisement</label>
<input type="radio" id="howNews" name="howLearned" value="news" />

<label for="howInquiry">Personal inquiry</label>
<input type="radio" id="howInquiry" name="howLearned" value="Personal inquiry" />

<label for="howWeb">Website advertisement</label>
<input type="radio" id="howWeb" name="howLearned" value="Website advertisement" />

<label for="howOther">Other</label>
<input type="radio" id="howOther" name="howLearned" value="Other" />
</fieldset>

<h5>What days of the week are you available?</h5>
<fieldset class="inlineBlockRadio">
<label for="daysSunday">Sunday</label>
<input type="checkbox" id="daysSunday" name="daysSunday" value="Sunday" />

<label for="daysMonday">Monday</label>
<input type="checkbox" id="daysMonday" name="daysMonday" value="Monday" />

<label for="daysTuesday">Tuesday</label>
<input type="checkbox" id="daysTuesday" name="daysTuesday" value="Tuesday" />

<label for="daysWednesday">Wednesday</label>
<input type="checkbox" id="daysWednesday" name="daysWednesday" value="Wednesday" />

<label for="daysThursday">Thursday</label>
<input type="checkbox" id="daysThursday" name="daysThursday" value="Thursday" />

<label for="daysFriday">Friday</label>
<input type="checkbox" id="daysFriday" name="daysFriday" value="Friday" />

<label for="daysSaturday">Saturday</label>
<input type="checkbox" id="daysSaturday" name="daysSaturday" value="Saturday" />
</fieldset>

<h5>What areas of work would you be interested in (check all that apply)?</h5>
<fieldset class="inlineBlockRadio">
<label for="workStart">Start/Finish Line</label>
<input type="checkbox" id="workStart" name="workStart" value="Start/Finish Line" />

<label for="workRegistration">Registration</label>
<input type="checkbox" id="workRegistration" name="workRegistration" value="Registration" />

<label for="workSetup">Course Set-Up/Take-Down</label>
<input type="checkbox" id="workSetup" name="workSetup" value="Course Set-Up/Take-Down" />

<label for="workSponsor">Fund Raising/Sponsoring</label>
<input type="checkbox" id="workSponsor" name="workSponsor" value="Fund Raising/Sponsoring" />

<label for="workTrail">Trail Work/Maintenance</label>
<input type="checkbox" id="workTrail" name="workTrail" value="Trail Work/Maintenance" />

<label for="workStaff">Support Staff</label>
<input type="checkbox" id="workStaff" name="workStaff" value="Support Staff" />

<label for="workOfficial">Course Marshall/Official</label>
<input type="checkbox" id="workOfficial" name="workOfficial" value="Course Marshall/Official" />
</fieldset>

<fieldset class="blockTextarea">
<label for="comments">Comments/Additional Information</label>
<textarea id="comments" name="comments"></textarea>
</fieldset>

<input type="submit" id="volunteerSubmit" name="volunteerSubmit" value="Send Information" />

</form>
'
?>