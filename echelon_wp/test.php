<?php
include('wp-load.php');

$sponsors = pageHelper::get_sponsors_list();

for($i=0;$i<count($sponsors);$i++) {
    $values[$i] = $sponsors[$i]->id;
    $valueLabel[$i] = $sponsors[$i]->brand;
}

print_r($values);
print_r($valueLabel);