// Hover Hint //
(function($) {
    var settings = {
            hint : 'We have a hint!!',
            hintId : 'hoverHint',
            xOffset : 25,
            yOffset : -25
        };

	$.fn.hoverHint = function(defaults) {
        var root = $(this),
            defaults = $.extend({}, settings, defaults);

        handleHovers();

        function handleHovers() {
            root.hover(function(e) {
                writeHint(e);
            }, function() {
                var hint = $('#' + defaults.hintId);
                removeHint(hint);
            });
        }

        function writeHint(e) {
            var hoverHint = '<div id="'+defaults.hintId+'"><p>'+defaults.hint+'</p></div>';

            $('body').append(hoverHint);
            initialHintPosition(e)
        }

        function initialHintPosition(e) {            
            var hint = $('#' + defaults.hintId),
                width = hint.width(),
                height = hint.height(),
                position = {
                    w : width,
                    h : height,
                    x : horizontalPosition(e.pageX, width),
                    y : verticalPosition(e.pageY, height)
            };

            hint.css({'top' : position.y + 'px','left' : position.x + 'px'});

            showHint(hint, position);
        }

        function verticalPosition(yPos, height) {
            var offset = defaults.yOffset + (height * -1),
                newPos = yPos + offset;

            if (newPos < 0) {
                newPos = 0
            }

            return newPos;
        }

        function horizontalPosition(xPos, width) {
            var offset = defaults.xOffset + (width),
                newPos = xPos + offset,
                clientW = document.documentElement.clientWidth;

                if (newPos > clientW) {
                    newPos = clientW
                }

            return newPos;
        }

        function showHint(hint, position) {
            hint.fadeIn(250);
            animateHint(hint, position);
        }

        function removeHint(hint) {
            hint.fadeOut(250, function() {
                $(this).remove();
            });
        }

        function animateHint(hint, position) {
            root.mousemove(function(e) {
                var x = e.pageX + defaults.xOffset,
                    y = verticalPosition(e.pageY, position.h);

               hint.css({'top' : y + 'px','left' : x + 'px'});
            });
        }

	};
})(jQuery);

$('#linky').hoverHint({
    hint : 'This is a really meaningful hint'
});

$('#linky2').hoverHint({
    hint : 'Something really valuable goes here'
});