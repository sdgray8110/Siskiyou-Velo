var race = {
    init: function() {
        $('.raceNav ul ').tabs({
            ajaxCallback: function() {
                // Overview Gallery
                $('#sponsors').fadeGallery();
            }
        });
    }
};

$(document).ready(function() {
    race.init();
});
