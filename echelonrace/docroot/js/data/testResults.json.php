<?php

include('../../../includes/global/dbFunctions.php');
include('../../../includes/global/resultsBuilder.php');

$fields = '"Place","Number","Last Name","First Name","Team","Age","Points"';
$tableFieldNames = array('place', 'number', 'last', 'first', 'team', 'age', 'points');

echo renderAltResults('svblogs','stxc1Results','place','ASC','DISTINCT field',false,$fields,$tableFieldNames);

?>