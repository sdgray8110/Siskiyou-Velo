<?php

include('fireReports.php');

echo '
<ul>
    <li><a href="rawMemberData.csv">Full Current Membership | Raw Data</a></li>
    <li><a href="fullMemberHistory.csv">Full Member History</a></li>
    <li><a href="recentExpirations.csv">Recently Expired</a></li>
    <li><a href="longTermExpirations.csv">Long Term Expired (within last 6 months)</a></li>
    <li><a href="recentJoin.csv">Recently Joined</a></li>
    <li><a href="recentRenewals.csv">Recently Renewed</a></li>
    <li><a href="nearExpiration.csv">Nearing Expiration (within 60 days)</a></li>
    <li><a href="holidayParty.csv">Current Holiday Party Registrants</a></li>
</ul>
';
?>