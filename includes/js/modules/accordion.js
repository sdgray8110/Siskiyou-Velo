(function($) {
    var settings = {
        headerEl: 'h2',
        activeClass: 'active',
        slideDuration: 250
    };

	$.fn.accordion = function(options) {
        options = $.extend({}, settings, options),
        root = $(this),
        headers = root.find(options.headerEl);

        addClickHandlers();

        function addClickHandlers() {
            headers.click(function() {
                var clicked = $(this),
                    container = clicked.next();

                if (!clicked.hasClass(options.activeClass)) {
                    openAccordion(clicked,container);
                } else {
                    closeAccordion(clicked,container);
                }
            });
        }

        function openAccordion(clicked,container) {
            var activeHeader = root.find('h2.' + options.activeClass),
                activeContainer = activeHeader.next();

            closeAccordion(activeHeader,activeContainer);

            container.slideDown(options.slideDuration,function() {
                clicked.add(container).addClass(options.activeClass);
            });
        }

        function closeAccordion(header,container) {
            container.slideUp(options.slideDuration,function() {
                header.add(container).removeClass(options.activeClass);
            });
        }
    };
})(jQuery);