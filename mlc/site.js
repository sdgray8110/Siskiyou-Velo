function rideSwitcher() {
    var imageCount =  $('.flashPhoto img').size() - 1,
        image = $('.active'),
        activeIndex = $('.flashPhoto img').index(image),
        nextImage = activeIndex + 1;

        if (nextImage > imageCount) {
            nextImage = 0;
        }

        $('.flashPhoto img.active').fadeOut(function() {
            $(this).removeClass('active');
            $('.flashPhoto img').eq(nextImage).fadeIn(function() {
                $(this).addClass('active');
            });
        });
}
function rideTimer() {
    setInterval('rideSwitcher()', 5000);
}

$(function() {
    rideTimer();
});