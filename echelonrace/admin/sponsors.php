<?php
include('../includes/global/config.php');
$result = sponsorQuery('');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../docroot/css/admin.css" rel="stylesheet" />
<title>Echelon Racing Sponsor Admin</title>
</head>

<body>
<form id="sponsorAdmin" method="" action="">
<table>
    <tr>
        <th>ID</th>
        <th>Sponsor</th>
        <th>Link</th>
        <th>Shootout</th>
        <th>Spring Thaw</th>
        <th>STXC</th>
        <th>Stage Coach</th>
    </tr>


<?php

sponsorRows($result);

?>

</table>
</form>
<script src="../docroot/js/production/global.js" type="text/javascript"></script>
<script src="../docroot/js/admin.js" type="text/javascript"></script>

</body>
</html>
