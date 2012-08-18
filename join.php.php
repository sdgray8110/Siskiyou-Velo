<?php require_once("includes/auth.php"); ?>
<?php include("includes/header.php"); ?>
Join The Siskiyou Velo</title>

<?php include("includes/header_bottom.html"); ?>
<?php include("includes/login.php"); ?>
<?php include("includes/topnav.php"); ?>

<?php

$payByCheck = $_GET['payByCheck'];

if (!$payByCheck) {
    echo '
    <div id="leftContent">
        <div id="joinTheClub">

            <h1>Join The Siskiyou Velo Bicycle Club Today</h1>

            <h4>Membership Benefits:</h4>

            <p>Your membership dollars support our work in bicycle advocacy, commuting, and education. As a member, you&rsquo;ll be part of a community of cyclists doing all they can to improve bicycling conditions in our community. For your commitment to better bicycling, you’ll receive:</p>

            <ul>
                <li>10% discounts on bicycle parts and gear at local supporting shops.</li>
                <li>Membership to the Siskiyou Velo Google Group Bike Rides for spontaneous rides</li>
                <!--<li>Access to club rides posted in the website “Members-Only” section</li>-->
                <li>A monthly newsletter in e-format, with articles about club members and events.</li>
                <li>Access to social rides and events.</li>
                <li>Your membership is an investment in creating a better community through bicycling.</li>
            </ul>

            <h4>A powerful bicycle advocate working for you throughout the Rogue Valley.</h4>
            <ul>
                <li>By helping shape policies and plans to be pro-bike, we have direct impact on our built landscape, our transportation choices, our environment and – most of all – our communities.</li>
                <li>Our education and outreach programs encourage safer, healthier choices for everyone who wants to bike.</li>
                <li>We’re working every day to make cycling a safer, more convenient transportation choice for everyone.</li>
            </ul>

            <p>To join now, enter your email address below and click join</p>
            <form id="joinForm" name="joinEmailTest" action="">
                <label for="">Email Address:</label>
                <input type="text" id="joinEmail" name="checkForEmail" />
                <input type="hidden" name="pageRef" value="checkEmail" />
                <input type="submit" value="Join" class="submit" id="nextStep" />
            </form>
        </div>
    </div>
    ';
}

else {
    echo "
    <h2>Your Registration is almost complete.</h2>
    <p class='memberinfo'>Thank you for updating your information. To complete your registration, mail a check for $".$cost." to:</p>
    <ul>
        <li>Siskiyou Velo</li>
        <li>PO BOX 974</li>
        <li>Ashland, Or 97520</li>
    </ul>

    <p class='memberinfo'>Once we have received your check, you'll receive an email confirming that your registration has been processed. If you have any questions, please contact our <a href'mailto:membership@siskiyouvelo.org'>Membership VP</a>. Thank you for joining the Siskiyou Velo.</p>

    </div>
";
}

<!-------- END MAIN BODY -------->

<?php include("includes/generic_feed.html"); ?>
<?php include("includes/foot.html"); ?>