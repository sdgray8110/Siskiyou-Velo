/* Ajax Tabs */
$('.raceNav ul ').tabs({
    ajaxCallback: function() {
        // Overview Gallery
        $('#thawGallery').fadeGallery();
        $('#sponsors').fadeGallery();
    }
});

// Overview Gallery
$('#thawGallery').fadeGallery();

// Tie clicks of content links to tabs
$('#mainContent .crossCountry, #mainContent .downhill').live('click', function(e) {
    e.preventDefault();

    var root = $(this),
        elClass = root.attr('class');

    $('#' + elClass).click();
});

// Sponsors Execution //
$('#sponsors').fadeGallery({
    interval : 5000
});