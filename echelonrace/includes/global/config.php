<?php
$rootContext = '/home/gray8110/public_html/echelonrace/';
$imagePath = '/home/gray8110/public_html/echelonrace/docroot/img/';
$includes = $rootContext . '/includes/';
$formHandlers = '../includes/formHandlers/';
$staticContext = 'http://www.echelonrace.com/docroot/';
$bikeFestLink = '/shootout/';
$springThawLink = '/springthaw/';
$stxcLink = '/stxc/';
$stageCoachLink = '/stagecoach/';
$volunteerLink = '/volunteer.php';
$photosLink = '/photos.php';
$aboutLink = '/about.php';
$contactLink = '/contact.php';
$springThawNav = array('overview|Overview','register|Registration', 'crossCountry|Cross Country', 'downhill|Downhill', 'kidsRace|Kid&rsquo;s Race','information|Stuff You Need to Know');
$bikefestNav = array('overview|Overview','register|Register', 'dayOne|Day One', 'dayTwo|Day Two', 'lodging|Camping/Lodging','information|Stuff You Need to Know');
$stxcNav = array('overview|Overview','register|Registration', 'races|The Races', 'directions|Directions');
$stageCoachNav = array('overview|Overview','register|Register','crossCountry|XC Race','super-d|Super D','lodging|Camping/Lodging','information|Stuff You Need to Know');

function is_odd($number) {
  return $number & 1;
}

function buildSubNav($nav) {

    $i = 0;
    foreach ($nav as $page) {
        $array = explode('|', $page);
        $pageId = $array[0];
        $name = $array[1];
        $i == 0 ? $thisClass = 'class="active" ' : $thisClass = '';

        echo '
            <li><a '.$thisClass.' id="'.$pageId.'" href="ajax/'.$pageId.'.php">'.$name.'</a></li>
        ';

        $i++;
    }
}

function buildPhotoGallery($filePath, $imgPath, $activeClass, $alt, $method) {
    $files = buildPhotoArray($filePath, $imgPath, $activeClass, $alt);
    if ($method == 'shuffle') {
        shuffle($files);
    } else {
        sort($files, SORT_STRING);
    }

    $i = 0;
    foreach ($files as $file) {
        $className = $i == 0 ? ' class="'.$activeClass.'"' : '';
        echo "\t<li".$className."><img src=\"".$imgPath.$file."\" alt=\"".$alt."\" /></li>\n";
        $i ++;
    }

}

function buildPhotoArray($filePath, $imgPath, $activeClass, $alt) {
    if ($handle = opendir($filePath)) {

        $files = array();
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $files[] = $file;
            }
        }
        closedir($handle);
        return $files;
    }
}

function dbConnection() {
    //Connect to database
    $connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

    //Debug
    if (!$connection) {
        die ("Database connection failed: " . mysql_error());
    }

    //Select database
    $new_db = mysql_select_db("gray8110_svblogs",$connection);

    //Debug
    if (!$new_db) {
        die("Database selection failed: " . mysql_error());
    }

    return $connection;
}

function sponsorQuery($raceName) {
    $connection = dbConnection();

    if ($raceName) {
        $qry = 'SELECT * FROM echelonSponsors WHERE '.$raceName.' = 1 ORDER BY RAND()';
    } else {
        $qry = 'SELECT * FROM echelonSponsors ORDER BY id ASC';
    }

    $result = @mysql_query($qry, $connection);

    return $result;
}

function renderSponsors($raceName) {
    $result = sponsorQuery($raceName);
    $i = 0;

    while ($sponsors = mysql_fetch_array($result)) {
        $name = $sponsors['name'];
        $url = $sponsors['url'];
        $image = $sponsors['image'];
        $i == 0 ? $thisClass = ' class="active" ' : $thisClass = '';


        if ($url) {
            echo "<li".$thisClass."><a href=\"".$url."\"><img src=\"/docroot/img/sponsors/".$image."\" alt=\"".$name."\" /></a></li>\n\t";
        } else {
            echo "<li".$thisClass."><img src=\"/docroot/img/sponsors/".$image."\" alt=\"".$name."\" /></li>\n\t";
        }

        $i ++;
    }
}

function sponsorRows($result) {
    $i = 0;
    while ($row = mysql_fetch_array($result)) {
        is_odd($i) ? $rowClass = ' class="odd"' : $rowClass = '';

        echo '
            <tr'.$rowClass.'>
                <td>'.$row["id"].'</td>
                <td>'.$row["name"].'</td>
                <td>'.$row["url"].'</td>
                <td class="bikefest">'.booleanCheckbox($row["bikefest"], $row["id"]).'</td>
                <td class="springthaw">'.booleanCheckbox($row["springthaw"], $row["id"]).'</td>
                <td class="stxc">'.booleanCheckbox($row["stxc"], $row["id"]).'</td>
                <td class="stagecoach">'.booleanCheckbox($row["stagecoach"], $row["id"]).'</td>
            </tr>
        ';

        $i++;
    }
}

function booleanCheckbox($value, $id) {
    if ($value == '0') {
        $checked = '';
    } else {
        $checked = ' checked="checked"';
    }

    return '<input type="checkbox" name="sponsor'.$id.'" value="1"'.$checked.' />';
}

function updateSponsors($value, $id, $race) {
    $connection = dbConnection();

    	//Create update query
	$qry = 'UPDATE echelonSponsors SET '.$race.' = '.$value.' WHERE id = '.$id;

	@mysql_query($qry, $connection);
}

// Email Functions
function radioValue($array) {
    $str = '';

    foreach ($array as $value) {
        $str == '' ? $sep = '' : $sep = ', ';

        if ($value != '') {
            $str = $str . $sep . $value;
        }
    }

    return $str;
}