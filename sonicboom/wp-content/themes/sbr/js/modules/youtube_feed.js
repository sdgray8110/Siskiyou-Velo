(function ($) {
    var settings = {
        userID: 'SBRBicycle',
        template: '#videoCarouselTemplate',
        data: {}
    };

    $.fn.youtubeFeed = function (options) {
        var root = $(this),
            options = $.extend({}, settings, options);

        init();

        function init() {
            get_feed();
        }

        function get_feed() {
            $.ajax({
                type: 'get',
                url: 'http://gdata.youtube.com/feeds/users/'+options.userID+'/uploads?alt=json-in-script&format=5',
                cache: 'false',
                dataType: 'jsonp',
                success: function(data) {
                    options.data = data;
                    console.log(data);
                    renderFeed();
                }
            })
        }

        function renderFeed() {
            global.applyTemplate({
                data: options.data.feed,
                container: root,
                templateEl: $(options.template),
                callback: function() {
                    applyCarousel();
                }
            });
        }

        function applyCarousel() {
            var carousel = root.find('.carouselContainer'),
                controls = root.find('.carouselControl'),
                prev = controls.find('.prev a'),
                next = controls.find('.next a');

            carousel.jCarouselLite({
                btnNext: next,
                btnPrev: prev,
                scroll: 3,
                circular: true
            });
        }

    };
})(jQuery);