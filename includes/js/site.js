$(document).ready(function() {
    $('span.newComment').click(function() {
        var commentID = '#' + $(this).attr('title');
		var formID = 'form#' + $(this).attr('title');

		if ($(this).hasClass('active')) {
			$(this).removeClass('active').text('View/Post New Comment');
			$(commentID).slideUp('slow').removeClass('active');			
		}
		
		else {
			$('span.active').removeClass('active').text('View/Post New Comment');
			$(this).addClass('active').text('Close Comments');
			
			if ($('div.newComment').hasClass('active')) {
				$('div.newComment').slideUp('slow');
			}
			
			$(commentID).slideDown('slow').addClass('active');
		}
		
	});

//Submit is on a div instead of a submit button to prevent the form from executing before validation
	$("div.submit").click(function() {
	  var cmtID = $(this).attr('id').replace(/submit_/, "");
	  var thisForm = $(this).parents('form:first');
	  var divID = '#newComment_' + cmtID;;
      var str = thisForm.serialize();
	  
 

// Form Validation
	    thisForm.validation();	
		
        if(!thisForm.validate()) {
        }

// Submit form and refresh the comments
		else {	
			$.ajax({
				type: "POST",
				url: "includes/ajax.php",
				data: str,
				success: function(){
					$(divID).load("/includes/ajaxQuery.php?comment="+cmtID);
				}
			});
		}
	});

    $('#leftContent').find('.confirmLink').click(function() {
        var text = $(this).attr('rel');

        return confirm(text);
    });
});


// Strip Tags
function strip_tags (str, allowed_tags) {
    var key = '', allowed = false;
    var matches = [];    var allowed_array = [];
    var allowed_tag = '';
    var i = 0;
    var k = '';
    var html = ''; 
    var replacer = function (search, replace, str) {
        return str.split(search).join(replace);
    };
     // Build allowes tags associative array
    if (allowed_tags) {
        allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
    }
     str += '';
 
    // Match tags
    matches = str.match(/(<\/?[\S][^>]*>)/gi);
     // Go through all HTML tags
    for (key in matches) {
        if (isNaN(key)) {
            // IE7 Hack
            continue;        }
 
        // Save HTML tag
        html = matches[key].toString();
         // Is tag not in allowed list? Remove from str!
        allowed = false;
 
        // Go through all allowed tags
        for (k in allowed_array) {            // Init
            allowed_tag = allowed_array[k];
            i = -1;
 
            if (i != 0) { i = html.toLowerCase().indexOf('<'+allowed_tag+'>');}            if (i != 0) { i = html.toLowerCase().indexOf('<'+allowed_tag+' ');}
            if (i != 0) { i = html.toLowerCase().indexOf('</'+allowed_tag)   ;}
 
            // Determine
            if (i == 0) {                allowed = true;
                break;
            }
        }
         if (!allowed) {
            str = replacer(html, "", str); // Custom replace. No regexing
        }
    }
     return str;
}

$(function() {
	$('a.print').click(function(e) {
		e.preventDefault;
		window.print();
	});

    $('#agreeToTerms').click(function() {
        agreeToTerms();
    });

rideTimer();

$('#joinForm').joinPost({
    url : 'joinHandler.php'
});

});

function agreeToTerms() {
        var terms = $('#termsAgree').attr('checked');

        if (terms == false) {
            alert('You must agree to the Ride Waiver and Commitment to Cycling Excellence before continuing with renewal.');
        }

        else {
            $('.agreement').fadeOut(function(){
              $('.hideStuff').fadeIn();
            });
        }
}

function rideSwitcher() {
    var rideCount =  $('.thisRide').size() - 1,
        rides = $('.active'),
        activeIndex = $('.thisRide').index(rides),
        nextRide = activeIndex + 1;

        if (nextRide > rideCount) {
            nextRide = 0;
        }

        $('.featuredRide .active').fadeOut(function() {
            $(this).removeClass('active');
            $('.thisRide').eq(nextRide).fadeIn(function() {
                $(this).addClass('active');
            });
        });
}
function rideTimer() {
    setInterval('rideSwitcher()', 10000);
}

// Join Page Ajax Plugin
(function($) {
    var settings = {
        url : ''
    };
    $.fn.joinPost = function(options) {
        options = $.extend({}, settings, options),
        thisEL = $(this).attr('id'),
        submit = $('#' + thisEL + ' input[type=submit]');

        submit.live('click', function(e) {
           e.preventDefault();

            $.ajax({
                type : 'POST',
                data : $('#' + thisEL).serialize(),
                url : options.url,
                success : function(data) {
                    $('#' + thisEL).parent().parent().html(data);
                }
            });
        });

    };
})(jQuery);

// Ajax Login Plugin //
(function($) {
	var settings = {
        redirectPath : '',
        hiddenVar : '#hiddenVar',
        hiddenAttr : '#hiddenAttr'
	};

	$.fn.ajaxLogin = function(config) {
		config = $.extend({}, settings, config),

        $(this).live('click',function(e) {
           e.preventDefault();

            $(config.hiddenAttr).attr('value', $(config.hiddenVar).text());

            var formData = $(this).parents('form').serialize(),
                container = $(this).parents('form').parent();            

            $.ajax({
                type : 'POST',
                data : 'redirectPath=' + config.redirectPath + '&' + formData,
                url : '/includes/ajaxLogin/loginHandler.php',
                success : function(data) {
                    loadLoginBar(container, data);
                }
            });
        });

        function loadLoginBar(container, mainData) {
            var ajaxLogin = '#ajaxLogin',
                len = $(mainData).find(ajaxLogin).length;

            if (len <= 0) {
                container.fadeOut(function() {
                    container.html(mainData).fadeIn();
                });
            } else {
                $.ajax({
                    type : 'GET',
                    url : '/includes/ajaxLogin/headerLoginBar.php?ajax=true',
                    success : function(data) {
                        container.fadeOut(function() {
                            container.html(mainData).fadeIn();
                        });

                        $('#login').fadeOut(function() {
                            $('#login').after(data).remove();
                            $('.loginBar .hidden').fadeIn();
                        });
                    }
                });
            }
        }
	};
})(jQuery);
