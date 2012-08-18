<h1>New Account Profile</h1>

<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.svdb.php');
$svdb = new svdb;
$membership = $svdb->membershipVP();
$renewalDate = date('m/d/Y');
$primaryEmail = $_GET['email'];
echo "
<h2 class='memberDetails'>Membership Details</h2>
<ul class='memberDetails'>
    <li>
        <p class='ident'>Membership Type:</p>
        <p id='memberTypeVar'>Individual</p>
    </li>
    <li>
        <p class='ident'>Join Date:</p>
        <p>".$renewalDate." <small>*Official join date set when payment clears.</small></p>
    </li>
    <div style='clear:both;'></div>
</ul>
";
?>

<div class='agreement'>
<p>Thank you for your joining the Siskiyou Velo. Joining will only take a few minutes and payments can now be processed online via paypal using a check or credit card.</p>

<p>If you wish to mail payment, please download the <a href="images/PDF/join.pdf">Membership Registration Form</a>, complete it, and mail it together with your check to:</p>

    <dl class="address">
        <dt><strong><?=$membership['firstname'];?> <?=$membership['lastname'];?></strong></dt>
        <dt>Siskiyou Velo Membership</dt>
        <dt>PO Box 974</dt>
        <dt>Ashland, OR 97520</dt>
    </dl>

<p>Our three membership types are detailed below. If you'd like to choose an option other than the default individual membership, you can do so on the next page.</p>

<dl>
    <dt>Individual:</dt><dd>$15 Annual Fee</dd>
    <dt>Family:</dt><dd>$20 Annual Fee</dd>
    <dt>Business:</dt><dd>$25 Annual Fee</dd>
</dl>

<p>As you proceed with registration, please be sure to enter as much information as possible. The details you provide will allow us to provide a better club experience for you and be assured that any data you provide will never be shared with third parties.  To continue with the registration process, you must also agree to the <a href='' rel='superbox[ajax][includes/terms.php?terms=waiver[700x550]'>Ride Waiver</a> and <a href='' rel='superbox[ajax][includes/terms.php?terms=proud[620x520]'>Committment to Cycling Excellence</a> agreement below. Though you can certainly &ldquo;click through&rdquo; this portion of the process, we encourage you to read these two documents. The waiver has been designed to protect you, the club and the officers of the club in case of law suits, and you should be informed about this. The other document concerns a club program to &ldquo;RIDE PROUD&rdquo; - a way of expressing the club&rsquo;s values in ettiquette on the road, observing laws and sharing the road. You can be PROUD to be informed.</p>

<p>If you have any questions, please contact our <a href='mailto:membership@siskiyouvelo.org'>Membership VP</a>

<p class='disclaimer'>*By selecting the &ldquo;I agree&rdquo; box and submitting this form, you indicate that you have read, understood and agree to the terms in our <a href='' rel='superbox[ajax][includes/terms.php?terms=waiver[700x550]'>Ride Waiver</a> and <a href='' rel='superbox[ajax][includes/terms.php?terms=proud[620x520]'>Committment to Cycling Excellence</a>.</p>
<label for='termsAgree'>
    <input type='checkbox' name='termsAgree' id='termsAgree' value='yes'></input>
    I Agree*
</label>

<input type='submit' class=submit' id='agreeToTerms' value='Continue Renewal &raquo'></input>

</div>

<div class="hideStuff">
<div class='memberProfile'>

<h2>Please Verify Your Contact Info &amp; Preferences</h2>
<p class='memberinfo'>Please fill out the form below. Be sure to proceed through each section and complete all fields.</p></div>

<div id='memberAccordion'>
<form id='profile' name='profile' action='../includes/join-exec.php' method='post'>

