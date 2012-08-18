$(function() {
    if ($("#profile").length) {
        $("#profile").validate({
            rules : {
                author : {
                    required : true,
                    minlength : 2
                },
                honeypot : {
                    required : true,
                    email : true
                },
                subject : {
                    required : true,
                    minlength : 2
                },
                comment : {
                    required : true,
                    minlength : 10
                }
            }
        });
    }

    if ($('ul.twitter').length) {
        $('ul.twitter').data('position', 1);
    }
});

var position = $('ul.twitter').data('position');
if (!position) {
    position = 0;
}

// Twitter Rotation //
var intervalizer = setInterval("intervalAct()",20000);

// Twitter Navigation //
$('#twitNav a').live('click',function(e) {
    e.preventDefault();
    var action = $(this).text();

    if (action == 'Next' || action == 'Prev') {
        position = $(this).attr('href').split('=')[1];
        twitterAjax(position);
    }

    else if (action == 'Pause') {
        stopInterval();
        $(this).text('Play');
    }

    else if (action == 'Play') {
        var intervalizer = setInterval("intervalAct()",20000);
        $(this).text('Pause');
    }
});


function intervalAct() {
    if (position <= 13) {
            position ++;

    } else {
            position = 0;
    }

    twitterAjax(position);

    $('ul.twitter').data('position', position);
}

function twitterAjax(position) {
    $.ajax({
       type : 'GET',
       url : 'includes/twitter.php?position=' + position,
       success : function(response) {
           $('ul.twitter li').children().fadeOut(function() {
                $('ul.twitter').html(response);
                $('ul.twitter li').children().fadeIn();
           });
       }
    });
}

function stopInterval() {
    clearInterval(intervalizer);
}