$(document).ready(function() {
    $('#nobike').change(function() {
            $(this).nextAll('.bikeType').attr({'checked' : ''});
    });

    $('.bikeType').change(function() {
            $('#nobike').attr({'checked' : ''});
    });

    

    $('#memberAccordion h2.memberDetails').live('click', function() {
        if ($(this).hasClass('active')) {
            $('#memberAccordion h2.active + div').slideUp();
            $('#memberAccordion h2').removeClass('active');
        }

        else {
            $('#memberAccordion h2.active + div').slideUp();
            $('#memberAccordion .active').removeClass('active');
            $(this).addClass('active');
            $(this).next('div').slideDown();
        }
    });

    $('#memberType').change(function() {
        var selected = $('option:selected', this).attr('text').split(' - ')[0];
        $('#memberTypeVar').text(selected);
    });

    $.superbox();
    $("#profile").validate();
});

$('#agreeToTerms').click(function() {
    agreeToTerms();
});