<h2 class='memberDetails active'>Membership Settings &amp; Payment Preference</h2>
<div class='memberDetails' style='display:block;'>
    <p>At the completion of this form, you will be redirected to Paypal for payment. Paypal is a quick, easy and secure way of making payments with credit card or check online. You are not required to create an account with PayPal though it does make the process simpler for future payments. </p>
    <!--<p>If you would prefer, we will still accept checks. Please select that option in the dropdown below, finish updating your preferences and click continue with checkout. Upon completion you'll be provided with a mailing address to send the check to. Thank you.</p>-->
    <!--<label for='paymentType'>Payment Method:</label>-->
    <select style="display:none;" id='paymentType' name='paymentType'>
        <option selected='selected' value='paypal'>Paypal</option>
        <!--<option value='check'>Check by Mail</option>-->
    </select>

    <label for='contactSelect'>Membership Type:</label>
    <select id='memberType' name='memberType'>
        <option value='I'>Individual - $15 Annual Membership</option>
        <option value='F'>Family - $20 Annual Membership</option>
        <option value ='B'>Business - $25 Annual Membership</option>
    </select>
</div>

<h2 class='memberDetails'>Vital Information</h2>
<div class='memberDetails'>
    <label for='firstname'>First Name:</label>
    <input type='text' class='required'  minlength='2' name='firstname' id='firstname' value=''></input>
    
    <label for='lastname'>Last Name:</label>
    <input type='text' class='required'  minlength='2' name='lastname' id='lastname' value=''></input>
    
    <label for='email1'>Primary Email:</label>
    <input type='text' class='required email' name='email1' id='email1' value='<?php echo $primaryEmail ?>'></input>
    
    <label for='email2'>Secondary Email:</label>
    <input type='text' class='email' name='email2' id='email2' value=''></input>
    
    <label for='address'>Mailing Address:</label>
    <input type='text' name='address' id='address' value=''></input>
    
    <label for='city'>City:</label>
    <input type='text' name='city' id='city' value=''></input>
    
    <label for='state'>State:</label>
    <input type='text' name='state' id='state'  maxlength='3' value=''></input>
    
    <label for='zip'>Zip Code:</label>
    <input type='text' name='zip' id='zip' maxlength='10' value=''></input>
    
    <label for='phone'>Phone Number:</label>
    <input type='text' name='phone' id='phone'  maxlength='14' value=''></input>
	
	<div class='separate'>
            <label for='passwd'>Password:</label>
            <input type='password' class='required' name='passwd' id='passwd' minlength='6' ></input>
	</div>
</div>

<h2 class='memberDetails'>Additional Information</h2>
<div class='memberDetails'>
    <label for='address2'>Secondary/Physical Address:</label>
    <input type='text' name='address2' id='address2' value=''></input>

    <label for='family'>Family Members:</label>
    <input type='text' name='family' id='family' value=''></input>
    
    <label for='address'>Website:</label>
    <input type='text' name='website' id='website' value=''></input>
    
    <label for='age'>Age:</label>
    <select name='age' id='age'>
    	<option value='18-35'>18-35</option>
        <option value='36-45'>36-45</option>
        <option value='46-55'>46-55</option>
        <option value='56-65'>56-65</option>
        <option value='66+'>66+</option>
    </select>
    
</div>

<h2 class='memberDetails'>Contact Preferences</h2>
<div class='memberDetails'>
	<p>The Siskiyou Velo as a club makes member contact information available to our members through our Membership Directory. If you'd prefer that your contact information not be shared with other members you can set your preferences below. This information is only available to club members and never made available to a third party. The club also occasionally sends out emails to members. These range from newsletter notifications and renewal reminders to club rides you might be interested in. You can opt-out of these below.</p>
    <label for='contactSelect'>Display Contact Info:</label>
    <select id='contactSelect' name='contact'>
        <option value='3'>All Contact Info</option>
        <option value='2'>Phone Only</option>
        <option value='1'>Email Only</option>
        <option value='0'>No Contact Info</option>
    </select>
    
    <label for='contact'>Display Address:</label>
    <select id='addressSelect' name='dispAddress'>
        <option value='1'>Display Address</option>
        <option value='0'>Keep Address Private</option>
    </select>
    
    <label for='contact'>Email Opt-out:</label>
    <select id='optOut' name='emailOptOut'>
        <option value='0'>Receive Club Emails</option>
        <option value='1'>Opt Out of Club Emails</option>
    </select>

    
    <label for='comments'>Comments:</label>
    <textarea name='comments' id='comments'></textarea>
    
</div>


