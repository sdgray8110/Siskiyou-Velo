// **** NAV FUNCTIONS **** //
$(document).ready(function() {
	$('ul.mainNav li').click(function() {
		if ($(this).hasClass('active')) {}
		else {
		var clicked = $(this).attr('class');
		$('ul.mainNav li').removeClass('active');
		$(this).addClass('active');
		$('ul.subNav').css({'display' : 'none'});
		$('ul#'+clicked).css({'display' : 'block'});
		}
	});

	$('ul.subNav li').click(function() {
		$('ul.subNav li').removeClass('active');
		$(this).addClass('active');
	});
	
	$('span.status').click(function() {
		if ($('.memberDialog').hasClass('active')) {
			$('.memberDialog').slideUp(1000).removeClass('active');
		}
		else {
			$('.memberDialog').slideDown(1000).addClass('active');
		}
	});
	
// ** Sub Nav Functions ** //
$.ajaxSetup ({
    // Disable caching of AJAX responses */
    cache: false
});

	$('#emailNav li').click(function() {
		var clicked = $(this).attr('id');
			$('.memberTables').remove();
			if ($('div.test').length < 1) {$('div.ckEditorForm').after('<div class="test"></div>');}
			$('div.test').load('includes/email_selects.php?list=' + clicked);
			$('div.ckEditorForm').css({'display' : 'block'});
	});
	
	$('#memberNav li').click(function() {
		var clicked = $(this).attr('id');
			$('.test').remove();
			if ($('div.memberTables').length < 1) {$('div.ckEditorForm').before('<div class="memberTables"></div>');}
			if (clicked == 'search_tab') {
				$('div.advSearch').slideDown();
			}
			
			else if (clicked == 'add_tab') {
				$('div.advSearch').slideUp();
				url = 'includes/new_member.php?height=500&width=720';
				tb_show('', url);				
			}
			
			else {
				$('div.advSearch').slideUp();
				$('div.memberTables').load('includes/member_tables.php?type=' + clicked);
				$('div.ckEditorForm').css({'display' : 'none'});
			}
	});

	$('#eventsNav li').click(function() {
		var clicked = $(this).attr('id');
			$('.memberTables').remove();
			if ($('div.test').length < 1) {$('div.ckEditorForm').after('<div class="test"></div>');}

            $.ajax({
                type : 'GET',
                url : 'includes/events/' + clicked + '.php',
                success : function(data) {
                    $('#emailForm').after(data);
                    subEvents();
                    tb_reinit('a.thickbox, area.thickbox, input.thickbox');
                }
            });

			$('div.ckEditorForm').css({'display' : 'none'});
	});    
	
    $('#emailForm').submit(function () {
		if ($('#subject').attr('value') == '') {
			alert('Please Enter A Subject');			
			return false;
		}
		else if ($('#recipients').attr('value') == '') {
			alert('Please Choose Your Recipient(s)');			
			return false;
		}
    }); 
});

// **** ADVANCED SEARCH  **** //
$('div.advSearch p').live('click',function() {
    var advanced = $('#advanced').serialize();

    $.ajax({
        type : 'GET',
        url : 'includes/member_tables.php?filter=yes',
        data : advanced,
        success : function(data) {
            $('div.memberTables').html(data);
        }
    });
});



// **** EMAIL FUNCTIONS **** //
function selectAll() {
	$('.select_multiple option').attr({'selected' : 'selected'});
}

// **** EVENTS FUNCTIONS **** //
function subEvents() {
    $('#moreDetails input[type="radio"]').inputChange({
        callback : function() {
            $.ajax({
                type : 'GET',
                url : 'includes/events/subEvents.php?count=1',
                success : function(data) {
                    $('#moreDetails').html(data);                  
                }
            });
        }
    });

    $('#purchases input[type="radio"]').inputChange({
        callback : function() {
            $.ajax({
                type : 'GET',
                url : 'includes/events/merchandise.php?count=1',
                success : function(data) {
                    $('#purchases').html(data);
                }
            });
        }
    });

    $('#date, #closingDate').mask('99/99/9999');
    $('#time').mask('99:99');
}

$('#addSubEvent').addAnother({
   url : 'includes/events/subEvents.php',
   rootContainerName : 'subevent'
});

$('#addMerchandise').addAnother({
   url : 'includes/events/merchandise.php',
   rootContainerName : 'merchandise'
});

$('#submitNewEvent').live('click', function(e) {
    e.preventDefault();

    var formData = $('#newEventForm').serialize();

    $.ajax({
        type : 'POST',
        data : formData,
        url : 'includes/events/formHandlers/createEvent.php?',
        success : function(data) {
            $('#newEventForm').parent().html(data);
        }
    });
});

function officerSelections() {
    var officer = $('input#officerTag').val(),
        officer2 = $('input#officer2Tag').val(),
        officer3 = $('input#officer3Tag').val(),
        select = $('#officerSelect'),
        select2 = $('#officerSelect2'),
        select3 = $('#officerSelect3');

    compareSelections(select,officer);
    compareSelections(select2,officer2);
    compareSelections(select3,officer3);
}

function ageTypeSelections() {
    var age = $('input#Age').val(),
        ageSelect = $('#ageSelect'),
        type = $('input#type').val(),
        typeSelect = $('#typeSelect');

    compareSelections(ageSelect,age);
    compareSelections(typeSelect,type);
}

function compareSelections(select,val) {
    var options = select.find('option');

    console.log(select,val);

    if (val) {
        options.each(function() {
            var el = $(this);

            if (el.val() == val) {
                el.attr({'selected' : 'selected'});
            }
        });
    } else {
        options.eq(options.length - 1).attr({'selected' : 'selected'});
    }
}

