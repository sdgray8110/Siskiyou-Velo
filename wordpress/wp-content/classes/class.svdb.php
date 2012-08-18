<?php

class svdb {
    protected $defaultPW = '39b0d86a2144a5cba348f7ec1557e20e';
    protected $emailTemplatePath;

    private function init() {
        $this->set_template_path();

        if (!$credentials) {
            $credentials = array(
                'host'=>'localhost',
                'username'=>'gray8110',
                'password'=>'8aU_V{^{,RJC',
                'db' => 'gray8110_svblogs'
            );
        }

        return $credentials;
    }

    private function setConnection($credentials) {
        $connection = mysql_connect($credentials['host'],$credentials['username'],$credentials['password']);

        return $connection;

    }

    private function setDb() {
        $credentials = $this->init();
        $connection = $this->setConnection($credentials);

        mysql_select_db($credentials['db'],$connection);

        return array(
            "connection"=>$connection,
            "credentials"=>$credentials
        );
    }

    private function set_template_path() {
        $this->emailTemplatePath = $_SERVER['DOCUMENT_ROOT'] . '/email_templates/';
    }

    public function newsletters() {
        $connection = $this->setDb();
        $query = "SELECT * FROM sv_newsbrief ORDER BY ID DESC LIMIT 12";

        return mysql_query($query,$connection['connection']);
    }

    public function classifieds() {
        $connection = $this->setDb();
        $query = "SELECT * FROM classifieds WHERE timestamp >= (DATE_ADD(curdate(), INTERVAL -6 MONTH)) ORDER BY timestamp DESC";

        return mysql_query($query,$connection['connection']);
    }

    public function classified($id) {
        $connection = $this->setDb();
        $query = "SELECT * FROM classifieds WHERE ID = $id";

        return mysql_query($query,$connection['connection']);
    }

    public function login($email) {
        $connection = $this->setDb();
        $query = "SELECT * FROM wp_users WHERE email1 = '$email' LIMIT 1";

        return mysql_query($query,$connection['connection']);
    }

    public function emailSent() {
        $connection = $this->setDb();
        $query = "SELECT * FROM sv_newsbrief ORDER BY ID DESC LIMIT 1";
        $row = mysql_fetch_array(mysql_query($query,$connection['connection']));

        return $row['sent'] == 1;
    }

    public function activeMembers() {
        $connection = $this->setDb();
        $query = "SELECT firstname, lastname, email1, email2, address, city, state, zip, phone, DisplayAddress, DisplayContact FROM wp_users WHERE DateExpire >= (DATE_ADD(curdate(), INTERVAL -2 MONTH))  ORDER BY lastname";

        return mysql_query($query,$connection['connection']);
    }

    public function fullMemberHistory() {
        $connection = $this->setDb();
        $query = "SELECT * FROM wp_users ORDER BY lastname ASC";

        return mysql_query($query,$connection['connection']);
    }

    public function rideData($id) {
        $connection = $this->setDb();
        $query = "SELECT * FROM  sv_rides WHERE ID = '$id' LIMIT 1";

        return mysql_fetch_assoc(mysql_query($query,$connection['connection']));
    }

    public function postRideData($id,$data) {
        $connection = $this->setDb();
        $query = "UPDATE sv_rides SET ridedata = '$data' WHERE ID = $id";
        $execute = mysql_query($query,$connection['connection']);

        if (!$execute) {
            echo $query;
        } else {
            echo 'success';
        }
    }

    public function officers() {
        $connection = $this->setDb();
        $query = "SELECT * FROM wp_users WHERE officer != '' ORDER BY officer ASC";

        return mysql_query($query,$connection['connection']);
    }

    public function officer_roles() {
        $connection = $this->setDb();
        $query = "SELECT * FROM sv_officer_roles ORDER BY id ASC";

        return mysql_query($query,$connection['connection']);
    }

    public function membershipVP() {
        $connection = $this->setDb();
        $query = "SELECT * FROM wp_users WHERE officer = 'VP Membership' ORDER BY officer ASC LIMIT 1";
        
        return mysql_fetch_assoc(mysql_query($query,$connection['connection']));
    }

    public function get_membership_vp_name() {
        $membershipVP = $this->membershipVP();
        $format = '%s %s';

        return sprintf($format,$membershipVP['firstname'],$membershipVP['lastname']);
    }

    public function get_officer_roles() {
        $connection = $this->setDb();
        $query = "SELECT role FROM officerRoles ORDER BY role ASC";
        $result = mysql_query($query,$connection['connection']);
        $arr = array();

        while ($row = mysql_fetch_assoc($result)) {
            $arr[] = $row['role'];
        }

        return $arr;
    }

// Performance Metrics //
    public function get_performance_day($timestamp) {
        $connection = $this->setDb();
        $query = "SELECT * FROM performance WHERE timestamp = '$timestamp'";
        $result = mysql_query($query,$connection['connection']);
        $arr = array();

        while ($row = mysql_fetch_assoc($result)) {
            $arr[] = $row['data'];
        }

        if (count($arr)) {
            return $arr[0];
        }

        return false;

    }

    public function post_performance_update($timestamp, $data) {
        $existingData = $this->get_performance_day($timestamp);
        $connection = $this->setDb();

        if ($existingData) {
            $query = "UPDATE performance SET data = '$data' WHERE timestamp = '$timestamp'";
        } else {
            $query = "INSERT INTO performance (timestamp, data)VALUES ('$timestamp', '$data')";
        }

        return mysql_query($query,$connection['connection']);
    }


// Auto Email Region //
    public function renewal_reminder_email() {
        $connection = $this->setDb();
        $query = "SELECT * FROM wp_users WHERE DateExpire = (DATE_ADD(curdate(), INTERVAL +1 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY lastname";

        return mysql_query($query,$connection['connection']);
    }

