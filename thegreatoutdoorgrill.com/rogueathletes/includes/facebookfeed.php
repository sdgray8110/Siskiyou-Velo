<div class="">
<div class="">

/* Global JS */

// Main Nav //

$('#header > ul').mainNav();

// Results Overlay //
$('#header li.results a').sgModal({
    width:700,
    height:500,
    modifiedAjax: true,
    ajaxCallback: function(data) {
        console.log(data);
    }
});

// Facebook Feed //
$('#fbFeed').fbFeed({
    userID : 115104251898013
});


</div>
</div>