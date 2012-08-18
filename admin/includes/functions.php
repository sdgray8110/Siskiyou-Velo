<?php
// checks if iterated value is positive
function checkNum($num){
  return ($num%2) ? TRUE : FALSE;
}

//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
	$str = @trim($str);
	if(get_magic_quotes_gpc()) {
		$str = stripslashes($str);
	}
	return mysql_real_escape_string($str);
}

function buildOptions($values, $selected) {
    foreach ($values as $value) {
        $value = explode('|', $value);
        $optionValue = $value[0];
        $optionName = $value[1];

        if ($selected == $optionValue) {
            $optionSelected = ' selected="selected"';
        } else {
            $optionSelected = '';
        }

        echo '
            <option value="'.$optionValue . '"' . $optionSelected . '>' . $optionName . '</option> 
        ';
    }
}

function splitPipedArray($pipedArray) {
    $array = explode('|', $pipedArray);

    return $array;
}

function createLabels($value) {
    $needle = array('merchandise', 'subevent');
    $result = str_replace($needle, '', $value);

    return $result;
}



function buildFormFromArrays($type, $builder, $array1, $array2, $array3, $array4, $id1, $id2, $id3, $id4) {
    $array1 = splitPipedArray($array1);
    $array2 = splitPipedArray($array2);
    $array3 = splitPipedArray($array3);
    $array4 = splitPipedArray($array4);
    $label1 = createLabels($id1);
    $label2 = createLabels($id2);
    $label3 = createLabels($id3);
    $label4 = createLabels($id4);
    $derivative = str_replace('Name', '', $id1);

    $i = 1;

    if ($builder == 'edit') {
        foreach ($array1 as $target) {
            $n = $i - 1;
            if ($target != '') {
                echo '
                    <div class="'.$derivative.$i.'">
                        <h4>'. $type . ' ' . $i  . '</h4>
                        <label for="'.$id1.$i.'">'.$label1.'</label>
                        <input type="text" name="'.$id1.$i.'" id="'.$id1.$i.'" value="'.$array1[$n].'" />

                        <label for="'.$id2.$i.'">'.$label2.'</label>
                        <input type="text" name="'.$id2.$i.'" id="'.$id2.$i.'" value="'.$array2[$n].'" />

                        <label for="'.$id3.$i.'">'.$label3.'</label>
                        <input type="text" name="'.$id3.$i.'" id="'.$id3.$i.'" value="'.$array3[$n].'" />

                        <label for="'.$id4.$i.'">'.$label4.'</label>
                        <textarea name="'.$id4.$i.'" id="'.$id4.$i.'">'.$array4[$n].'</textarea>
                    </div>
                ';
            }

            else {
                switch($type) {
                    case 'Sub Event':
                        $addAnother = 'addSubEvent';
                    break;
                    case 'Item':
                        $addAnother = 'addMerchandise';
                    break;
                }

                echo '
                    <div class="'.$derivative.$i.'">
                        <a href="" id="'.$addAnother.'">Add Another</a>
                    </div>
                ';

                break;
            }

            $i ++;
        }
    } else {
        foreach ($array1 as $target) {
            $n = $i - 1;
            if ($target != '') {
                echo '
                    <div class="'.$derivative.$i.'">
                        <h4>Event Name:</h4>
                        <p>Description</p>
                        <p><a href="">Additional Link</a></p>
                        <p>Event Registration Fee: $10.00</p>
                        <fieldset>
                            <label for="something">Select This Event</label>
                            <input type="radio" id="something" name=something">
                        </fieldset>
                    </div>
                ';
            }

            $i ++;
        }
    }
}

function iteratedOptions($start, $count, $selected) {
    $i = 0;
    $current = $start;

    while ($i < $count) {

        if ($current == $selected) {
            echo '
                <option value="'.$current.'" selected="selected">'.$current.'</option>
            ';
        } else {
            echo '
                <option value="'.$current.'">'.$current.'</option>
            ';
        }

        $i ++;
        $current ++;
    }
}

?>