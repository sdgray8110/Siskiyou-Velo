/* Feed Pattern */
//Fri Apr 30 08:50:30 +0000 2010

/* Search Pattern */
//Sat, 24 Apr 2010 18:00:22 +0000

function dateObj(date, type, gmt) {
	var newDate = date.split(' ');

	if (type == 'search') {
		var day = parseInt(newDate[1]),
			month = newDate[2],
			year = parseInt(newDate[3]),
			time = newDate[4],
			gmtDiff = parseInt(newDate[5].replace('+', '').replace('000', ''));
	}

	else {
		var day = parseInt(newDate[2]),
			month = newDate[1],
			year = parseInt(newDate[5]),
			time = newDate[3],
			gmtDiff = parseInt(newDate[4].replace('+', '').replace('000', ''));
	}

	var dateObj = {
		day : day,
		month : month,
		year : year,
		gmtDiff : gmtDiff,
		gmt : parseInt(gmt),
		weekday : newDate[0].replace(',', ''),
		hour : handleZeroes(time.split(':')[0]),
		minute : time.split(':')[1]
	}

	dateObj = makeGmt(dateObj);
	dateObj = amPm(dateObj);

	return dateObj;
}

function handleZeroes(hour) {
	hour == '00' ? hour = 0 : hour = hour;

	if (typeof(hour) === 'string') {
		hour.charAt(0) == 0 ? hour = parseInt(hour.replace('0', '')) : hour = parseInt(hour);
	}
	return hour;
}

function makeGmt(dateObj) {
	var preHour = dateObj.hour + dateObj.gmt,
		gmtDiff = dateObj.gmtDiff,
		subT = preHour - gmtDiff;
		subT < 0 ? subT = (subT / -1) : subT = subT;

	if (preHour - gmtDiff < 0) {
		dateObj.hour = 24 - subT
	} else {
		dateObj.hour = subT;
	}

	return dateObj;
}

function amPm(dateObj) {
	var preHour = dateObj.hour;

    if (preHour > 12) {
        var standard = 'pm';
        preHour = preHour - 12;
    } else {
        if (preHour == 0) {
            preHour = 12;
        }
        var standard = 'am';
    }
	dateObj.hour = preHour;
	dateObj.standard = standard;

	return dateObj;
}

function constructDate(dateObj) {
    dateObj.timeStr = dateObj.hour + ':' + dateObj.minute + dateObj.standard;

    var date = dateObj.weekday + ', ' + dateObj.month + ' ' + dateObj.day + ' at ' + dateObj.timeStr;
    return date;
}

function parseDate(date, type, gmt) {
	var obj = dateObj(date, type, gmt),
		date = constructDate(obj);

	return date;
}

function clickUrl(text) {
    var linkRegEx = /((ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?)/gi;
    var hashRegEx = /#([^ ]+)/;
    var userRegEx = /@([^ ]+)/;
    text = text.replace(linkRegEx, '<a target="_blank" href=\"$1\">$1</a>');
    text = text.replace(hashRegEx, '<a target="_blank" class="hash" href=\"http://twitter.com/#search?q=%23$1\">#$1</a>');
    text = text.replace(userRegEx, '@<a target="_blank" href=\"http://www.twitter.com/$1\">$1</a>');
    return text;
}

(function ($) {
    var settings = {
        type: 'feed',
		count: 30,
        user: '',
        searchTerm: '',
		gmt: -8
    };

    $.fn.twitterize = function (options) {
        options = $.extend({}, settings, options);
        var thisEl = $(this);

        if (options.type == 'search') {
            var url = 'http://search.twitter.com/search.json?q=' + options.searchTerm + '&.json?count='+options.count+'&callback=?';
        }
        else {
            var url = 'http://twitter.com/statuses/user_timeline/' + options.user + '.json?count='+options.count+'&callback=?';
        }

		twitterAjax();

		function twitterAjax() {
			$.ajax({
				url: url,
				dataType: "json",
				cache: "false",
				success: function (process) {
					if (options.type == 'search') {
						$.each(process.results, function (i, item) {
							var user = item.from_user;
							var thumb = item.profile_image_url;
							var text = clickUrl(item.text);
							var timestamp = parseDate(item.created_at, options.type, options.gmt);
							$('<li/>').html('<div class="thumb"><img src="' + thumb + '" height="48" width="48" alt="' + user + '"/></div><div><p><a target="_blank" href="http://www.twitter.com/' + user + '">' + user + '</a> ' + text + '</p><p>' + timestamp + '</p></div>').appendTo(thisEl);
						});
					}
					else {
						$.each(process, function (i, item) {
							var user = item.user.screen_name;
							var thumb = item.user.profile_image_url;
							var text = clickUrl(item.text);
							var timestamp = parseDate(item.created_at, options.type, options.gmt);
							var source = item.source;
							$('<li/>').html('<div class="thumb"><img src="' + thumb + '" height="48" width="48" alt="' + user + '"/></div><div><p><a target="_blank" href="http://www.twitter.com/' + user + '">' + user + '</a> ' + text + '</p><p>' + timestamp + ' via ' + source + '</p></div>').appendTo(thisEl);
						});

					}
				}
			});
		}
    };
})(jQuery);