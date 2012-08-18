// JSON Calendar Constructor //
(function($) {
    var settings = {
        jsonURL : 'includes/calendarJSON.php',
        dayHeaders : 'SU,M,T,W,TH,F,SA'
    };

	$.fn.calendar = function(defaults) {
        var root = $(this),
            defaults = $.extend({}, settings, defaults);

        calendarAjax(defaults.jsonURL);

        root.find('a.pager').live('click', function(e) {
            e.preventDefault();

            var thisEl = $(this),
                rel = thisEl.attr('rel'),
                url = defaults.jsonURL + '?month=' + rel;

            calendarAjax(url);            
        });

        function calendarAjax(url) {
            $.getJSON(url,function(data) {
                buildCalendar(data);
            });
        }

        function buildCalendar(data) {

            var baseCalData = {
                month : data.items[0].month,
                numMonth : data.items[0].numMonth,
                year : data.items[0].year,
                duration : data.items[0].duration,
                starts : data.items[0].starts
            };

            buildBaseCalendar(baseCalData);
            buildEvents(data.items[0]);
            buildPagers(baseCalData);
        }

        function buildBaseCalendar(baseCalData) {
            var header = '<h2>' + baseCalData.month + ' ' + baseCalData.year + '</h2>',
                dayHeaders = buildDayHeaders(),
                days = buildDays(baseCalData),
                grid = '<ul id="calendarGrid">'+ dayHeaders + days + '</ul>';

            root.html(header + grid);
        }

        function buildDayHeaders() {
            var dayHeaders = defaults.dayHeaders.split(','),
                headers = '';

            for (var i in dayHeaders) {
                headers += '<li class="dayHeaders"><h3>'+dayHeaders[i]+'</h3></li>';
            }

            return headers;
        }

        function leadingDays(baseCalData) {
            var days = '';

            for (i=1;i<=baseCalData.starts;i++){
                days += '<li class="empty"></li>';
            }

            return days;
        }

        function trailingDays(baseCalData) {
            var initDays = parseInt(baseCalData.starts) + parseInt(baseCalData.duration),
                weeks = Math.ceil(initDays / 7),
                remainder = (weeks * 7) - initDays,
                days = '';

            for (i=1;i<=remainder;i++){
                days += '<li class="empty"></li>';
            }

            return days;
        }

        function buildDays(baseCalData) {
            var days = leadingDays(baseCalData);

            for (i=1;i<=baseCalData.duration;i++) {
                days += '<li id="day'+i+'"><h5>'+i+'</h5></li>';
            }

            days += trailingDays(baseCalData);

            return days;
        }

        function buildEvents(data) {

            $.each(data.events,function(i,event){

                !event.type ? eventClass = 'class="event"' : eventClass = 'class="event ' + event.type + '"';
                var name = '<h6>'+event.name+'</h6>',
                    time = '<p>'+event.time+'</p>',
                    link = '<p class="moreInfo"><a href="'+event.link+'">more info &raquo;</a></p>',
                    location = '<p>'+event.location+'</p>',
                    info = '<div>'+ location + link +'</div>',
                    eventEl = '<div '+eventClass+'>' + name + time + info + '</div>';

                $('#day' + event.date).append(eventEl);
            });
        }

        function pageData(baseCalData) {
            var curMonth = parseInt(baseCalData.numMonth),
                curYear = parseInt(baseCalData.year),
                monthInc = curMonth + 1,
                monthDec = curMonth - 1,
                yearInc = curYear + 1,
                yearDec = curYear - 1,
                pagerData = []; // (prevMonth, prevYear, nextMonth, nextYear)

            if (curMonth > 1 && curMonth < 12) {
                pagerData.push(monthDec, curYear, monthInc, curYear);
            } else if (curMonth == 12) {
                pagerData.push(monthDec, curYear, 1, yearInc);
            } else {
                pagerData.push(12, yearDec, monthInc, curYear);
            }

            return pagerData;
        }

        function buildPagers(baseCalData) {
            var pagerData = pageData(baseCalData),
                prevMonth = '<a class="pager prev" href="" rel="'+pagerData[0]+'|' + pagerData[1] +'">Previous Month</a>',
                nextMonth = '<a class="pager next" href="" rel="'+pagerData[2]+'|' + pagerData[3] +'">Next Month</a>';

            root.find('h2').after(nextMonth).after(prevMonth);
        }

	};
})(jQuery);

$('#calendar').calendar();
