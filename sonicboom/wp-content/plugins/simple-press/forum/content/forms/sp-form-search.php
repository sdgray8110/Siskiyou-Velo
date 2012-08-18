<?php
/*
Simple:Press
Search Form Rendering
$LastChangedDate: 2012-06-04 11:57:12 -0700 (Mon, 04 Jun 2012) $
$Rev: 8656 $
*/

if (preg_match('#'.basename(__FILE__).'#', $_SERVER['PHP_SELF'])) die('Access denied - you cannot directly call this file');

function sp_render_inline_search_form($args) {
	global $sfvars, $spThisUser, $sfglobals;

	extract($args, EXTR_SKIP);

	$pageview = $sfvars['pageview'];

	# all or current forum?
	$out = '';
	$out.= '<fieldset class="spSearchFormAdvanced">';
	$out.= '<legend>'.$labelLegend.':</legend>';
	$out.= '<div class="spSearchSection">';

	$out = apply_filters('sph_SearchFormTop', $out);

	$out.= '<div class="spRadioSection spLeft">';
	$tout = '';
	$tout.= '<p class="spSearchForumScope">&mdash;&nbsp;'.$labelScope.'&nbsp;&mdash;</p>';
	$ccheck = 'checked="checked"';
	$acheck = '';
	if (($pageview == 'forum') || ($pageview == 'topic')) {
		$tout.= '<input type="hidden" name="forumslug" value="'.esc_attr($sfvars['forumslug']).'" />';
		$tout.= '<input type="hidden" name="forumid" value="'.esc_attr($sfvars['forumid']).'" />';
		$tout.= '<label class="spLabel spRadio" for="sfradio1">'.$labelCurrent.'</label><input type="radio" id="sfradio1" name="searchoption" value="1" '.$ccheck.' /><br />';
	} else {
		$acheck = 'checked="checked"';
	}
	$tout.= '<label class="spLabel spRadio" for="sfradio2">'.$labelAll.'</label><input type="radio" id="sfradio2" name="searchoption" value="2" '.$acheck.' /><br />';
	$out.= apply_filters('sph_SearchFormForumScope', $tout);
	$out.= '</div>';

	# search type?
	$tout = '';
	$tout.= '<div class="spRadioSection spLeft">';
	$tout.= '<p class="spSearchMatch">&mdash;&nbsp;'.sp_text('Match').'&nbsp;&mdash;</p>';
	$tout.= '<label class="spLabel spRadio" for="sfradio3">'.$labelMatchAny.'</label><input type="radio" id="sfradio3" name="searchtype" value="1" checked="checked" /><br />';
	$tout.= '<label class="spLabel spRadio" for="sfradio4">'.$labelMatchAll.'</label><input type="radio" id="sfradio4" name="searchtype" value="2" /><br />';
	$tout.= '<label class="spLabel spRadio" for="sfradio5">'.$labelMatchPhrase.'</label><input type="radio" id="sfradio5" name="searchtype" value="3" />';
	$out.= apply_filters('sph_SearchFormMatch', $tout);
	$out.= '</div>';

	# topic title?
	$tout = '';
	$tout.= '<div class="spRadioSection spLeft">';
	$tout.= '<p class="spSearchOptions">&mdash;&nbsp;'.$labelOptions.'&nbsp;&mdash;</p>';
    $checked = ($searchIncludeDef == 1 || empty($searchIncludeDef) || $searchIncludeDef < 0 || $searchIncludeDef > 3) ? ' checked="checked"' : '';
	$tout.= '<label class="spLabel spRadio" for="sfradio6">'.$labelPostsOnly.'</label><input type="radio" id="sfradio6" name="encompass" value="1"'.$checked.' /><br />';
    $checked = ($searchIncludeDef == 2) ? ' checked="checked"' : '';
	$tout.= '<label class="spLabel spRadio" for="sfradio7">'.$labelTitlesOnly.'</label><input type="radio" id="sfradio7" name="encompass" value="2"'.$checked.' />';
    $checked = ($searchIncludeDef == 3) ? ' checked="checked"' : '';
	$tout.= '<label class="spLabel spRadio" for="sfradio8">'.$labelPostTitles.'</label><input type="radio" id="sfradio8" name="encompass" value="3"'.$checked.' /><br />';
	$out.= apply_filters('sph_SearchFormOptions', $tout);
	$out.= '</div>';

	$out.= '</div><br />';

	$out.= '<div class="spClear"></div>';
	$out.= '<p class="spLeft spSearchDetails">'.$labelWildcards.':<br />*&nbsp; '.$labelMatchAnyChars.'&nbsp;&nbsp;&nbsp;&nbsp;%&nbsp; '.$labelMatchOneChar.'</p>';
	$out.= '<p class="spLeft spSearchDetails">'.sprintf($labelMinLength, '<b>'.$sfglobals['mysql']['search']['min'].'</b>', '<b>'.$sfglobals['mysql']['search']['max'].'</b>')."</p>";

 	$out.= '</fieldset>';
	$out.= '<div class="spClear"></div>';

	$tout = '';
	if ($spThisUser->member) {
		$tout.= '<fieldset class="spSearchMember">';
		$tout.= '<legend>'.$labelMemberSearch.':</legend>';
		$tout.= '<div class="spSearchSection">';
		$tout.= '<img src="'.SPTHEMEICONSURL.'sp_Search.png" alt="" />&nbsp;';
		$tout.= '<input type="hidden" name="userid" value="'.$spThisUser->ID.'" />';
		$tout.= '<input type="submit" class="spSubmit" name="membersearch" value="'.$labelTopicsPosted.'" />';
		$tout.= '<input type="submit" class="spSubmit" name="memberstarted" value="'.$labelTopicsStarted.'" />';
		$tout.= '</div>';
		$tout.= '</fieldset>';
	}

	if (!empty($tout)) $out.= $tout;

	$out = apply_filters('sph_SearchFormBottom', $out);

	return $out;
}
?>