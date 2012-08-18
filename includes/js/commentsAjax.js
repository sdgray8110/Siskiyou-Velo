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
					$(divID).load("http://www.siskiyouvelo.org/includes/ajaxQuery.php?comment="+cmtID);
				}
			});
		}
	});
});


// Rounded Corners
$(document).ready(function() {
	$('div.comment').corner('7px');
	$('div.commentInfo').corner('tl 7px').corner('bl 7px');
});