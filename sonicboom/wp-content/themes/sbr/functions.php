<?php

add_action( 'after_setup_theme', 'sbr_setup' );

if ( ! function_exists( 'sbr_setup' ) ) {
    function sbr_setup() {
        $themeDir = get_stylesheet_directory();

        // Register Classes Here
        if (!class_exists('homepageHelper')) {
            require_once($themeDir . '/classes/class.homepageHelper.php');
        }

        if (!class_exists('sponsorHelper')) {
            require_once($themeDir . '/classes/class.sponsorHelper.php');
        }

        if (!class_exists('resultsScraper')) {
            require_once($themeDir . '/classes/class.usacResultsScraper.php');
        }

        if (!class_exists('blogHelper')) {
            require_once($themeDir . '/classes/class.blogHelper.php');
        }

        if (!class_exists('userHelper')) {
            require_once($themeDir . '/classes/class.userHelper.php');
        }

        if (!class_exists('markupHelper')) {
            require_once($themeDir . '/classes/class.markupHelper.php');
        }

        if (!class_exists('emailHelper')) {
            require_once($themeDir . '/classes/class.emailHelper.php');
        }

        if (!class_exists('strava')) {
            require_once($themeDir . '/classes/class.stravaHelper.php');
        }

        if (!class_exists('raceHelper')) {
            require_once($themeDir . '/classes/class.raceHelper.php');
        }


        // Add Scripts To The Footer
        add_action('wp_enqueue_scripts', 'footer_scripts');

        // Extra User Fields
        add_action( 'show_user_profile', 'extra_user_profile_fields' );
        add_action( 'edit_user_profile', 'extra_user_profile_fields' );
        add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
        add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );

        // Add Shortcodes
        add_shortcode('users', 'get_users_page');
        add_shortcode('contact', 'get_contact_page');
        add_shortcode('results', 'get_results_page');
        add_shortcode('strava', 'get_strava_page');
    }
}

function get_html_header() {
    require_once(get_stylesheet_directory() . '/includes/html_header.php');
}

function get_users_page() {
    require_once(get_stylesheet_directory() . '/includes/users.php');
}

function get_contact_page() {
    require_once(get_stylesheet_directory() . '/includes/contact.php');
}

function get_results_page() {
    require_once(get_stylesheet_directory() . '/ajax/results.php');
}

function get_strava_page() {
    $markupHelper = new markupHelper();
    $markupHelper->strava(get_the_ID());
}

if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus(
		array(
		  'main_menu' => 'Main Menu'
		)
	);
}

function objectSort(&$data, $key) {
    for ($i = count($data) - 1; $i >= 0; $i--) {
        $swapped = false;
        for ($j = 0; $j < $i; $j++) {
            if ($data[$j]->$key > $data[$j + 1]->$key) {
                $tmp = $data[$j];
                $data[$j] = $data[$j + 1];
                $data[$j + 1] = $tmp;
                $swapped = true;
            }
        }
        if (!$swapped) return;
    }
}

function number_suffix($number){

    // Validate and translate our input
    if ( is_numeric($number)){

        // Get the last two digits (only once)
        $n = $number % 100;

    }
    else {
        // If the last two characters are numbers
        if ( preg_match( '/[0-9]?[0-9]$/', $number, $matches )){

            // Return the last one or two digits
            $n = array_pop($matches);
        }
        else {

            // Return the string, we can add a suffix to it
            return $number;
        }
    }

    // Skip the switch for as many numbers as possible.
    if ( $n > 3 && $n < 21 )
        return $number . 'th';

    // Determine the suffix for numbers ending in 1, 2 or 3, otherwise add a 'th'
    switch ( $n % 10 ){
        case '1': return $number . 'st';
        case '2': return $number . 'nd';
        case '3': return $number . 'rd';
        default:  return $number . 'th';
    }
}

function footer_scripts() {
    $deps = array();
    $src = false;
    $ver = '1.0';
    $in_footer = true;
    $template_dir = get_stylesheet_directory_uri();

    // Register Scripts
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', $template_dir . '/js/lib/jquery.1.7.2.min.js');
    wp_register_script( 'template', $template_dir . '/js/lib/jquery.template.min.js');
    wp_register_script( 'youtube_feed', $template_dir . '/js/modules/youtube_feed.js');
    wp_register_script( 'jcarousel_lite', $template_dir . '/js/modules/jcarousel.lite.js');
    wp_register_script( 'fancybox', $template_dir . '/js/modules/jquery.fancybox.pack.js');
    wp_register_script( 'fauxSelect', $template_dir . '/js/modules/fauxSelect.js');
    wp_register_script( 'inlineLabel', $template_dir . '/js/modules/inlineLabel.js');
    wp_register_script( 'global', $template_dir . '/js/global.js');
    wp_register_script( 'home', $template_dir . '/js/home.js');
    wp_register_script( 'blog', $template_dir . '/js/blog.js');

    // Enqueue Scripts $handle, $src, $deps, $ver, $in_footer
    wp_enqueue_script( 'jquery',$src,$deps,$ver,$in_footer);
    wp_enqueue_script( 'template',$src,$deps,$ver,$in_footer);
    wp_enqueue_script( 'fancybox',$src,$deps,$ver,$in_footer);
    wp_enqueue_script( 'fauxSelect',$src,$deps,$ver,$in_footer);
    wp_enqueue_script( 'inlineLabel',$src,$deps,$ver,$in_footer);
    wp_enqueue_script( 'global',$src,$deps,$ver,$in_footer);

    // Page Specific JS
    get_page_scripts();
}

