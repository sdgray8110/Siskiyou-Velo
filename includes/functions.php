<?php
// Clean Values to prevent sql injection
function clean($str) {
        $str = @trim($str);
        if(get_magic_quotes_gpc()) {
                $str = stripslashes($str);
        }
        return mysql_real_escape_string($str);
}

// checks if iterated value is positive
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}

function shortenText($text) {
	$chars = 190;
	$text = $text." ";
	$text = substr($text,0,$chars);
	$text = substr($text,0,strrpos($text,' '));
	if (substr($text, -4) == '</p>') {
			$text = substr_replace($text, '', -4) . '</p>';
	}
	
	elseif (substr($text, -4) == '</li>') {
			$text = substr_replace($text, '', -4) . '</li>';
	}		
	
	else {
	$text = $text . '';
	}

	return $text;
}

//HOW LONG AGO
function getHowLongAgo($date, $display = array('Year', 'Month', 'Day', 'Hour', 'Minute', 'Second'), $ago = 'Ago')
{
    $date = getdate(strtotime($date));
    $current = getdate();
    $p = array('year', 'mon', 'mday', 'hours', 'minutes', 'seconds');
    $factor = array(0, 12, 30, 24, 60, 60);

    for ($i = 0; $i < 6; $i++) {
        if ($i > 0) {
            $current[$p[$i]] += $current[$p[$i - 1]] * $factor[$i];
            $date[$p[$i]] += $date[$p[$i - 1]] * $factor[$i];
        }
        if ($current[$p[$i]] - $date[$p[$i]] > 1) {
            $value = $current[$p[$i]] - $date[$p[$i]];
            return $value . ' ' . $display[$i] . (($value != 1) ? 's' : '') . ' ' . $ago;
        }
    }

    return '';
}

function VisitorIP() { 
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		$TheIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
	else $TheIp=$_SERVER['REMOTE_ADDR'];
	
	return trim($TheIp);
}

$Users_IP_address = VisitorIP();


function db_connect($table) {
//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

//Debug
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

//Select database
$new_db = mysql_select_db($table,$connection);

//Debug
    if (!$new_db) {
        die("Database selection failed: " . mysql_error());
    }
return $connection;    
}

function populateSelect($array, $field, $description) {
    if (!$description) {
        foreach($array as $value) {
            echo "<option value='".$value."'";
                if ($field == $value) {echo "selected='selected'";}
                echo ">".$value."</option>
                ";
        }
    }

    else {
        foreach($array as $key => $value) {
            echo "<option value='".$value."'";
                if ($field == $value) {echo "selected='selected'";}
                echo ">".$description[$key]."</option>
                ";
        }
    }
}

function buildCheckbox($checkID,$array,$field,$label){
    if (is_array($checkID) == true) {
        foreach($array as $key => $value) {
            echo "<label for ='".$checkID[$key]."'>".$label[$key]."</label>
                <input id=".$checkID[$key]." name='".$checkID[$key]."' type='checkbox' value='".$value."' ";
                if (strpos($field,$value) !== false) {echo "checked='checked'";}
                echo " class='checkbox'></input>
                    ";
        }
    }
    else {
        echo "<label for ='".$checkID."'>".$label."</label>
            <input id=".$checkID." name='".$checkID."' type='checkbox' value='".$array."' ";
            if (strpos($field,$array) !== false) {echo "checked='checked'";}
            echo " class='checkbox'></input>
                ";
    }
}

function buildRadio($checkID,$name,$array,$field,$label){
    foreach($array as $key => $value) {
            echo "<label for ='".$checkID[$key]."'>".$label[$key]."</label>
                <input id=".$checkID[$key]." name='".$name."' type='radio' value='".$value."' ";
                if (strpos($field,$value) !== false) {echo "checked='checked'";}
                echo " class='checkbox'></input>
                    ";
    }
}

function next12Months() {
    $year = date('Y');
    $nextMonth = date('m');
    $nextMonth == 12 ? $nextMonth = 0 : $nextMonth = $nextMonth;
    $nextMonth == 0 ? $nextYear = $year + 1 : $nextYear = $year;
    $years = array($year, $year + 1);
    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
    $monthOptions = buildOptions($months, $nextMonth);
    $yearOptions = buildOptions($years, $nextYear);

    return '<select id="month" name="month">'.$monthOptions.'</select><select id="year" name="year">'.$yearOptions.'</select>';
}

function buildOptions($arr, $selected) {
    $selects = array();

    for ($i=0;$i<count($arr);$i++) {
        $arr[$i] == $selected || $i == $selected ? $selectedVal = ' selected="selected"' : $selectedVal = '';
        $value = '<option value="'.$arr[$i].'"'.$selectedVal.'>'.$arr[$i].'</option>';
        array_push($selects,$value);
    }

    return implode('', $selects);
}

?>