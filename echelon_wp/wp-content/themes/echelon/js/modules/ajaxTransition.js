/*!
 * loading gif and transition animations for tabs/ajax
 *
 * REQUISITES:
 * -- jQuery 1.4.4
 *
 * ADDITIONAL REQUIREMENTS:
 * -- See echelonrace/docroot/css/global.css for sample CSS provisioning
 *
 * Authored by Spencer Gray
 */

(function ($) {
    var settings = {
        loadingGif: '/wp-content/themes/echelon/images/util/loading.gif',
        imageId : 'loadingGif',
        duration : 350,
        opacity : .3,
        ajaxCallback : function(){}
    };

    $.fn.ajaxTransition = function (options) {
        var options = $.extend({}, settings, options),
            container = $(this);

        $.ajaxTransition(options, container);
    };

    $.ajaxTransition = function(options, container) {
        options = $.extend({}, settings, options),

        $.ajaxTransition.animateOut(options, container);
    };

    $.extend($.ajaxTransition, {        
        animateOut : function(options, container) {
            $('#' + options.imageId).fadeToggle(options.duration);
            container.fadeTo(options.duration, options.opacity, function() {
                options.callback();
            });
        },

        animateIn : function(container, options) {
            options = $.extend({}, settings, options);

            $('#' + options.imageId).fadeToggle(options.duration);
            container.fadeTo(options.duration,  1);
        },

        createLoadingGif : function(container, options) {
            options = $.extend({}, settings, options);

            var loadingGif = $('<img />',{
                src : options.loadingGif,
                id : options.imageId,
                alt : 'Loading'
            });

            container.before(loadingGif);           
        }

    });
})(jQuery);