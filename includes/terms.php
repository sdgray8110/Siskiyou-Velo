<?php
$termsType = $_GET['terms'];
$waiver = '<div class="tos">';
$proud = '<div class="tos">';
        
switch($termsType) {
    case 'waiver' : 
        $proud = '<div class="tos hidden">';
        break;
    case 'proud' :
        $waiver = '<div class="tos hidden">';
        break;    
}

echo $waiver.'
<h2>Siskiyou Velo Ride Waiver: ';
    if ($termsType) {echo '<a href="javascript: $.superbox.close()" class="close"></a>';}
echo '</h2>
    <ul>
    <li>IN CONSIDERATION of being permitted to participate in any way in SISKIYOU VELO ("Club") sponsored Bicycling Activities ("Activity") I, for myself, my personal representatives, assigns, heirs, and next of kin:</li>

    <li>1.  ACKNOWLEDGE, agree, and represent that I understand the nature of Bicycling Activities and that I am qualified, in good health, and in proper physical condition to participate in such Activity.  I further acknowledge that the Activity will be conducted over public roads and faci lities open to the public during the Activity and upon which the hazards of traveling are to be expected.  I further agree and warrant that if, at any time, I believe conditions to be unsafe , I will immediately discontinue further participation in the Activity. I acknowledge that a helmet is required to be worn by me at all times while riding a bicycle in any Siskiyou Velo sponsored ride or event.</li>

    <li>2.  FULLY UNDERSTAND that (a) BICYCLING ACTIVITIES INVOLVE RISKS AND DANGERS OF SERIOUS BODILY INJURY, INCLUDING PERMANENT DI SABILITY, PARALYSIS AND DEATH ("Risks"); (b) these Risks and dangers may be caused by my own actions or inactions, the actions or inactions of others participating in the Activity, the conditions in which the Activity takes place, or THE NEGLIGENCE OF THE "RELEASEES" NAMED BELOW; (c) there may be OTHER RISKS AND SOCIAL AND ECONOMIC LOSSES either not known to me or not readily foreseeable at this time; and I FULLY ACCEPT AND ASSUME ALL SUCH RISKS AND ALL RESPONSIBILITY FOR LOSSES, COSTS, AND DAMAGES I may incur as a result of my participation in the Activity.</li>

    <li>3.  HEREBY RELEASE, DISCHARGE, AND COVENANT NOT TO SUE the Club, the LAB, its respective administrators, directors, agents, officers, members, volunteers, and employees, other participants, any sponsors, advertisers, and, if applicable, owners and lessors of premises on which the Activity takes place, (each considered one of the "RELEASEES" herein) FROM ALL LIABILITY, CLAIMS, DEMANDS, LOSSES, OR DAMAGES ON MY ACCOUNT CAUSED OR ALLEGED TO BE CAUSED IN WHOLE OR IN PART BY THE NEGLIGENCE OF THE "RELEASEES" OR OTHERWISE, INCLUDING NEGLIGENT RESCUE OPERATIONS.  And, I FURTHER AGREE that if, despite this RELEASE AND WAIVER OF LIABILITY, ASSUMPTION OF RISK, AND INDEMNITY AGREEMENT I, or anyone on my behalf, makes a claim against any of the Releasees, I WILL INDEMNIFY, SAVE, AND HOLD HARMLESS EACH OF THE RELEASEES from any litigation  expenses, attorney fees, loss, liability, damage, or cost which any may incur as the result of such claim.</li>

    <li>I AM 18 YEARS OF AGE OR OLDER, HAVE READ AND UNDERSTAND THE TERMS OF THIS AGREEMENT, UNDERSTAND THAT I AM GIVING UP SUBSTANTIAL RIGHTS BY SIGNING THIS AGREEMENT, HAVE SIGNED IT VOLUNTARILY AND WITHOUT ANY INDUCEMENT OR ASSURANCE OF ANY NATURE AND INTEND IT TO BE A COMPLETE AND UNCONDITIONAL RELEASE OF ALL LIABILITY TO THE GREATEST EXTENT ALLOWED BY LAW.  I AGREE THAT IF ANY PORTION OF THIS AGREEMENT IS HELD TO BE INVALID, THE BALANCE, NOTWITHSTANDING, SHALL CONTINUE IN FULL FORCE AND EFFECT.</li>

    </ul>
</div>';

echo $proud.'
<h2>I AM COMMITTED TO CYCLING EXCELLENCE:';
    if ($termsType) {echo '<a href="javascript: $.superbox.close()" class="close"></a>';}
echo '</h2>
<h3>Siskiyou Velo is &ldquo;PROUD&rdquo;!</h3>

<p><strong>P =</strong> Prepared for everything</p>
<p><strong>R =</strong> Routinely safe</p>
<p><strong>O =</strong> Out to have fun</p>
<p><strong>U =</strong> Undauntingly a defensive rider, respectful of all road users</p>
<p><strong>D =</strong> Dedicated to positively representing cyclists</p>

<h3>I am prepared:</h3>

<ul>
<li>I wear my helmet when on my bike.</li>
<li>I keep my bike in good working condition.</li>
<li>I have my identification and emergency information with me.</li>
<li>I know the route, or have a map with me if I don’t.</li>
<li>I have the tools, tubes, tire boots and inflation equipment to keep my bike going.</li>
<li>I have the clothing, food and liquids to keep me going.</li>
</ul>

<h3>My goals on the road:</h3>

<ul>
    <li>I ride to have fun while being safe.</li>
    <li>I gladly SHARE THE ROAD, and I ride in a manner that makes me a Good Will Ambassador for Cycling.</li>
    <li>I am responsible for keeping my bike out of danger to protect myself and other users of the road.</li>
    <li>I ride single file in the presence of motorized vehicles, and stay as far right as is safely possible.</li>
    <li>I give respect to motorists and pedestrians. If in doubt, I stop and wave them through.</li>
    <li>I observe all applicable provisions of the <a href="www.dmv.org/or-oregon/automotive-law/vehicle-code.php">Oregon State Vehicle Code</a>.</li>
    <li>I observe the terms established by the Ride Leader, and work to keep the group together.</li>
    <li>I stop, with the group, for all mechanical problems until released by the Ride Leader.</li>
    <li>I never negatively or abusively confront other users of the road, even if they are guilty of a transgression.</li>
    <li>I share the joy of cycling with others</li>
</ul>

</div>';
?>
