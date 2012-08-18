<?php

class mainNav {
    private function getNav($file = '') {
        $file = !$file ? $_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-content/data/mainNav.json' : $file;
        $json = file_get_contents($file);

        return json_decode($json,true);
    }

    private function renderFlatList($links) {
        $markup = "";

        foreach ($links as $key => $val) {
            if (!is_array($val)) {
                $markup .= "<li><a href=\"".$val."\">".$key."</a></li>\n";
            } else {
                $markup .= $this->handleSpecialItems($key,$val);
            }
        }

        return $markup;
    }

    private function handleSpecialItems($key,$val) {
        $svdb = new svdb();
        if (!$svdb->emailSent()) {
            return "<li><a href=\"".$val['link']."\" class=\"".$val['class']."\">".$key."</a></li>\n";
        }

        return '';
    }

    private function newsletterNav() {
        $svdb = new svdb();
        $news = $svdb->newsletters();
        $markup = $this->loopNews($news);

        return $markup;
    }

    private function loopNews($news) {
        $markup = "";
        $position = 0;
        
        while ($row = mysql_fetch_array($news)) {
            $markup .= $this->newsletterMarkup($row,$position);
            $position ++;
        }

        return $markup;
    }

    private function newsletterMarkup($row,$i) {
        $markup = "";
        $markup .= "<li><a target='_blank' href='../images/Newsletters/PDF/" . $row["filename"] . "'>" . $row["issue"] . "</a>\n";
        $markup .= "\t<ul class='subNews" . $i . "'>\n";
        $markup .= "\t\t<li class=\"preview\">In This Issue:</li>\n";
        $markup .= "\t\t<li>" . $row["item1"] . "</li>\n";
        $markup .= "\t\t<li>" . $row["item2"] . "</li>\n";
        $markup .= $row["item3"] ? "\t\t<li>" . $row["item3"] . "</li>\n" : "";
        $markup .= "\t</ul>\n";
        $markup .= "</li>\n";

        return $markup;
    }

    public function renderNav($file = '') {
        $auth = new auth();
        $roles = $auth->init();

        $nav = $this->getNav($file);
        $markup = "";

        foreach ($nav as $key => $val) {
            $link = $nav[$key]["categoryID"];
            $class = $nav[$key]["class"];
            $type = $nav[$key]["type"];
            $renderOfficer = $type == "officer" && $roles['officer'];
            $renderMember = $type == "member" && $roles['member'];
            
            if ($type == "flat" || $renderOfficer || $renderMember) {
                $markup .= "<li>\n<a href=\"".$link."\">".$key."</a>";
                $markup .= "<ul class=\"".$class."\">" . $this->renderFlatList($nav[$key]["links"]) . "</ul>";
            }

            if ($type == "news") {
                $markup .= "<li>\n<a href=\"".$link."\">".$key."</a>";
                $markup .= "<ul class=\"".$class."\">" . $this ->newsletterNav() . "</ul>";
            }
        }

        $markup .= "</li>\n";

        echo $markup;
    }
}
?>