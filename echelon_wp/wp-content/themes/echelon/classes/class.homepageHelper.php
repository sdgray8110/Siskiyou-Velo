<?php
class homepageHelper {
    public function __construct($id) {
        $this->id = $id;
        $this->title = get_the_title($id);
        $this->content = pageHelper::get_content($id);
        $this->photos = $this->get_photo_gallery();
        $this->announcements = $this->get_announcements();
    }

    private function get_photo_gallery() {
        $groupData = get_group('data', $this->id);
        $photos = $groupData[1]['data_photos'];
        shuffle($photos);

        return $photos;
    }

    private function get_announcements() {
        $groupData = get_group('announcement', $this->id);
        $announcements = array();

        foreach ($groupData as $announcement) {
            $data = new stdClass();
            $data->headline = $announcement['announcement_headline'][1];
            $data->description = $announcement['announcement_description'][1];
            $data->link = $announcement['announcement_link'][1] ? $announcement['announcement_link'][1] : '/';
            $data->icon = $announcement['announcement_icon'][1]['thumb'];

            $announcements[] = $data;
        }

        return array_reverse($announcements);
    }

}