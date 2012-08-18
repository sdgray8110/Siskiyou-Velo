<?php

class resultsScraper {
    public function __construct() {
        $serverRoot = $_SERVER['DOCUMENT_ROOT'] ? $_SERVER['DOCUMENT_ROOT'] : '/home/gray8110/public_html';

        include($serverRoot . '/utils/simplehtmldom_1_5/simple_html_dom.php');
        //$this->riders = array(347366,336396,257288,195657,286398);
        $this->riders = resultsScraper::get_riders();
        $this->results = $this->fetchResults();
    }

    private function fetchResults() {
        $teamdata = array();

        foreach ($this->riders as $riderData) {
            $id = $riderData->usac;
            $resultpage = file_get_html('http://www.usacycling.org/results/?compid=' . $id);
            $races = $resultpage->find('td.homearticlebody');
            $results = $resultpage->find('tr.homearticlebody');
            $len = count($results);
            $data = new stdClass();
            $data->riderName = '';
            $data->usac = $id;
            $data->id = $riderData->id;
            $data->data = array();
            $notResult = array('dns','dnf', 'dnp', 'dq', 'DNF', 'DNS', 'DNP', 'DQ');

            for ($i=0;$i<$len;$i++) {
                $race = $races[$i];
                $result = $results[$i];
                $raceInfo = explode(' - ',$race->plaintext);
                $addtlInfo = explode(' | ', $raceInfo[1]);

                $racedata = new stdClass();
                $data->riderName = $data->riderName ? $data->riderName : $result->find('td',2)->plaintext;

                $racedata->date = strtotime($raceInfo[0]);
                $racedata->prettyDate = date('m/d',$racedata->date);
                $racedata->month = date('F',$racedata->date);
                $racedata->year = date('Y',$racedata->date);
                $racedata->event = $race->find('a',0)->plaintext;
                $racedata->discipline = $addtlInfo[1];
                $racedata->category = $addtlInfo[2];
                $racedata->placing = $result->find('td',0)->plaintext;
                $racedata->prettyPlacing = number_suffix($racedata->placing);
                $racedata->name = $data->riderName;
                $racedata->usac = $data->usac;
                $racedata->id = $id;

                if (!in_array($racedata->placing,$notResult)) {
                    $data->data[] = $racedata;
                }
            }

            $teamdata[$id] = $data;
        }

        return $teamdata;
    }

    public static function get_riders() {
        $args = array('orderby' => 'login');
        $users = get_users($args);
        $arr = array();

        foreach ($users as $user) {
            $license = get_user_meta($user->ID,'usac_license');

            if ($license) {
                $info = new stdClass();
                $info->usac = $license[0];
                $info->id = $user->ID;

                $arr[] = $info;
            }
        }

        return $arr;
    }

    public static function get_saved_results() {
        $riders = resultsScraper::get_riders();
        $results = array();

        foreach ($riders as $rider) {
            $riderData = get_user_meta($rider->id,'results',true);
            $results[$riderData->id] = $riderData;
        }

        return $results;
    }

    public static function combined_results($year = false) {
        $results = resultsScraper::get_saved_results();
        $combinedResults = array();

        foreach($results as $rider) {
            if (is_array($rider->data)) {
                foreach ($rider->data as $result) {
                    if (!$year || $year == $result->year) {
                        $combinedResults[] = $result;
                    }
                }
            }
        }

        objectSort($combinedResults,'date');

        return array_reverse($combinedResults);
    }

    public static function combined_results_by_month($year) {
        $combined = resultsScraper::combined_results($year);
        $months = array();
        $results = new stdClass();
        $results->year = $year;
        $results->months = array();
        $results->races = 0;
        $results->podiums = 0;
        $results->wins = 0;
        $i = -1;

        foreach ($combined as $result) {
            if (!in_array($result->month,$months)) {
                $i++;
                $months[] = $result->month;
                $results->months[$i] = new stdClass();
                $results->months[$i]->month = $result->month;
                $results->months[$i]->results = array();
            }

            $result->rowClass = resultsScraper::oddEven(count($results->months[$i]->results));
            $result->win = $result->placing == 1 ? 'Win!' : '';
            $result->category = $result->category == '' ? '' : '| ' . $result->category;
            $results->months[$i]->results[] = $result;
            $results->races ++;
            $results->podiums = $result->placing <= 3 ? $results->podiums + 1 : $results->podiums;
            $results->wins = $result->placing == 1 ? $results->wins + 1 : $results->wins;
        }

        return $results;
    }

    public static function race_years() {
        $results = resultsScraper::combined_results();
        $years = array();

        foreach ($results as $result) {
            if (!in_array($result->year,$years)) {
                $years[] = $result->year;
            }
        }

        return $years;
    }

    public static function get_race_years() {
        return get_post_meta(108,'race_years',true);
    }

    public static function race_dropdown_data() {
        $years = resultsScraper::get_race_years();
        $arr = array();

        foreach ($years as $year) {
            $item = new stdClass();

            $item->title = $year . ' Race Results';
            $item->name = $year;

            $arr[] = $item;
        }

        $subjectDropdown = array(
            'className' => 'race_years',
            'title' => $years[0] . ' Race Results',
            'name' => 'race_years',
            'options' => $arr
        );

        return $subjectDropdown;
    }

    private function oddEven($count) {
        return $count % 2 ? 'evenRow' : 'oddRow';
    }
}