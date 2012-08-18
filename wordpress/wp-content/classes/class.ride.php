<?php
class ride {
    protected $rideData;
    protected $rideDataArray;
    protected $metric;
    protected $footConversion = 3.2808399;
    protected $kmConversion = 1609.344;
    protected $hypConstant = 160.9343997553797;

    public function init($id, $metric = false) {
        $this->getRide($id);
        $this->rideDataArray = $this->setRideDataArray();
        $this->metric = $metric;
    }

    private function getRide($id) {
        require_once('class.svdb.php');
        $svdb = new svdb;

        $this->rideData = $svdb->rideData($id);
    }

    private function setRideDataArray() {
        return json_decode($this->rideData['ridedata'],true);
    }

    private function elevationData() {
        $distance = $this->get_distance();
        $elevation = array();
        $arr = array();

        foreach($this->rideDataArray['elevationData'] as $point) {
            $elevation[] = $this->metric ? floor($point) : floor($point * $this->footConversion);
        }

        for ($i=0;$i<count($this->rideDataArray['elevationData']);$i++) {
            $point = $this->rideDataArray['elevationData'][$i];
            $grade = round($this->rideDataArray['gradeData']['grade'][$i] * 100,1);
            $elevation[$i] = array();
            $elevation[$i]['name'] = $grade;
            $elevation[$i]['y'] = $this->metric ? floor($point) : floor($point * $this->footConversion);
        }

        $arr['distance'] = $distance;
        $arr['elevation'] = $elevation;
        $arr['rideName'] = $this->get_ride_name();

        return json_encode($arr,true);
    }

    private function buildAccordionObject() {
        $arr = array();
        $arr['body'] = array($this->rideData['description'],$this->rideData['about'],$this->rideData['directions'],$this->rideData['traffic'],$this->rideData['optional_body']);
        $arr['headlines'] = array('Ride Description','About This Ride','Directions','Traffic',$this->rideData['optional_head']);

        for ($i=0;$i<count($arr['body']);$i++) {
            if (!$arr['body'][$i]) {
                unset($arr['body'][$i]);
                unset($arr['headlines'][$i]);
            }
        }

        return $arr;
    }

    private function grade($rise,$hyp) {
        $run = sqrt((pow($hyp,2)) - (pow($rise,2)));

        return $rise / $run;
    }

    public function set_grade_data($elevArray) {
        $obj = array();
        $obj['grade'] = array();
        $obj['grade'][0] = 0;
        $obj['max'] = 0;

        for ($i=0;$i<count($elevArray) - 1; $i++) {
            $rise = $elevArray[$i + 1] - $elevArray[$i];
            $hyp = $this->hypConstant;
            $curGrade = $this->grade($rise,$hyp);

            $obj['grade'][$i] = $curGrade;

            if ($curGrade > $obj['max']) {
                $obj['max'] = $curGrade;
            }
        }

        return $obj;
    }

    public function get_ride_name() {
        return $this->rideData['ridename'];
    }

    public function get_elevation_data() {
        return $this->elevationData();
    }

    public function get_distance($label = false) {
        $distance = $this->metric ? $this->rideDataArray['distance'] / 1000 : ($this->rideDataArray['distance'] * $this->footConversion) / 5280;
        $distance = round($distance,2);
        $measure = $this->metric ? 'km' : 'miles';

        return $label ? $distance . ' ' . $measure : $distance;
    }

    public function get_elevation_gain($label = false) {
        $elevationGain = $this->metric ? $this->rideDataArray['elevationGain'] : ($this->rideDataArray['elevationGain'] * $this->footConversion);
        $elevationGain = ceil($elevationGain);
        $elevationGain = number_format($elevationGain);
        $measure = $this->metric ? 'meters' : 'feet';
        return $label ? $elevationGain . ' ' . $measure : $elevationGain;
    }

    public function get_accordion() {
        return $this->buildAccordionObject();
    }

    public function get_foot_conversion() {
        return $this->footConversion;
    }

    public function get_meter_conversion() {
        return $this->kmConversion;
    }

    public function get_max_grade() {
        return round($this->rideDataArray['gradeData']['max'] * 100,1);
    }
}
?>