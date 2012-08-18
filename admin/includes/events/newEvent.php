<div>
<h2>Create A New Club Event</h2>

<form id="newEventForm" method="" action="">
    <fieldset>
        <legend>Event Details:</legend>

        <label for="eventName">Event Name:</label>
        <input id="eventName" type="text" name="eventName" />

        <label for="date">Event Date:</label>
        <input id="date" type="text" name="date" />

        <label for="time">Event Time:</label>
        <input id="time" type="text" name="time" />

        <select id="meridian" name="meridian">
            <option value="am">AM</option>
            <option selected="selected" value="pm">PM</option>
        </select>

        <label for="closingDate">Registration Closes:</label>
        <input id="closingDate" type="text" name="closingDate" />

        <label for="location">Event Location:</label>
        <input id="location" type="text" name="location" />

        <label for="description">Event Description:</label>
        <textarea id="description" name="description"></textarea>
    </fieldset>

    <div id="moreDetails">
        <fieldset class="singleEvent">
            <legend>More Details:</legend>

            <p>Does this event include multiple sub-events or rides?  <span>(Up to 5)</span></p>

            <label class="radio" for="nestedNo">Yes</label>
            <input class="radio" type="radio" id="nestedYes" name="nestedRadio" value="1" />

            <label class="radio" for="nestedNo">
                No
                <input class="radio" type="radio" id="nestedNo" name="nestedRadio" value="0" checked="checked" />
            </label>

            <label for="eventPrice">Event Price:</label>
            <input class="eventPrice" id="eventPrice" type="text" name="eventPrice" />

            <label for="eventLink">Event Link (Map, Photos etc):</label>
            <input class="eventLink" id="eventLink" type="text" name="eventLink" />

        </fieldset>
    </div>

    <div id="purchases">
        <fieldset>
            <legend>Event Merchandise <span>(T-Shirts, Jerseys etc)</span>:</legend>

            <p>Will this event offer merchandise for sale in addition to registration?  <span>(Up to 5)</span></p>

            <label class="radio" for="accYes">
                Yes
                <input class="radio" type="radio" id="accYes" name="merchandise" value="1" />
            </label>

            <label class="radio" for="accNo">
                No
                <input class="radio" type="radio" id="accNo" name="merchandise" value="0" checked="checked" />
            </label>

        </fieldset>
    </div>

    <fieldset>
        <input type="submit" id="submitNewEvent" value="Create Event" />
    </fieldset>
</form>