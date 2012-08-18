<?php
$getPosition = $_GET['position'] * 4;

//Connect to database
$connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");
if (!$connection) {
	die ("Database connection failed: " . mysql_error());
}

$db_select = mysql_select_db("gray8110_etna",$connection);

if (!$db_select) {
	die("Database selection failed: " . mysql_error());
}

function clickUrl($text) {
    $linkRegEx = '/((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/';
    $hashRegEx = '/#([^ ]+)/';
    $userRegEx = '/@([^ )]+)/';
    $text = preg_replace($linkRegEx, '<a target="_blank" href="$1">$1</a>', $text);
    $text = preg_replace($hashRegEx, '<a target="_blank" class="hash" href="http://twitter.com/#search?q=%23$1">#$1</a>', $text);
    $text = preg_replace($userRegEx, '@<a target="_blank" href="http://www.twitter.com/$1">$1</a>', $text);
    return $text;
}

if (!$getPosition) {
    $qry = mysql_query("SELECT * FROM twitter ORDER BY date DESC LIMIT 4", $connection);
    $current = 1;
    $next = '<a href="http://etna-desalvo.com/includes/twitter.php?position=1">Next</a> |';

} else {
    $qry = mysql_query("SELECT * FROM twitter ORDER BY date DESC LIMIT $getPosition,4", $connection);
    $current = ($getPosition / 4) + 1;
    $prev = $current - 2;
        $prev = '<a href="http://etna-desalvo.com/includes/twitter.php?position='.$prev.'">Prev</a> | ';
    $next = $current;
        if ($next > 15) {$next = 0;};
        $next = '<a href="http://etna-desalvo.com/includes/twitter.php?position='.$next.'">Next</a> | ';
}

$n = 1;
while ($row = mysql_fetch_array($qry)){
    $date = date('n/j/Y g:ia', $row[date]);
    $name = $row[name];
    $source = $row[source];
    $textValue = clickUrl($row[text]);
    $thumb = $row[thumb];
    $replyTo = $row[reply_to_name];
    $replyPost = $row[reply_to_id];

    echo '
        <li class="row'.$n.'">
            <img src="'.$thumb.'" alt="'.$name.'" />
                <div>
                    <p><a class="author" href="http://www.twitter.com/'.$name.'">'.$name.'</a>: '.$textValue.'</p>
                    <p class="timestamp">'.$date.' via '.$source;
                        if ($replyTo && $replyPost) {
                            echo ' in reply to <a target="_blank" href="http://twitter.com/'.$replyTo.'/statuses/'.$replyPost.'">'.$replyTo.'</a>';
                        }
                    echo '</p>
                </div>
        </li>
        ';
    $n++;
}
    echo '
        <li class="row'.$n.'" id="twitNav">
            <p class="timestamp">Page '.$current.' of 15 | '.$prev . $next .' <a href="">Pause</a></p>
        </li>'
?>