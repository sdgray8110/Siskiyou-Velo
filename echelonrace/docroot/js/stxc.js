/* Ajax Tabs */
$('.raceNav ul ').tabs({
    ajaxCallback: function() {
        // Overview Gallery
        $('#stxcGallery').fadeGallery();
        $('#sponsors').fadeGallery();
    }
});

// Overview Gallery
$('#stxcGallery').fadeGallery();

// Sponsors Execution //
$('#sponsors').fadeGallery({
    interval : 5000
});