<?php
class raceHelper {
    public function __construct($id) {
        $this->id = $id;
        $this->title = get_the_title($id);
        $this->tabs = $this->get_tab_data();
        $this->rightNav = $this->get_right_nav();
        $this->ajaxURL = get_stylesheet_directory_uri() . '/includes/race_page_content.php';
        $this->sponsors = $this->get_sponsors();
    }

    private function get_sponsors() {
        $sponsorIds = get_post_meta($this->id,'sponsors');
        $sponsors = array();

        foreach ($sponsorIds as $id) {
            $sponsor = new stdClass();
            $sponsor->id = $id;
            $sponsor->title = get_the_title($id);
            $sponsor->url = $this->get_sponsor_url($id);
            $sponsor->logo = $this->get_sponsor_logo($id);

            $sponsors[] = $sponsor;
        }

        shuffle($sponsors);

        return $sponsors;
    }

    private function get_sponsor_url($id) {
        $groupData = get_group('data', $id);

        return $groupData[1]['data_url'][1];
    }

    private function get_sponsor_logo($id) {
        $groupData = get_group('data', $id);

        return $groupData[1]['data_logo'][1]['thumb'];
    }

    private function get_right_nav() {
        $groupData = get_group('right_nav', $this->id);
        $groups = array();

        foreach ($groupData as $group) {
            $data = new stdClass();
            $data->title = $group['right_nav_title'][1];
            $data->items = $this->right_nav_items($group['right_nav_item_title'],$group['right_nav_item_content']);

            $groups[] = $data;
        }

        return $groups;
    }

    private function right_nav_items($titles,$contents) {
        $items = array();
        for ($i=1;$i<=count($titles);$i++) {
            $item = new stdClass();
            $item->title = $titles[$i];
            $item->content = $contents[$i];

            $items[] = $item;
        }

        return $items;
    }

    public function main_content_include_path($template) {
        $fmt = get_template_directory() . '/includes/templates/%s.php';
        $template = $this->templateName($template);
        $path = '';

        $path = sprintf($fmt,$template);

        return $path;
    }

    private function templateName($template) {
        switch($template) {
            case '':
                return 'overview';
            case 'register':
                return 'register';
            default:
                return 'generic';
        }
    }

    private function  get_tab_data() {
        $genericTabs = $this->genericTabs();
        $tabs = array(
            'overview' => $this->overviewTab(),
            'register' => $this->registerTab()
        );

        foreach ($genericTabs as $tab) {
            $tabs[$tab->slug] = $tab;
        }

        return $tabs;
    }

    private function overviewTab() {
        $tab = new stdClass();
        $tab->title = 'Overview';
        $tab->slug = 'overview';
        $tab->content = pageHelper::get_content($this->id);

        return $tab;
    }

    private function registerTab() {
        $tab = new stdClass();
        $tab->title = 'Register';
        $tab->slug = 'register';
        $tab->content = $this->get_registration_data();

        return $tab;
    }

    private function genericTabs() {
        $groupData = get_group('tabs',$this->id);
        $tabs = array();

        foreach ($groupData as $item) {
            $tab = new stdClass();
            $tab->title = $item['tabs_title'][1];
            $tab->slug = $item['tabs_slug'][1];
            $tab->content = $item['tabs_content'][1];

            $tabs[$tab->slug] = $tab;
        }

        return $tabs;
    }

    private function get_registration_data() {
        $groupData = get_group('registration',$this->id);
        $data = new stdClass();
        $data->fees = $this->registration_fee_data($groupData[1]['registration_fee_title'],$groupData[1]['registration_fee_description']);
        $data->link = $groupData[1]['registration_online_registration_link'][1];
        $data->form = $groupData[1]['registration_registration_form'][1];

        return $data;
    }

    private function registration_fee_data($title,$description) {
        $fees = array();
        for ($i=1;$i<=count($title);$i++) {
            $fee = new stdClass();
            $fee->title = $title[$i];
            $fee->description = $description[$i];

            $fees[] = $fee;
        }

        return $fees;
    }

}