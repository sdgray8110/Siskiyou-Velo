<?php

class memberData {
    protected $activeMembers;

    private function setMemberData() {
        $svdb = new svdb;
        $this->activeMembers = $svdb->activeMembers();
    }

    public function generateJSON() {
        $this->setMemberData();
        $data = array();
        
        while ($row = mysql_fetch_assoc($this->activeMembers)) {
            $row['name'] = trim($row['firstname'] . ' ' . $row['lastname']);
            if ($row['DisplayAddress'] == 1 && $row['address']) {
                $row['googleMaps'] = $this->googleMapsLink($row);
            }
            $data[] = $row;
        }

        return json_encode($data,true);
    }

    public function googleMapsLink($row) {
        $cleanAddress = str_replace('#', 'Apt', $row['address']);
        $format = '<a target="_blank" href="http://maps.google.com/?q=%s+%s+%s+%s"><img title="View %s in Google Maps" alt="View %s in Google Maps" src="http://maps.google.com/mapfiles/kml/pal5/icon15.png" style="float:right; padding:3px;"/></a>';

        return sprintf($format,$cleanAddress,$row['city'],$row['state'],$row['zip'],$cleanAddress,$cleanAddress);
    }
}

?>