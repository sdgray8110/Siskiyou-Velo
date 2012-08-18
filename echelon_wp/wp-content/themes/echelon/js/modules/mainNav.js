/*!
 * Quick dropdown navigation with fade effect
 *
 * REQUISITES:
 * -- jQuery 1.4.4
 *
 * Authored by Spencer Gray
 */

// Main Nav //
(function($) {
    var settings = {
            fadeDuration : 150,
            navItem : 'li.drop',
            subNav : 'ul'
        };

	$.fn.mainNav = function(defaults) {
        var root = $(this),
            defaults = $.extend({}, settings, defaults),
            navItem = root.find(defaults.navItem);

        // TODO - Add delay to prevent two dropdowns from briefly being displayed simultaneously
        navItem.hover(function() {
            var navEl = $(this);
            fadeNav('in', navEl);
        }, function() {
            var navEl = $(this);
            fadeNav('out', navEl);
        });

        function fadeNav(type, el) {
            var subNav = el.find(defaults.subNav);

            if (type === 'in') {
                subNav.fadeIn(defaults.fadeDuration);
            } else {
                subNav.fadeOut(defaults.fadeDuration);
            }
        }

	};
})(jQuery);