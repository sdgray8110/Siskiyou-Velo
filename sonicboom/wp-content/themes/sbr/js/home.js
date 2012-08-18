var home = {
    setVars: function() {
        var data = {
            carousel: $('#photoCarousel'),
            youtubeFeed: $('#youtubeFeed')
        };

        $.extend(home,data);
    },

    init: function() {
        home.setVars();
        home.photoCarousel();
        home.youtubeFeed.youtubeFeed();
        home.applyFancyboxes();
    },

    applyFancyboxes: function() {
        home.youtubeFeed.find('.play').fancybox({
            padding: 0,
            openEffect : 'elastic',
            openSpeed  : 150,
            closeEffect : 'elastic',
            closeSpeed  : 150,
            closeClick : true,
            type: 'iframe',
            width: 640,
            height: 480
        });
    },

    photoCarousel: function() {
        var root = home.carousel,
            carousel = root.find('.carouselContainer'),
            carouselItems = carousel.find('.photoCarouselItem'),
            controls = root.find('.carouselControl');

        home.appendControls(root, controls);

        carousel.jCarouselLite({
            visible: 1,
            circular: true,
            auto: 7000,
            btnGo: controls.find('.pager'),
            afterEnd: function(a) {
                var index = a.attr('id').replace('carouselItem_',''),
                    pager = controls.find('a').eq(index);

                home.setActivePager(pager);
            }
        });
    },

    setActivePager: function(el) {
        el = el || home.carousel.find('.carouselControl a').eq(0),
        pager = el.parent();

        el.parent().siblings().removeClass('active');
        pager.addClass('active');
    },

    appendControls: function(carousel,controls) {
        var carouselLength = carousel.find('.photoCarouselItem').length,
            markup = '',
            i = 0;

        if (carouselLength > 1) {
            while (i < carouselLength) {
                markup += '<li><a href="#" class="pager"></a></li>';
                i++;
            }
        }

        controls.html(markup);
        home.setActivePager();
    }
};

$(document).ready(function() {
    home.init();
});

