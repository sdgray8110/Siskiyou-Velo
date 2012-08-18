<?php

class officers {
    protected $officerData;
    protected $officerTypes;
    protected $officerRoles;
    protected $officer_roles;

    public function init() {
        $this->getOfficerData();
        $this->set_officer_types();
    }

    private function getOfficerData() {
        require_once($_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/classes/class.svdb.php');
        $svdb = new svdb();
        $this->officerData = $svdb->officers();
        $this->officerRoles = $svdb->get_officer_roles();
    }

    private function set_officer_types() {
        $types = array('President');
        $officerArray = array();
        $officerArray['President'] = array();

        while ($row = mysql_fetch_assoc($this->officerData)) {
            if (!in_array($row['officer'],$types)) {
                $types[] = $row['officer'];
                $officerArray[$row['officer']] = array();
            }

            if (!in_array($row['officer2'],$types)) {
                $types[] = $row['officer2'];
                $officerArray[$row['officer2']] = array();
            }

            if (!in_array($row['officer3'],$types)) {
                $types[] = $row['officer3'];
                $officerArray[$row['officer3']] = array();
            }          

            // Append Officer Data //
            $officerArray[$row['officer']][] = $row;

            if ($row['officer2']) {
                $officerArray[$row['officer2']][] = $row;
            }

            if ($row['officer3']) {
                $officerArray[$row['officer3']][] = $row;
            }
        }

        $this->officerData = $this->cleanEmails($officerArray);
        $this->officerTypes = $types;
    }

    private function cleanEmails($officers) {
        $officers['Webmaster'][0]['email1'] = 'webmaster@siskiyouvelo.org';

        return $officers;
    }

    public function get_position() {
        return $this->officerTypes;
    }

    public function get_officers() {
        return $this->officerData;
    }

    public function get_officer_roles() {
        return $this->officerRoles;
    }

    public function build_officer_select() {
        $format = '<option class="compare" value="%s">%s</option>';
        $selects = '';

        foreach ($this->officerRoles as $role) {
            $selects .= sprintf($format,$role,$role);
        }

        echo $selects;
    }
}

?>