<h2 class='memberDetails'>Volunteering &amp; Cycling Preferences</h2>
    <div class='memberDetails cyclingInfo'>
        <h2>Cycling Preferences</h2>
            <label class='normal' for ='ridingStyle'>Riding Style</label>
                <select id='ridingStyle' name='ridingStyle'>
                    <option value=''>Choose Your Riding Style</option>
                    <option value='a'>Ride only by myself</option>
                    <option value='b'>With significant other or close friends, only</option>
                    <option value='c'>Small groups – social</option>
                    <option value='d'>Any group rides</option>
                    <option value='e'>Don&rsquo;t Ride</option>
                </select>
	
            <label class='normal' for ='ridingSpeed'>Riding Speed</label>
                <select id='ridingSpeed' name='ridingSpeed'>
                    <option value=''>Choose Your Riding Speed</option>
                    <option value='a'>Under 10 mph average speed</option>
                    <option value='b'>Between 10-12 mph</option>
                    <option value='c'>Between 12-15 mph</option>
                    <option value='d'>Over 15 mph</option>
                    <option value='e'>Don&rsquo;t Ride</option>
                </select>

        <h2>Club Activities</h2>
            <label for ='volunteer'>Interested in Volunteering?</label>
            <input type='checkbox' value='1' class='checkbox' id='volunteer' name='volunteer'></input>

            <label for ='rideLeader'>Interested in Ride Leading</label>
            <input type='checkbox' value='1' class='checkbox' id='rideLeader' name='rideLeader'></input>
		

        <h2>Bicycle Stable <span>(Check All That Apply)</span></h2>
            <label for ='nobike'>Do not own a bike</label>
            <input id='nobike' name='nobike' type='checkbox' value='a' class='checkbox'></input>
            
            <label for ='cityBike'>City Bike</label>
            <input id='cityBike' name='cityBike' type='checkbox' value='b' class='checkbox bikeType'></input>
            
            <label for ='roadBike'>Road Bike, CX, Touring or Hybrid</label>
            <input id='roadBike' name='roadBike' type='checkbox' value='c' class='checkbox bikeType'></input>
            
            <label for ='mountain'>Mountain Bike</label>
            <input id='mountain' name='mountain' type='checkbox' value='d' class='checkbox bikeType'></input>

            <label for ='recumbent'>Recumbent</label>
            <input id='recumbent' name='recumbent' type='checkbox' value='e' class='checkbox bikeType'></input>
			
            <label for ='tandem'>Tandem</label>
            <input id='tandem' name='tandem' type='checkbox' value='f' class='checkbox bikeType'></input>
			
            <label for ='fixedgear'>Fixed Gear</label>
            <input id='fixedgear' name='fixedgear' type='checkbox' value='g' class='checkbox bikeType'></input>
            
        <h2>Why I am joining the Siskiyou Velo Cycling club <span>(Check All That Apply)</span></h2>
            <label for ='nobikes'>Do not own a bike</label>
            <input id='nobikes' name='nobikes' type='checkbox' value='a' class='checkbox'></input>
            
            <label for ='socialize'>To socialize</label>
            <input id='socialize' name='socialize' type='checkbox' value='b' class='checkbox'></input>
            
            <label for ='social_rides'>Ride and socialize</label>
            <input id='social_rides' name='social_rides' type='checkbox' value='c' class='checkbox'></input>
            
            <label for ='advocacy'>Support cycling (Rights, education, advocacy, etc.)</label>
            <input id='advocacy' name='advocacy' type='checkbox' value='d' class='checkbox'></input>

            <label for ='green'>Support a &ldquo;green&rdquo; lifestyle (alternative transportation, etc.)</label>
            <input id='green' name='green' type='checkbox' value='e' class='checkbox'></input>
    </div>

        <input class='submit' id='finalSubmit' type='submit' alt='Submit' name='submit' value='Continue &raquo;'></input>
	
	</form>
</div>
</div>
<script src="http://ajax.microsoft.com/ajax/jquery.validate/1.7/jquery.validate.js" type="text/javascript"></script>
<script type="text/javascript" src="includes/js/memberAccordion.js"></script>

<script>
    $('#finalSubmit').click(function() {
        var root = $(this),
            form = root.parents('form'),
            valid = form.valid();

        if (!valid) {
            $('h2.memberDetails').eq(2).click()
        } else {
            return true;
        }
    });
</script>