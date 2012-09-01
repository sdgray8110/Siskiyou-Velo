<?php

class raceHelper {
    public function __construct($id) {
        $this->id = $id;
        $this->title = get_the_title($id);
        $this->location = ac_get_field('location', $id);
        $this->purse = ac_get_field('purse', $id);
        $this->date = $this->raceDate();
        $this->results = $this->get_results();
        $this->courseInfo = $this->get_course_info();
        $this->sponsors = ac_get_field('sponsors', $id);
    }

    private function raceDate() {
        $originalDate = ac_get_field('date', $this->id);
        $time = strtotime($originalDate);
        $date = new stdClass();
        $date->year = date('Y', $time);
        $date->formatted = $originalDate;

        return $date;
    }

    private function get_results() {
        $data = new stdClass();
        $data->link = ac_get_field('results_link', $this->id);
        $data->men = ac_get_field('mens_winner', $this->id);
        $data->women = ac_get_field('womens_winner', $this->id);

        return $data;
    }

    private function get_course_info() {
        $data = new stdClass();
        $data->length = ac_get_field('course_length', $this->id);
        $data->shortDescription = ac_get_field('short_course_description', $this->id);
        $data->fullDescription = ac_get_field('full_course_description', $this->id);
        $data->mapLink = ac_get_field('course_map_link', $this->id);
        $data->mapImage = $this->get_map_image();
        $data->course_length = ac_get_field('course_length', $this->id);

        return $data;
    }

    private function get_map_image() {
        $data = ac_get_field('course_map_image', $this->id);

        return $data['url'];
    }
}