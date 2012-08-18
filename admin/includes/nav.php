<?php 
echo '
<ul class="mainNav">
	<li class="emailNav">Club Email</li>    
	<li class="memberNav">Membership Administration</li>
    <li class="eventsNav">Events Manager</li>
    <li class="rideNav">Ride Schedule</li>
    <li class="newsNav">Newsletter</li>
    <li class="blogNav">Blog</li>  
	<span class="status">'.$status.'<strong>'.$name.'</strong></span>
</ul>
';

if ($_SESSION['SESS_OFFICER'] == '') {
	include('login-form.php');
}
else {
	
if ($_GET['email'] == 'sent') {
	include('email_success.php');
}

include('member_dialog.php');

echo '
<ul id="emailNav" class="subNav">
	<h2>Choose Your Email Group &raquo; </h2>
	<li id="members60">Current Members</li>
    <li id="full">Full History</li>
    <li id="officers">Officers</li>
    <li id="mlc">Past MLC Participants</li>
</ul>

<ul id="memberNav" class="subNav">
	<h2>Choose Your Membership Application &raquo; </h2>
	<li id="add_tab">Add New Member</li>
    <li id="fullList_tab">Full History</li>
    <!--<li id="pendingActivation">Pending Activation</li>-->
    <li id="members60_tab">Current Membership</li>
    <li id="search_tab">Advanced Search/Filter</li>    
</ul>

<ul id="eventsNav" class="subNav">
	<h2>Choose Your Event Manager &raquo; </h2>
	<li>Meeting Schedule</li>
    <li id="events">Active Events</li>
    <li id="newEvent">Create New Event</li>	
</ul>

<ul id="rideNav" class="subNav">
	<h2>Choose Ride Editor &raquo; </h2>
	<li>Post New Ride(s)</li>
    <li>Edit/Delete Ride</li>
</ul>

<ul id="newsNav" class="subNav">
	<h2>Choose Newsletter Function &raquo; </h2>
	<li>Upload Newsletter</li>
    <li>Send Notification Email</li>
    <li>Retrieve Ride Schedule</li>
</ul>

<ul id="blogNav" class="subNav">
	<h2>Choose Blog Function &raquo; </h2>
	<li>New Homepage Blog Post</li>
    <li>Edit/Delete Blog Post</li>
    <li>Moderate Comments</li>
</ul>
';

include('includes/advanced.php');

}
?>