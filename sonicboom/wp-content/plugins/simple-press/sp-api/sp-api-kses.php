<?php
/*
Simple:Press
KSES - Alowed Forum Post Tags
$LastChangedDate: 2012-04-11 06:04:32 -0700 (Wed, 11 Apr 2012) $
$Rev: 8314 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

# ==================================================================
#
# 	CORE: This file is loaded at CORE
#	Creates the SP specific KSES arrays
#
# ==================================================================

# Version: 5.0
function sp_kses_array() {
	global $allowedforumtags, $allowedforumprotocols;

    $allowedforumprotocols = apply_filters('sph_allowed_protocols', array ('http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'clsid'));
	$allowedforumtags = array(
	'address' 	 => array(),
	'a' 		 => array('class' => true, 'href' => true, 'id' => true, 'title' => true, 'rel' => true, 'rev' => true, 'name' => true, 'target' => true),
	'abbr' 		 => array('class' => true, 'title' => true),
	'acronym' 	 => array('title' => true),
    'article'    => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'aside'      => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'b' 		 => array(),
	'big' 		 => array(),
	'blockquote' => array('id' => true, 'cite' => true, 'class' => true, 'lang' => true, 'xml:lang' => true),
	'br' 		 => array('class' => true),
	'caption' 	 => array('align' => true, 'class' => true),
	'cite' 		 => array('class' => true, 'dir' => true, 'lang' => true, 'title' => true),
	'code' 		 => array('style' => true),
	'dd' 		 => array(),
    'details'    => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'open' => true, 'style' => true, 'xml:lang' => true),
	'div' 		 => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'dl' 		 => array(),
	'dt' 		 => array(),
	'em' 		 => array(),
	'embed' 	 => array('height' => true, 'name' => true, 'pallette' => true, 'src' => true, 'type' => true, 'width' => true),
	'figure'     => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
    'figcaption' => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'font' 		 => array('color' => true, 'face' => true, 'size' => true),
	'footer'     => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'header'     => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'hgroup'     => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'h1' 		 => array('align' => true, 'class' => true, 'id'    => true, 'style' => true),
	'h2' 		 => array('align' => true, 'class' => true, 'id'    => true, 'style' => true),
	'h3' 		 => array('align' => true, 'class' => true, 'id'    => true, 'style' => true),
	'h4' 		 => array('align' => true, 'class' => true, 'id'    => true, 'style' => true),
	'h5' 		 => array('align' => true, 'class' => true, 'id'    => true, 'style' => true),
	'h6' 		 => array('align' => true, 'class' => true, 'id'    => true, 'style' => true),
	'hr' 		 => array('align' => true, 'class' => true, 'noshade' => true, 'size' => true, 'width' => true),
	'i' 		 => array(),
	'img' 		 => array('alt' => true, 'align' => true, 'border' => true, 'class' => true, 'height' => true, 'hspace' => true, 'longdesc' => true, 'vspace' => true, 'src' => true, 'style' => true, 'width' => true),
	'ins' 		 => array('datetime' => true, 'cite' => true),
	'kbd' 		 => array(),
	'label' 	 => array('for' => true),
	'li' 		 => array('align' 	=> true, 'class' => true),
    'menu'       => array('class' => true, 'style' => true, 'type' => true),
    'nav'        => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'object' 	 => array('classid' => true, 'codebase' => true, 'codetype' => true, 'data' => true, 'declare' => true, 'height' => true, 'name' => true, 'param' => true, 'standby' => true, 'type' => true, 'usemap' => true, 'width' => true),
	'param' 	 => array('id' => true, 'name' => true, 'type' => true, 'value' => true, 'valuetype' => true),
	'p' 		 => array('class' => true, 'align' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'pre' 		 => array('class' => true, 'style' => true, 'width' => true),
	'q' 		 => array('cite' => true),
	's' 		 => array(),
    'section'    => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'span' 		 => array('class' => true, 'dir' => true, 'align' => true, 'lang' => true, 'style' => true, 'title' => true, 'xml:lang' => true),
	'strike' 	 => array(),
	'strong' 	 => array(),
	'sub' 		 => array(),
	'summary'    => array('align' => true, 'class' => true, 'dir' => true, 'lang' => true, 'style' => true, 'xml:lang' => true),
	'sup' 		 => array(),
	'table' 	 => array('align' => true, 'bgcolor' => true, 'border' => true, 'cellpadding' => true, 'cellspacing' => true, 'class' => true, 'dir' => true, 'id' => true, 'rules' => true, 'style' => true, 'summary' => true, 'width' => true),
	'tbody' 	 => array('align' => true, 'char' => true, 'charoff' => true, 'valign' => true),
	'td' 		 => array('abbr' => true, 'align' => true, 'axis' => true, 'bgcolor' => true, 'char' => true, 'charoff' => true, 'class' => true, 'colspan' => true, 'dir' => true, 'headers' => true, 'height' => true, 'nowrap' => true, 'rowspan' => true, 'scope' => true, 'style' => true, 'valign' => true, 'width' => true),
	'tfoot' 	 => array('align' => true, 'char' => true, 'class' => true, 'charoff' => true, 'valign' => true),
	'th' 		 => array('abbr' => true, 'align' => true, 'axis' => true, 'bgcolor' => true, 'char' => true, 'charoff' => true, 'class' => true, 'colspan' => true, 'headers' => true, 'height' => true, 'nowrap' => true, 'rowspan' => true, 'scope' => true, 'valign' => true, 'width' => true),
	'thead' 	 => array('align' => true, 'char' => true, 'charoff' => true, 'class' => true, 'valign' => true),
	'title' 	 => array(),
	'tr' 		 => array('align' => true, 'bgcolor' => true, 'char' => true, 'charoff' => true, 'class' => true, 'style' => true, 'valign' => true),
	'tt' 		 => array(),
	'u' 		 => array(),
	'ul' 		 => array('class' => true, 'style' => true, 'type' => true),
	'ol' 		 => array('class' => true, 'start' => true, 'style' => true, 'type' => true),
	'var' 		 => array());
    $allowedforumtags = apply_filters('sph_kses_allowed_tags', $allowedforumtags);
}
?>