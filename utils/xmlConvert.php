<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.svdb.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.ride.php');

$svdb = new svdb();
$ride = new ride();
$xml = new SimpleXMLElement(stripslashes($_POST['xml']));
$json = json_encode($xml);
$oldarr = json_decode($json,TRUE);
$arr = array();

for ($i=0;$i<count($oldarr['graphs']['graph'][0]['value']);$i++) {
    $arr['elevationData'][] = $oldarr['graphs']['graph'][0]['value'][$i] / $ride->get_foot_conversion();
    $arr['distancePoints'][] = ($oldarr['series']['value'][$i] * 5280) / $ride->get_foot_conversion();
}

$arr['distance'] = $_POST['distance'] * $ride->get_meter_conversion();
$arr['elevationGain'] = $_POST['elevationGain'] / $ride->get_foot_conversion();
$arr['gradeData'] = $ride->set_grade_data($arr['elevationData']);

$svdb->postRideData($_POST['rideID'],json_encode($arr));

?>