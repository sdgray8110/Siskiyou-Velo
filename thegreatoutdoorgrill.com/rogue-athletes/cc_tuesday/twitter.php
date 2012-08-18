<?php
class twitter
{
    protected $api_path, $username, $followers, $followerCt, $url, $message;
    public $tweets;

    public function __construct($username, $message, $followerCt) {
        $this->setVars($username, $message, $followerCt);
        $this->constructTweets();
    }

    private function setVars($username, $message, $followerCt) {
        $this->api_path = 'https://api.twitter.com/1';
        $this->username = $username;
        $this->followerCt = $followerCt;
        $this->followers = $this->getFollowers();
        $this->url = 'http://goo.gl/HygSe';
        $this->tweets = array();
        $this->message = $message;
    }

    private function getFollowers() {
        $path = $this->api_path . '/followers/ids.json?cursor=-1&screen_name=' . $this->username;
        $followerIDs = json_decode(file_get_contents($path));
        $randomized = $this->randomize($followerIDs->ids);

        return $this->getUserNames($randomized);
    }

    private function getUserNames($arr) {
        $users = implode(',',$arr);
        $path = $this->api_path . '/users/lookup.json?user_id=' . $users;
        $feed = json_decode(file_get_contents($path));

        return $this->setUserNames($feed);
    }

    private function setUserNames($arr) {
        $userNames = array();

        foreach ($arr as $user) {
            $userNames[] = '@' . $user->screen_name;
        }

        return $userNames;
    }

    private function randomize($arr) {
        shuffle($arr);
        $arr = $this->trimArray($arr);

        return $arr;
    }

    private function trimArray($arr) {
        $newArr = array();

        for ($i=0;$i<$this->followerCt;$i++) {
            $newArr[] = $arr[$i];
        }

        return $newArr;
    }

    private function constructTweets() {
        $availableChars = $this->availableChars();
        $str = '';
        $i = 1;

        foreach ($this->followers as $follower) {
            if (strlen($str) + strlen(' ' . $follower) <= $availableChars) {
                $str .= ' ' . $follower;
            } else {
                $this->tweets[] = $this->constructTweet($str);
                $str = ' ' . $follower;
            }

            if ($this->followerCt == $i) {
                $this->tweets[] = $this->constructTweet($str);
            }

            $i ++;
        }
    }

    private function constructTweet($str) {
        $pattern = '%s%s %s';

        return sprintf($pattern,$this->message,$str,$this->url);
    }

    private function availableChars() {
        return 140 - (strlen($this->url) + strlen($this->message) + 1);
    }
}
