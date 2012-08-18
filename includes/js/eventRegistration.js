$('#registerNow').live('click', function(e) {
    e.preventDefault();

    var formData = $('#registerSubmit').serialize();

    $.ajax({
        type : 'GET',
        url : 'includes/events/registrationForm.php',
        data : formData,
        success : function(data) {
            $('.registrationContent').fadeOut(function() {
                $('.registrationContent').html(data).fadeIn();
            });
        }
    });
});

// Recalculate Attendee & Cost
$('#attendees, #nonMembers').live('change', function() {
    writeNewValues(calculateOrderTotal());
});

// Continue Registration - perform validation before submitting form
$('#continueRegistration').live('click', function() {
    if (validateMealChoice(calculateOrderTotal(), $('#merch3'), 'Select no more than one meal per attendee.', 'You must select a meal for each attendee.')) {
        $('#continueRegistration').hide().after('<p>Redirecting to paypal ...</p>');
        return true;
    }

    return false;
});

$('#forgotPassword').click(function(e) {
    $('.passwordClosed').attr('class','passwordOpen');
});

// Ajax Login
$('#ajaxLoginSubmit').ajaxLogin({
    redirectPath : '/includes/events/eventDetail.php'
});

function calculateOrderTotal() {
    var getValues = {
        memberCost : parseFloat($('#memberCost').text()),
        nonMemberCost : parseFloat($('#nomMemberCost').text()),
        membersAttending : parseInt($('#attendees option:selected').attr('value')),
        nonMembersAttending : parseInt($('#nonMembers option:selected').attr('value'))
    },

        newValues = {
        totalAttendees : parseInt(getValues.membersAttending + getValues.nonMembersAttending),
        totalCost : ((getValues.membersAttending * getValues.memberCost) + (getValues.nonMembersAttending * getValues.nonMemberCost)).toFixed(2)
    };



    return newValues;
}

function writeNewValues(newValues) {
    $('#totalAttendees').text(newValues.totalAttendees);
    $('#totalCost').text('$' + newValues.totalCost);
    $('#finalTotal').val(newValues.totalCost);
}

function validateMealChoice(newValues, prevElement, messaging1, messaging2) {
    var elements = {
        meal1 : parseInt($('#merch1').attr('value')),
        meal2 : parseInt($('#merch2').attr('value')),
        meal3 :parseInt( $('#merch3').attr('value'))
    },

        totals = elements.meal1 + elements.meal2 + elements.meal3,
        attendees = newValues.totalAttendees;

    if (totals > attendees) {
        renderValidation(false, prevElement, messaging1);
        return false;
    } else if (totals < attendees) {
        renderValidation(false, prevElement, messaging2);
        return false;
    } else if (totals == attendees){
        renderValidation(true, prevElement, '');
        return true;
    }
}

function renderValidation(isValid, prevElement, messaging) {
    if (isValid === true) {
        $('.validationError').remove();
    } else {
        $('.validationError').remove();
        prevElement.after('<p class="validationError">' + messaging + '</p>');
    }
}