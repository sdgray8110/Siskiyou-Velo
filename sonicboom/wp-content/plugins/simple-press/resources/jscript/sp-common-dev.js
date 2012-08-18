/* ---------------------------------
Simple:Press
Common Javascript
$LastChangedDate: 2010-06-27 04:12:12 +0100 (Sun, 27 Jun 2010) $
$Rev: 4174 $
------------------------------------ */

/*--------------------------------------------------------------
spjDialogAjax: Opens a jQuery UI Dialog popup filled by Ajax
	e:			The button/link object making the call
	url:		The url to the ahah file to populate dialog
	title:		text for the popup title bar
	width:		Width of the popup or 0 fot auto
	height:		Height of popup or 0 for auto
	position:	Set to zero to calculate. Or 'center', 'top' etc.
*/

function spjDialogAjax(e, url, title, width, height, position) {
	// close and remove any existing dialog. remove hdden div and recreate it */
	if(jQuery().dialog("isOpen")) {
		jQuery().dialog('close');
	}
	jQuery('#dialog').remove();
	jQuery("#dialogcontainer").append("<div id='dialog'></div>");
	jQuery('#dialog').load(url, function(ajaxContent) {
		spjDialogPopUp(e, title, width, height, position, ajaxContent);
	});
}

/*--------------------------------------------------------------
spjDialogHtml:	Opens a jQuery UI Dialog popup filled by content
	e:			The button/link object making the call
	content:	the formatted content to be displayed
	title:		text for the popup title bar
	width:		Width of the popup or 0 fot auto
	height:		Height of popup or 0 for auto
	position:	Set to zero to calculate. Or 'center', 'top' etc.
*/

function spjDialogHtml(e, content, title, width, height, position) {
	// close and remove any existing dialog. remove hdden div and recreate it */
	if(jQuery().dialog("isOpen")) {
		jQuery().dialog('close');
	}
	jQuery('#dialog').remove();
	jQuery("#dialogcontainer").append("<div id='dialog'></div>");
	spjDialogPopUp(e, title, width, height, position, content);
}

/*--------------------------------------------------------------
spjDialogPopUp: Opens a jQuery UI Dialog popup
	e:			The button/link object making the call
	title:		text for the popup title bar
	width:		Width of the popup or 0 fot auto
	height:		Height of the popup or 0 for auto
	position:	Set to zero to calculate. Or 'center', 'top' etc.
	content:	The cntent to be dsplayed
*/

function spjDialogPopUp(e, title, width, height, position, content) {
	// force content into dialog div
	jQuery('#dialog').html(content);
	if(position == 0) {
		var topPos = 0;
		var p = jQuery("#" + e.id);
		var offset = p.offset();
		var leftPos = (Math.floor(offset.left) - width);
		if (navigator.appName == "Microsoft Internet Explorer") {
			topPos = (Math.floor(offset.top) - document.body.scrollTop);
		} else {
			topPos = (Math.floor(offset.top) - window.pageYOffset);
		}
		if(leftPos < 0) leftPos = 0;
		if(topPos < 20) topPos = 20;
	}
	jQuery('#dialog').dialog({
		zindex: 100000,
		autoOpen: false,
		show: 'fold',
		hide: 'fold',
		width: 'auto',
		height: 'auto',
		maxHeight: 800,
		position: position,
		draggable: true,
		resizable: true,
		title: title,
		closeText: ''
	});

	if(width > 0) {
		jQuery('#dialog').dialog("option", "width", width);
	}
	if(height > 0) {
		jQuery('#dialog').dialog("option", "height", height);
	}
	if(position == 0) {
		jQuery('#dialog').dialog("option", "position", [leftPos, topPos]);
	}

	jQuery('#dialog').dialog( "option", "zIndex", 100000);

	jQuery('#dialog').dialog('open');
	jQuery(function(jQuery){vtip();});
}

/*--------------------------------------------------------------
vtip:  Tooltip enhancer
*/

this.vtip = function() {
	this.xOffset = -10; /*	x distance from mouse */
	this.yOffset =  18; /* y distance from mouse */

	jQuery(".vtip").unbind().hover(
		function(e) {
			if(this.title != '') {
				this.t = this.title;
				this.title = '';
				this.top = (e.pageY + yOffset); this.left = (e.pageX + xOffset);
				jQuery('body').append( '<p id="vtip">' + this.t + '</p>' );
				jQuery('p#vtip').css("top", this.top+"px").css("left", this.left+"px").fadeIn("fast");
			}
		},
		function() {
			if(this.t != undefined) {
				this.title = this.t;
				jQuery("p#vtip").fadeOut("slow").remove();
			}
		}
	).mousemove(
		function(e) {
			this.top = (e.pageY + yOffset);
			this.left = (e.pageX + xOffset);

			jQuery("p#vtip").css("top", this.top+"px").css("left", this.left+"px");
		}
	);
};

/*--------------------------------------------------------------
jcookie:  Set/Get a cookie
*/
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};