<?php

include('../../../includes/global/dbFunctions.php');
include('../../../includes/global/resultsBuilder.php');

echo buildResults('svblogs','thawDhResults','place','ASC','DISTINCT field',false,$fields,$tableFieldNames);

?>