<div id="mainNav">
<div class="pdmenu">
<ul>
<li><a href="#">Siskiyou Velo</a>
	<ul class="velo">
    <li><a href="index.php">Homepage</a></li>
    <li><a href="join.php">Join the Velo</a></li>
    <li><a href="officers.php">Officers</a></li>
    <li><a href="businesses.php">Supporting Businesses</a></li>
    <li><a href="clubstore.php">Club Store &amp; Classifieds</a></li>
    <li><a href="links.php">Links</a></li>
    <li><a href="officers.php">Contact Us</a></li>
    </ul>
</li>

<li class="menu"><a href="#">Advocacy</a>
	<ul class="advocacy">
    <li><a href="advocacy.php">Advocacy Overview</a></li>
    <li><a href="advocacy.php?pageID=Contacts">Resources &amp; Contacts</a></li>
    <li><a href="advocacy.php?pageID=Issues">Reported Local Cycling Issues</a></li>
    <li><a href="advocacy.php?pageID=Portfolio">Advocacy Portfolio</a></li>
    <li><a href="advocacy.php?pageID=Safety">Bicycle Safety Tips</a></li>
    <li><a href="advocacy.php?pageID=Construction">Construction Report</a></li>
    <li><a href="advocacy.php?pageID=Hazards">Report Cycling Hazards</a></li>
    </ul>
</li>

<li class="menu"><a href="#">Newsletters</a>
	<ul class="news">
		<?php include 'includes/newsletter.php'; ?>
    </ul>
</li>

<li class="menu"><a href="#">Rides &amp; Events</a>
	<ul class="rides">
    <li><a href="schedule.php">Current Ride Schedule </a></li>
    <li><a href="ridemaps.php">Ride Maps &amp; Descriptions</a></li>
    <!--<li><a href="#">Local Events</a></li>-->
    <li><a href="racing.php">Local Racing</a></li>
    <li><a href="http://www.mountainlakeschallenge.com">Mountain Lakes Challenge</a></li>
    </ul>
</li>

<?php
	if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
    }
    else {
    echo
'<li><a href="#">Club Members Only</a>
	<ul class="memberMenu">
    <li><a href="members.php">Membership Directory</a></li>
    <li><a href="member-profile.php">My Member Profile</a></li>
    <li><a href="members-training-education.php">Education and Skills Training</a></li>
 ';
////////////////////////////////
// Member Renewal Conditional //
////////////////////////////////
    include('includes/db_connect.php');
        $memberID = $_SESSION["SESS_MEMBER_ID"];
        $result = mysql_query("SELECT * FROM wp_users WHERE ID = '$memberID'", $connection);

    while ($row = mysql_fetch_array($result)) {
        $renewDate = $row['DateExpire'];
        $date = time();

        if (strtotime($renewDate) - $date < 7776000) {
            echo '<li><a href="member-profile.php?renewal=yes">Renew My Membership</a></li>
            ';
        }
    }

    if (time() <= 1290920400) {
        echo '
            <li><a href="eventRegistration.php?ID=3">Holiday Party Registration</a></li>
        ';
    }
////////////////////////
// End Member Renewal //
////////////////////////




echo '
    <li><a href="member_classifieds.php">Post Item to Classifieds</a></li>
    </ul>
</li>';
    }
	if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_OFFICER']) == '')) {
    }
    else {
    echo
'<li><a href="#">Officers Only</a>
	<ul class="memberMenu">
	    <li><a href="blog_post.php">Post Homepage Update </a></li>
	    <li><a href="post_schedule.php">Post Rides to Schedule</a></li>
		<li><a href="printable_schedule.php">Newsletter Schedule</a></li>
		<li><a href="news_post.php">Newsletter Upload</a></li>';
		include('includes/news_email.php');
echo '
	</ul>
</li>';
}
?>

</ul>
</div>
</div>

