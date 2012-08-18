<?php
/*
Simple:Press
Admin Panels - Options/Components Tab Rendering Support
$LastChangedDate: 2012-05-06 14:27:11 -0700 (Sun, 06 May 2012) $
$Rev: 8502 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# == PAINT ROUTINES

# ------------------------------------------------------------------
# spa_paint_options_init()
# Initializes the tab index sequence starting with 100
# ------------------------------------------------------------------
function spa_paint_options_init() {
	global $tab;
	$tab = 100;
}

# ------------------------------------------------------------------
# spa_paint_open_tab()
# Creates the containing block around a form or main section
# ------------------------------------------------------------------
function spa_paint_open_tab($tabname, $full=false) {
	echo "<div class='sfform-panel'>";
	echo "<div class='sfform-panel-head'><span class='sftitlebar'>$tabname</span></div>\n";

	echo "<table width='100%' cellpadding='0' cellspacing='0'>\n";
	echo "<tr valign='top'>\n";
    if ($full)
    	echo "<td colspan='2' width='50%'>\n";
    else
    	echo "<td width='50%'>\n";
}

# ------------------------------------------------------------------
# spa_paint_options_init()
# Initializes the tab index sequence starting with 100
# ------------------------------------------------------------------
function spa_paint_close_tab() {
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</div>\n";
}

function spa_paint_tab_right_cell() {
	echo "</td>\n";
	echo "<td width='50%'>\n";
}

function spa_paint_open_panel() {
	echo "<table width='100%'>\n";
}

function spa_paint_close_panel() {
	echo "</table>\n";
}

function spa_paint_open_fieldset($legend, $displayhelp=false, $helpname='', $opentable=true) {
	global $adminhelpfile;

	echo "<tr>\n";
	echo "<td>\n";
	echo "<fieldset class='sffieldset'>\n";
	echo "<legend><strong>$legend</strong></legend>\n";
	if ($displayhelp) echo spa_paint_help($helpname, $adminhelpfile);
	if ($opentable) echo "<table class='form-table' width='100%'>\n";
}

function spa_paint_close_fieldset($closetable=true) {
	if ($closetable) echo "</table>\n";
	echo "</fieldset>\n";
	echo "</td>\n";
	echo "</tr>\n";
}

function spa_paint_input($label, $name, $value, $disabled=false, $large=false) {
	global $tab;

	echo "<tr valign='top'>\n";
	if ($large) {
		echo "<td class='sflabel' width='40%'>\n";
	} else {
		echo "<td class='sflabel' width='60%'>\n";
	}
	echo "<span class='sfalignleft'>$label:</span>";

	echo "</td>\n";
	echo "<td>\n";
	echo '<input type="text" class="sfpostcontrol" tabindex="'.$tab.'" name="'.$name.'" value="'.esc_attr($value).'" ';
	if ($disabled == true) echo "disabled='disabled' ";
	echo "/></td>\n";
	echo "</tr>\n";
	$tab++;
}

function spa_paint_file($label, $name, $disabled, $large, $path) {
	global $tab;

	echo "<tr valign='top'>\n";
	if ($large) {
		echo "<td class='sflabel' width='30%' valign='top' >\n";
	} else {
		echo "<td class='sflabel' width='50%'>\n";
	}
	echo $label.":</td>\n";
	echo "<td>\n";
	if (is_writable($path)) {
		echo '<div id="sf-upload-button" class="sfform-upload-button">'.spa_text('Browse').'</div>';
		echo '<div id="sf-upload-status"></div>';
	} else {
		echo '<div id="sf-upload-button" class="sfform-upload-button sfhidden"></div>';
		echo '<div id="sf-upload-status">';
		echo '<p class="sf-upload-status-fail">'.spa_text('Sorry, uploads disabled! Storage location does not exist or is not writable. Please see forum - integration - storage locations to correct').'</p>';
		echo '</div>';
	}
	echo "</td>\n";

	echo "</tr>\n";
	$tab++;
}

function spa_paint_hidden_input($name, $value) {
	echo "<tr style='display:none'><td>\n";
	echo "<input type='hidden' name='$name' value='".esc_attr($value)."' />";
	echo "</td></tr>\n";
}

function spa_paint_textarea($label, $name, $value, $submessage='', $rows=1) {
	global $tab;

	echo "<tr valign='top'>\n";
	echo "<td class='sflabel' width='60%'>\n$label";
	if(!empty($submessage)) echo "<br /><small><strong>".esc_html($submessage)."</strong></small>\n";
	echo "</td>\n";
	echo "<td>\n";
	echo "<textarea rows='$rows' cols='80' class='sftextarea' tabindex='$tab' name='$name'>".esc_html($value)."</textarea>\n";
	echo "</td>\n";
	echo "</tr>\n";
	$tab++;
}

function spa_paint_wide_textarea($label, $name, $value, $submessage='', $xrows=1) {
	global $tab;

	echo "<tr valign='top'>\n";
	echo "<td class='sflabel' width='100%' colspan='2'>\n$label:";
	if (!empty($submessage)) echo "<small><br /><strong>$submessage</strong><br /><br /></small>\n";
	echo "</td></tr><tr><td colspan='2'>";
	echo "<textarea rows='$xrows' cols='80' class='sftextarea' tabindex='$tab' name='$name'>".esc_attr($value)."</textarea>\n";
	echo "</td>\n";
	echo "</tr>\n";
	$tab++;
}

function spa_paint_checkbox($label, $name, $value, $disabled=false, $large=false, $displayhelp=true) {
	global $tab;

	echo "<tr valign='top'>\n";

	echo "<td class='sflabel' width='100%' colspan='2'>\n";
	echo '<table class="form-table table-cbox"><tr><td class="td-cbox">';
	echo "<label for='sf-$name'>$label</label>\n";
	echo "<input type='checkbox' tabindex='$tab' name='$name' id='sf-$name' ";
	if ($value == true) echo "checked='checked' ";
	if ($disabled == true) echo "disabled='disabled' ";
	echo "/>\n";
	echo '</td></tr></table>';
	echo "</td>\n";
	echo "</tr>\n";
	$tab++;
}

function spa_paint_radiogroup($label, $name, $values, $current, $large=false, $displayhelp=true) {
	global $tab;

	$pos = 1;

	echo "<tr valign='top'>\n";
	echo "<td class='sflabel' width='100%' colspan='2'>\n";
	echo '<table class="form-table table-cbox"><tr><td class="td-cbox">';
	echo $label;
	echo ":\n</td>\n";
	echo "<td width='70%' class='td-cbox'>\n";
	foreach ($values as $value) {
		$check = '';
		if ($current == $pos) $check = ' checked="checked" ';
		echo '<label for="sfradio-'.$tab.'" class="sflabel radio">'.esc_html(spa_text($value)).'</label>'."\n";
		echo '<input type="radio" name="'.$name.'" id="sfradio-'.$tab.'"  tabindex="'.$tab.'" value="'.$pos.'" '.$check.' />'."\n";
		$pos++;
		$tab++;
	}
	echo "</td></tr></table>";
	echo "</td>\n";
	echo "</tr>\n";
	$tab++;
}

function spa_paint_radiogroup_confirm($label, $name, $values, $current, $msg, $large=false, $displayhelp=true) {
	global $tab;

	$pos = 1;

	echo "<tr valign='top'>\n";
	echo "<td class='sflabel' width='100%' colspan='2'>\n";
	echo '<table class="form-table table-cbox"><tr><td class="td-cbox">';
	echo $label;
	echo ":\n</td>\n";
	echo "<td width='70%' class='td-cbox'>\n";
	foreach ($values as $value) {
		$check = '';
		$select = '';
		if ($current == $pos) {
			$check = " checked = 'checked' ";
		} else {
			$select = " onclick ='spjToggleLayer(\"confirm-".$name."\")'";
		}
		echo "<input type='radio' id='sfradio$pos' name='$name' tabindex='$tab' value='$pos'$check.$select />";
		echo "<label class='sflabel radio' for='sfradio$pos'>".esc_html(spa_text($value)).'</label><br />';
		$pos++;
		$tab++;
	}
	echo '</td></tr></table>';
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr valign='top' id='confirm-$name' style='display:none'>";
		echo '<td colspan="2">';
			echo '<table class="form-table table-cbox">';
				echo '<tr>';
					echo "<td class='longmessage'>$msg</td>";
					echo '</tr><tr>';
					echo "<td class='sflabel' width='100%'>";
					echo '<table class="form-table table-cbox"><tr><td class="td-cbox">';
					echo "<label for='sfconfirm-box-$name'>".esc_html(spa_text('Confirm'))."</label>\n";
					echo "<input type='checkbox' name='confirm-box-$name' id='sfconfirm-box-$name' tabindex='$tab' />\n";
					echo '</td></tr></table>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		echo '</td>';
	echo '</tr>';
	$tab++;
}

function spa_paint_select_start($label, $name, $helpname) {
	global $tab;

	echo "<tr valign='top'>\n";
	echo "<td class='sflabel' width='60%'>\n$label:";
	echo "\n</td>\n";
	echo "<td>\n";
	echo "<select style='width:130px' class=' sfacontrol' tabindex='$tab' name='$name'>\n";
	$tab++;
}

function spa_paint_select_end() {
	echo "</select>\n";
	echo "</td>\n";
	echo "</tr>\n";
}

function spa_paint_link($link, $label) {
	echo "<tr>\n";
	echo "<td class='sflabel' colspan='2'>\n";
	echo "<a href='".esc_url($link)."'>$label</a>\n";
	echo "</td>\n";
	echo "</tr>\n";
}

function spa_paint_spacer() {
	echo '<br /><div class="clearboth"></div>';
}

function spa_paint_color_input($label, $name, $value, $tip=true) {
	global $tab, $tooltips;

	echo '<tr valign="top">';
	echo '<td class="sflabel" width="40%">';

	echo '<span class="sfalignleft">';
	if ($tip) echo '<img align="absmiddle" class="vtip" title="'.$tooltips[$name].'" style="border: 0pt none ; margin: 0pt 0pt 0pt 3px;" src="'.SFADMINIMAGES.'sp_Information.png" alt="" />';
	echo '&nbsp;&nbsp;'.$label.":</span>";

	echo '</td>';
	echo '<td width="100px">';
	echo '<input type="text" class="iColorPicker" tabindex="'.$tab.'" name="'.$name.'" id="'.$name.'" value="#'.esc_attr($value).'" />';
	echo '</td>';
	echo '</tr>';
	$tab++;
}

function spa_paint_help($name, $helpfile, $show=true) {
    $site = SFHOMEURL.'index.php?sp_ahah=help&amp;sfnonce='.wp_create_nonce('forum-ahah')."&amp;file=$helpfile&amp;item=$name";
    $title = spa_text('Simple:Press Help');
	$out = '';

	$out.= '<div class="sfhelplink">';
	if ($show) {
		$out.= '<a id="'.$name.'" class="button button-highlight sfhelplink " href="javascript:void(null)" onclick="spjDialogAjax(this, \''.$site.'\', \''.$title.'\', 600, 0, 0);">';
		$out.= spa_text('Help').'</a>';
	}
	$out.= '</div>';
    return $out;
}

function splice($text, $pos=10, $method) {
	$label = array();
	$label = explode(' ', $text);
	switch($method) {
		case 0:
			$rep ='&#x0A;';
			break;
		case 1:
			$rep ='<br />';
			break;
		case 2:
			$rep = "\n";
	}
	$label[$pos].= $rep;
	$text = implode(' ', $label);
	return str_replace($rep.' ', $rep, $text);
}

?>