function get_page_scripts() {
    $deps = array();
    $src = false;
    $ver = '1.0';
    $in_footer = true;
    $template_dir = get_stylesheet_directory_uri();

    $page = is_home() ? 'home' : 'blog';

    switch($page) {
        case 'home':
            wp_enqueue_script( 'youtube_feed',$src,$deps,$ver,$in_footer);
            wp_enqueue_script( 'jcarousel_lite',$src,$deps,$ver,$in_footer);
            wp_enqueue_script( 'home',$src,$deps,$ver,$in_footer);
            break;

        case 'blog':
            wp_enqueue_script( 'blog',$src,$deps,$ver,$in_footer);
            break;
    }
}

function extra_user_profile_fields( $user ) {
    $usac_license = get_the_author_meta( 'usac_license', $user->ID );
    $short_bio = get_the_author_meta( 'short_bio', $user->ID );
    $category = get_the_author_meta( 'road_category', $user->ID );
    $elite_team = get_the_author_meta( 'elite_team', $user->ID );
    $masters = get_the_author_meta( 'masters', $user->ID );

?>
<h3><?php _e("Extra profile information", "blank"); ?></h3>

<table class="form-table">
    <tr>
        <th><label for="usac_license"><?php _e("USAC License Number"); ?></label></th>
        <td>
            <input type="text" name="usac_license" id="usac_license" value="<?php echo esc_attr( $usac_license ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter your USAC License Number."); ?></span>
        </td>
    </tr>

    <tr>
        <th><label for="short_bio"><?php _e("Rider Bio"); ?></label></th>
        <td>
            <input type="text" maxlength="75" name="short_bio" id="short_bio" value="<?php echo esc_attr( $short_bio ); ?>" class="regular-text" /><br />
            <span class="description"><?php _e("Please enter a (very) brief rider bio. Max 75 characters."); ?></span>
        </td>
    </tr>

    <tr>
        <th><label for="road_category"><?php _e("Road Category"); ?></label></th>
        <td>
            <select id="road_category" name="road_category">
                <option value="">Select Category</option>
                <option<?=selected_input($category,1,'selected');?> value="1">Cat 1</option>
                <option<?=selected_input($category,2,'selected');?> value="2">Cat 2</option>
                <option<?=selected_input($category,3,'selected');?> value="3">Cat 3</option>
                <option<?=selected_input($category,4,'selected');?> value="4">Cat 4</option>
            </select>
        </td>
    </tr>

    <tr>
        <th><label for="elite_team"><?php _e("Elite Team"); ?></label></th>
        <td>
            <input<?=selected_input($elite_team,1);?> type="checkbox" name="elite_team" id="elite_team" value="1" />
        </td>
    </tr>

    <tr>
        <th><label for="masters"><?php _e("Masters 35+"); ?></label></th>
        <td>
            <input<?=selected_input($masters,1);?> type="checkbox" name="masters" id="masters" value="1" />
        </td>
    </tr>
</table>
<?php }

function save_extra_user_profile_fields( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    update_user_meta( $user_id, 'usac_license', $_POST['usac_license'] );
    update_user_meta( $user_id, 'short_bio', $_POST['short_bio'] );
    update_user_meta( $user_id, 'road_category', $_POST['road_category'] );
    update_user_meta( $user_id, 'elite_team', $_POST['elite_team'] );
    update_user_meta( $user_id, 'masters', $_POST['masters'] );

    return true;
}

function selected_input($val,$compare,$str = 'checked') {
    if ($val == $compare) {
        $fmt = ' %s="true"';
        return sprintf($fmt,$str);
    }

    return '';
}

function formatDate($date,$fmt) {
    $utc_date = DateTime::createFromFormat('Y-m-d G:i:s', $date, new DateTimeZone('UTC'));
    $local_date = $utc_date;
    $local_date->setTimeZone(new DateTimeZone('America/Denver'));

    return $local_date->format($fmt);
}

function truncateMarkup($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                    // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length+$content_length> $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1]+1-$entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left+$entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if($total_length>= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}

function browser() {
    $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
    // you can add different browsers with the same way ..
    if(preg_match('/(chromium)[ \/]([\w.]+)/', $ua))
        $browser = 'chromium';
    elseif(preg_match('/(chrome)[ \/]([\w.]+)/', $ua))
        $browser = 'chrome';
    elseif(preg_match('/(safari)[ \/]([\w.]+)/', $ua))
        $browser = 'safari';
    elseif(preg_match('/(opera)[ \/]([\w.]+)/', $ua))
        $browser = 'opera';
    elseif(preg_match('/(msie)[ \/]([\w.]+)/', $ua))
        $browser = 'msie';
    elseif(preg_match('/(firefox)[ \/]([\w.]+)/', $ua))
        $browser = 'firefox';

    preg_match('/('.$browser.')[ \/]([\w]+)/', $ua, $version);

    return array($browser,$version[2], 'name'=>$browser,'version'=>$version[2]);
}

function backgroundSizeSupported() {
    $browser = browser();
    $threshold = array(
        'firefox' => 4,
        'msie' => 9,
        'chrome' => 3,
        'safari' => 4.1,
        'opera' => 10
    );

    return $browser['version'] >= $threshold[$browser['name']];
}

?>