<div class="flashSlideshow">
        <img src="images/mainphoto.jpg" width="654" height="257" border="0" alt="Siskiyou Velo" />
</div>

<div id="featuredContent">
        <?php
            // Module 1 //
            if (!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
                include('includes/join_module.php');
            }

            else {
                include('includes/db_connect.php');
                    $memberID = $_SESSION["SESS_MEMBER_ID"];
                    $result = mysql_query("SELECT * FROM wp_users WHERE ID = '$memberID'", $connection);

                while ($row = mysql_fetch_array($result)) {
                    $renewDate = $row['DateExpire'];
                    $renewDate = date('m/j/Y', strtotime($renewDate));
                    $date = time();

                    if (strtotime($renewDate) - $date < 7776000) {
                        include('includes/renew_module.php');
                    } else {
                        include('includes/advocacy_module.php');
                    }
                }
            }

            // Module 2 //
            if (time() < 1277017200) {
                include('includes/mlc_featured.php');
            }
            else {
                include('includes/featured_ride.php');

            }

            // Module 3 //
        echo '
        <div class="contentModule">';
            include("includes/homepage_news.php");
        echo '
        </div>
        ';
    ?>
</div>