<?php
include('includes/nextMeeting.php');

echo '
<li>
	<h3>' . nextMeeting('monthly') . '</h3>
    <p>Monthly Club Meeting</p>
    <p><strong>7pm</strong>: Meet at 
    <!--<a target="_blank" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=3846+S+Pacific+Hwy,+Medford,+OR+97501&sll=42.284421,-122.831569&sspn=0.022066,0.038624&ie=UTF8&z=16">D&amp;S Harley Davidson in Phoenix</a>-->
    <a target="_blank"  href="http://maps.google.com/maps?saddr=Unknown+road&hl=en&sll=42.27253,-122.811495&sspn=0.007089,0.016512&geocode=FTAHhQIdzAqu-A&t=h&mra=ls&z=17">Blue Heron Park</a> in Phoenix
    </p>
</li>
';

?>