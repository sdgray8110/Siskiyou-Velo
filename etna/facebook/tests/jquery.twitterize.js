function parseDate(date, type) {
/* Feed Pattern */
//Fri Apr 30 08:50:30 +0000 2010

/* Search Pattern */
//Sat, 24 Apr 2010 18:00:22 +0000
    var newDate = date.split(' ');

if (type == 'search') {
    var day = newDate[1];
    var month = newDate[2];
    var year = newDate[3];
    var time = newDate[4];
	var gmtDiff = newDate[5].replace('+', '').replace('000', '');
}

else {
    var day = newDate[2];
    var month = newDate[1];
    var year = newDate[5];
    var time = newDate[3];
	var gmtDiff = newDate[4].replace('+', '').replace('000', '')
}

    var weekday = newDate[0].replace(',', '');
    var hour = time.split(':')[0];
    if (hour > 12) {
        var standard = 'pm';
        hour = hour - 12;
    } else {
        if (hour == 0) {
            hour = 12;
        }
        if (hour < 10) {
            hour = hour.replace('0', '');
        }
        var standard = 'am';
    }
    var minute = time.split(':')[1];


    time = hour + ':' + minute + standard;

    var date = weekday + ', ' + month + ' ' + day + ' at ' + time;
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
        user: '',
        searchTerm: ''
    };

    $.fn.twitterize = function (options) {
        options = $.extend({}, settings, options);
        var thisEl = $(this);

        if (options.type == 'search') {
            var url = 'http://search.twitter.com/search.json?q=' + options.searchTerm + '&.json?count=30&callback=?';
        }
        else {
            var url = 'http://twitter.com/statuses/user_timeline/' + options.user + '.json?count=30&callback=?';
        }

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
                        var timestamp = parseDate(item.created_at, options.type);
                        $('<li/>').html('<div class="thumb"><img src="' + thumb + '" height="48" width="48" alt="' + user + '"/></div><div><p><a target="_blank" href="http://www.twitter.com/' + user + '">' + user + '</a> ' + text + '</p><p>' + timestamp + '</p></div>').appendTo(thisEl);
                    });
                }
                else {
                    $.each(process, function (i, item) {
                        var user = item.user.screen_name;
                        var thumb = item.user.profile_image_url;
                        var text = clickUrl(item.text);
                        var timestamp = parseDate(item.created_at, options.type);
						var source = item.source;
                        $('<li/>').html('<div class="thumb"><img src="' + thumb + '" height="48" width="48" alt="' + user + '"/></div><div><p><a target="_blank" href="http://www.twitter.com/' + user + '">' + user + '</a> ' + text + '</p><p>' + timestamp + ' via ' + source + '</p></div>').appendTo(thisEl);
                    });
                }
            }
        });
    };
})(jQuery);