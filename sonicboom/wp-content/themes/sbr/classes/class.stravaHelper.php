<?php

class strava {

    public function __construct($id) {
        $this->id = $id;
        $this->baseURL = 'http://www.strava.com/api/';
    }

    public function hitAPI($args,$params = array()) {
        $json_url = $this->requestURL($args,$params);
        $ch = curl_init( $json_url );
        $options = array(
            CURLOPT_RETURNTRANSFER => true
        );

        curl_setopt_array( $ch, $options );

        return $result = curl_exec($ch);
    }

    public function requestURL($args,$queryParams) {
        $argStr = implode('/',$args);
        $paramsStr = count($queryParams) ? '?' . implode('&',$queryParams) : '';
        $params = $argStr . $paramsStr;

        return $this->baseURL . $params;
    }

    public function response($data) {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (!$isAjax) {
            return json_decode($data);
        }

        return $data;
    }

    public function get_members() {
        $args = array('v1','clubs',$this->id,'members');

        return $this->response($this->hitAPI($args));
    }

    public function get_club_rides($ct = 5) {
        $args = array('v1', 'rides');
        $params = array('clubId=' . $this->id);
        $raw = json_decode($this->hitAPI($args,$params))->rides;
        $data = array();
        $i = 0;

        foreach ($raw as &$ride) {
            if ($i < $ct) {
                $data[] = new ride($ride->id,$this->id);
            }

            $i++;
        }

        return $data;
    }

    public function save_ride_data($pageID) {
        $rides = json_encode($this->get_club_rides());

        update_post_meta($pageID,'rides',$rides);
    }
}

class ride extends strava {
    public function __construct($rideID,$teamID) {
        parent::__construct($teamID);
        $this->rideID = $rideID;
        $this->get_ride();
    }

    private function get_ride() {
        $args = array('v1','rides',$this->rideID);
        $data = json_decode($this->hitAPI($args))->ride;

        unset($this->rideID);
        unset($this->id);
        unset($this->baseURL);

        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        $this->id = $data->id;
        $this->startDate = date('l, M j, Y', strtotime($data->startDate));
        $this->movingTime = $this->hms($data->movingTime);
        $this->distance = number_format($this->meters_to_miles($data->distance), 2);
        $this->elevationGain = number_format($this->meters_to_feet($data->elevationGain), 0);
        $this->averageSpeed = number_format($this->set_average_speed($data), 2);
        $this->athlete->avatar = $this->get_avatar($data->athlete->id);
    }

    private function get_avatar($riderID) {
        $scraper = new stravaScraper($riderID);

        return $scraper->avatar;
    }

    private function hms ($sec, $padHours = false) {
        $hms = "";

        $hours = intval(intval($sec) / 3600);

        $hms .= ($padHours)
              ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
              : $hours. ":";

        $minutes = intval(($sec / 60) % 60);
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";
        $seconds = intval($sec % 60);
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

        return $hms;
    }

    private function meters_to_feet($meters) {
        return $meters * 3.28084;
    }

    private function meters_to_miles($meters) {
        return $this->meters_to_feet($meters) / 5280;
    }

    private function set_average_speed($data) {
        $hours = ($data->movingTime / 60) / 60;

        return $this->distance / $hours;
    }
}

class stravaScraper {
    public function __construct($riderID) {
        $serverRoot = '/home/gray8110/public_html';

        require_once($serverRoot . '/utils/simplehtmldom_1_5/simple_html_dom.php');
        $this->url = 'http://www.strava.com/athletes/' . $riderID;

        $this->avatar = $this->get_avatar();
    }

    private function get_avatar() {
        $html = str_get_html($this->curlReq());
        $container = $html->find('.avatar');
        $img = $container[0]->find('img');

        return $img[0]->src ? $img[0]->src : 'http://d26ifou2tyrp3u.cloudfront.net/assets/avatar/athlete/medium-162a16b208e0364313ec8ce239ffd7a8.png';
    }

    private function curlReq() {
        $ch = curl_init( $this->url );
        $options = array(
            CURLOPT_RETURNTRANSFER => true
        );

        curl_setopt_array( $ch, $options );

        return $result = curl_exec($ch);
    }
}