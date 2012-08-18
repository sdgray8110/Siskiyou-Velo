<?php
class auth {
    protected $currentPage;
    protected $roles;

    public function init() {
        session_start();
        $this->currentPage = $this->currentPageName();

        return $this->setRoles();
    }

    private function currentPageName() {
        return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
    }

    private function setRoles() {
        session_start();
        $this->roles["member"] = (isset($_SESSION['SESS_MEMBER_ID'])) && (trim($_SESSION['SESS_MEMBER_ID']) != '');
        $this->roles["officer"] = (isset($_SESSION['SESS_OFFICER'])) && (trim($_SESSION['SESS_OFFICER']) != '');
        $this->roles["member_id"] = $_SESSION['SESS_MEMBER_ID'];
        $this->roles["title"] = $_SESSION['SESS_TITLE'];
        $this->roles["firstname"] = $_SESSION['SESS_FIRST_NAME'];
        $this->roles["lastname"] = $_SESSION['SESS_LAST_NAME'];
        $this->roles["email"] = $_SESSION['SESS_EMAIL'];

        return $this->roles;
    }

    private function validLogin($user,$pw) {
        while ($row = mysql_fetch_array($user)) {
            if ($pw === $row['user_pass']) {
                return $row;
            }
        }

        return false;
    }

    private function loginSuccessRedirect($url) {
        $url = !$url ? '/' : $url;

        header("location: " . $url);
    }

    private function setSession($user) {
        session_start();
        session_regenerate_id();
        
        $_SESSION['SESS_MEMBER_ID'] = $user['ID'];
        $_SESSION['SESS_OFFICER'] = $user['officer'];
        $_SESSION['SESS_TITLE'] = $user['officer'] ? $user['officer'] : 'Club Member';
        $_SESSION['SESS_FIRST_NAME'] = $user['firstname'];
        $_SESSION['SESS_LAST_NAME'] = $user['lastname'];
        $_SESSION['SESS_EMAIL'] = $user['email1'];

        session_write_close();
    }

    public function memberAccessDenied() {
        if (!$this->roles["member"]) {
            header("location: /access-denied.php");
        }
    }

    public function officerAccessDenied() {
        if (!$this->roles["officer"]) {
            header("location: /access-denied-officers.php");
        }
    }

    public function login($email,$pw,$url='') {
        require_once('class.svdb.php');
        $svdb = new svdb;
        $result = $svdb->login($email);
        $pw = md5($pw);
        $user = $this->validLogin($result,$pw);

        if ($user) {
            $this->setSession($user);
            $this->loginSuccessRedirect($url);
        } else {
            $this->memberAccessDenied();
        }

    }
}