    public function expiration_reminder_email() {
        $connection = $this->setDb();
        $query = "SELECT * FROM wp_users WHERE DateExpire = (DATE_ADD(curdate(), INTERVAL -1 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY lastname";

        return mysql_query($query,$connection['connection']);
    }

    public function renewal_email() {
        $connection = $this->setDb();
        $query = "SELECT * FROM wp_users WHERE DateRenewed = (DATE_ADD(curdate(), INTERVAL 0 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY lastname";

        return mysql_query($query,$connection['connection']);
    }

    public function join_email() {
        $connection = $this->setDb();
        $query = "SELECT * FROM wp_users WHERE DateJoined = (DATE_ADD(curdate(), INTERVAL 0 MONTH)) && email1 != '' && emailOptOut != '1' ORDER BY lastname";

        return mysql_query($query,$connection['connection']);
    }

    public function send_reminder_emails() {
        $result = $this->renewal_reminder_email();

        while ($emailRow = mysql_fetch_assoc($result)) {
            $email = $emailRow['email1'];
            $email2 = $emailRow['email2'];
            $name = $emailRow['firstname'].' '.$emailRow['lastname'];
            $expire = date('n/d/Y', strtotime($emailRow["DateExpire"]));
            $address = $emailRow['address'];
            $city = $emailRow['city'];
            $state = $emailRow['state'];
            $zip = $emailRow['zip'];
            $phone = $emailRow['phone'];
            $membershipEmail = 'membership@siskiyouvelo.org';
            $membershipVP = $this->get_membership_vp_name();
            $memberID = $emailRow['ID'];
            $file = $emailRow['user_pass'] == $this->defaultPW ? 'reminder_default.php' : 'reminder_custom.php';
            $sendto = $email.', '.$email2.', gray8110@gmail.com';

            include($file);

            mail($sendto, $subject, $message, $headers);
            echo '<p>Reminder Email Sent To: '.$sendto.'</p>';
        }
    }

    public function send_expiration_emails() {
        $result = $this->expiration_reminder_email();

        while ($emailRow = mysql_fetch_assoc($result)) {
            $email = $emailRow['email1'];
            $email2 = $emailRow['email2'];
            $name = $emailRow['firstname'].' '.$emailRow['lastname'];
            $expire = date('n/d/Y', strtotime($emailRow["DateExpire"]));
            $address = $emailRow['address'];
            $city = $emailRow['city'];
            $state = $emailRow['state'];
            $zip = $emailRow['zip'];
            $phone = $emailRow['phone'];
            $membershipEmail = 'membership@siskiyouvelo.org';
            $membershipVP = $this->get_membership_vp_name();
            $memberID = $emailRow['ID'];
            $file = $emailRow['user_pass'] == $this->defaultPW ? 'expire_default.php' : 'expire_custom.php';
            $sendto = $email.', '.$email2.', gray8110@gmail.com';

            include($file);

            mail($sendto, $subject, $message, $headers);
            echo '<p>Expiration Email Sent To: '.$sendto.'</p>';
        }
    }

    public function send_renewal_emails() {
        $result = $this->renewal_email();

        while ($emailRow = mysql_fetch_assoc($result)) {
            $email = $emailRow['email1'];
            $email2 = $emailRow['email2'];
            $name = $emailRow['firstname'].' '.$emailRow['lastname'];
            $expire = date('n/d/Y', strtotime($emailRow["DateExpire"]));
            $address = $emailRow['address'];
            $city = $emailRow['city'];
            $state = $emailRow['state'];
            $zip = $emailRow['zip'];
            $phone = $emailRow['phone'];
            $membershipEmail = 'membership@siskiyouvelo.org';
            $membershipVP = $this->get_membership_vp_name();
            $memberID = $emailRow['ID'];
            $file = $emailRow['user_pass'] == $this->defaultPW ? 'renewal_default.php' : 'renewal_custom.php';
            $sendto = $email.', '.$email2.', gray8110@gmail.com';

            include($file);

            mail($sendto, $subject, $message, $headers);
            echo '<p>Renewal Email Sent To: '.$sendto.'</p>';
        }
    }

    public function send_join_emails() {
        $result = $this->join_email();

        while ($emailRow = mysql_fetch_assoc($result)) {
            $email = $emailRow['email1'];
            $email2 = $emailRow['email2'];
            $name = $emailRow['firstname'].' '.$emailRow['lastname'];
            $expire = date('n/d/Y', strtotime($emailRow["DateExpire"]));
            $address = $emailRow['address'];
            $city = $emailRow['city'];
            $state = $emailRow['state'];
            $zip = $emailRow['zip'];
            $phone = $emailRow['phone'];
            $membershipEmail = 'membership@siskiyouvelo.org';
            $membershipVP = $this->get_membership_vp_name();
            $memberID = $emailRow['ID'];
            $file = 'newmember.php';
            $sendto = $email.', '.$email2.', gray8110@gmail.com';

            include($file);

            mail($sendto, $subject, $message, $headers);
            echo '<p>Join Email Sent To: '.$sendto.'</p>';
        }
    }

    public function addHazards($response) {
        echo $response['honeypot'];
    }
}

?>