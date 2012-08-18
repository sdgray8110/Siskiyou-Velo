/*!
 * Basic no-frills, looping image gallery
 *
 * REQUISITES:
 * -- jQuery 1.4.4
 *
 * ADDITIONAL REQUIREMENTS:
 * -- Accepts a pre-rendered unordered list containing a set of images
 * -- See buildPhotoGallery & renderSponsors php methods for standard markup
 * -- See echelonrace/docroot/css/global.css for sample CSS provisioning
 *
 * Authored by Spencer Gray
 */

(function($) {
    var settings = {
            fadeDuration : 350,
            interval : 7000, 
            targets : 'li',
            active : 'active'
        };

	$.fn.fadeGallery = function(defaults) {
        var root = $(this),
            defaults = $.extend({}, settings, defaults),
            targets = root.find(defaults.targets),
            len = targets.length - 1;

        fireImages();


        // TODO: Verify that Chrome/V8 is no longer queueing each instance of this when tab/window is in the background and fast cycling them on focus.
        function fireImages() {
            defaults.imageTransition = setTimeout(function() {
                fadeImages();
                fireImages();
            }, defaults.interval);
        }

        function fadeImages() {
            var imageInfo = imageObj(),
                nextImage = imageInfo.nextImage,
                curImage = imageInfo.active;

            curImage.fadeOut(defaults.fadeDuration, function() {
                curImage.removeClass(defaults.active);
            });
            nextImage.fadeIn(defaults.fadeDuration, function() {
                nextImage.addClass(defaults.active);
            });
        }

        function imageObj() {
            var imageInfo = {
                active : root.find('.' + defaults.active)
            };

            imageInfo.active.index() == len ? imageInfo.nextImage = targets.eq(0) : imageInfo.nextImage = targets.eq(imageInfo.active.index() + 1);

            return imageInfo;
        }

	};
})(jQuery);