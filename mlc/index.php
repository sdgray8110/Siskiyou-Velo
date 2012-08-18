<?php
$pageTitle = 'Home';
include('includes/functions.php');
include('includes/header.php');
include('includes/flashPhoto.php');
?>

  <div id="bodyCont">

<?php include("includes/menu.php"); ?>

<div id="contentCont">
    <div class="content">
<?php echo '
        <h3>Three Great Mountain Rides</h3>
        <p>The Mountain Lakes Challenge offers three scenic and very demanding routes in the Southern Oregon Cascades. Bring your climbing legs.</p>
        <ul>
            <li><a href="'.$metricLink.'">Metric Challenge</a>: 58 Miles | 5,100&rsquo; Climbing</li>
            <li><a href="'.$centuryLink.'">Century Challenge</a>: 100 Miles | 7,100&rsquo;</li>
            <li><a href="'.$doubleLink.'">Double Metric Challenge</a>: 135 Miles | 10,000&rsquo;</li>
        </ul>

        <p>This is not a sprawling event with a cast of thousands, so you don&rsquo;t have to deal with long lines for sign-in, bathrooms, or food and water; and we can provide the best possible support. All three rides start and finish at ' . $gracePointLink . ' in Ashland and will keep you going with multiple well-stocked rest stops and lots of friendly volunteers.</p>

        <p><strong>Please note:</strong> Online pre-registration closes at midnight on Monday, June 18. There is no mail-in registration. There is a $10.00 surcharge for day-of registration. Be sure to check the <a href="http://forecast.weather.gov/MapClick.php?CityName=Ashland&state=OR&site=MFR&textField1=42.1947&textField2=-122.708&e=0">7 day weather forecast</a> in the days leading up to the event.</p>

    </div>
</div>

<div id="rightCol">
        <div class="rightContent updateBlurb">
          '.$updateBlurb.'
        </div>
</div>
';

include('includes/footer.php');
?>
</div>

<script type="text/javascript">
    $.superbox();
</script>
</body>
</html>