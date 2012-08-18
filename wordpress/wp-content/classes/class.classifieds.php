<?php
class classifieds {
    protected $classifieds;
    protected $noImageThumb = '/images/classifieds/no_image.gif';
    protected $thumbWidth = 100;
    protected $thumbHeight = 100;
    protected $fullImgWidth = 600;
    protected $fullImgHeight = 600;
    protected $noPriceString = 'Not Provided';
    protected $dateFormat = 'M, d Y';

    private function setClassifieds() {
        require_once('class.svdb.php');
        $svdb = new svdb();

        $this->classifieds = $svdb->classifieds();
    }

    private function setClassified($id) {
        require_once('class.svdb.php');
        $svdb = new svdb();

        $this->classifieds = $svdb->classified($id);
    }

    private function thumbnailURL($image,$id) {
        return !$image ? $this->noImageThumb : $this->resizedThumbURL($image,$id);
    }

    private function resizedThumbURL($image,$id) {
        $format = "/image.php/classified%s.jpg?width=%s&height=%s&image=http://www.siskiyouvelo.org/images/classifieds/%s";

        return sprintf($format,$id,$this->thumbWidth,$this->thumbHeight,$image);
    }

    private function resizedFullImageURL($image,$id) {
        $format = "/image.php/classified%s.jpg?width=%s&amp;height=%s&amp;image=http://www.siskiyouvelo.org/images/classifieds/%s";

        return sprintf($format,$id,$this->fullImgWidth,$this->fullImgHeight,$image);
    }

    private function postDate($timestamp) {
        $timestamp = strtotime($timestamp);
        return date($this->dateFormat, $timestamp);
    }

    private function editablePost($row) {
        $auth = new auth();
        $roles = $auth->init();
        if ($row['member_id'] = $roles['member_id'] || $roles['officer']) {
            $editText = !$roles['officer'] ? 'Edit Entry' : 'Officer Moderation';
            $format = ' | <a href="classifieds_edit.php?id=%s">%s</a> | <a class="confirmLink" rel="Are you sure you wish to delete his entry?" href="classifieds_edit.php?delete=yes&id=%s">Delete Entry</a>';

            return sprintf($format,$row['ID'],$editText,$row['ID']);
        }

        return '';
    }

    public function result($id) {
        if ($id) {
            $this->setClassified($id);
        } else {
            $this->setClassifieds();
        }

        return $this->classifieds;
    }

    public function imageLink($row) {
        $image = $this->thumbnailURL($row["image"],$row["ID"]);
        $format = '<a href="classified.php?id=%s"><img src="%s" alt="%s" /></a> ';

        return sprintf($format, $row["ID"],$image,$row["item"]);
    }

    public function fullImageLink($row) {
        if ($row['image']) {
            $format = '<a rel="lightbox" title="%s" href="images/classifieds/%s"><img src="%s" alt="%s" /></a>';

            return sprintf($format,$row['item'], $row['ID'], $this->resizedFullImageURL($row['image'],$row['ID']),$row['item']);
        }

        return '';
    }

    public function headerLink($row) {
        $format = '<a href="/classified.php?id=%s">%s</a>';

        return sprintf($format,$row["ID"],$row["item"]);
    }

    public function shortCopy($copy) {
        return String::truncate($copy,200);
    }

    public function price($price) {
        return !$price ? $this->noPriceString : $price;
    }

    public function emailLink($row) {
        $format = '<a href="mailto:%s?subject=Siskiyou Velo Classifieds: Inquiry regarding %s">%s</a>';

        return sprintf($format,$row["email"],$row["item"],$row["name"]);
    }

    public function postInfo($row) {
        $format = '<strong>Posted by</strong>:  %s on %s%s';

        return sprintf($format,$this->emailLink($row), $this->postDate($row["timestamp"]),$this->editablePost($row));
    }
}
?>
