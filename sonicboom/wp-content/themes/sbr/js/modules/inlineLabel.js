/* Inline Label
 *
 * Takes the value of the HTML5 placeholder attribute and creates
 * an inline label for non-supporting browsers.
 *
 * @author - Spencer Gray
 */

(function () {
    $.fn.inlineLabel = function () {
        var root = this,
            rootEl = root[0],
            label = root.attr('placeholder');

        // Prevent form submission with default value
        root.parents('form').submit(function () {
            if (rootEl.value == label  || rootEl.value == '') {
                return false;
            }
        });

        // Detect support for placeholder attribute
        var i = document.createElement('input');
        $.support.placeholder = 'placeholder' in i;

        // Only fire plugin when placeholder isn't supported
        if (!$.support.placeholder) {
            function setInlineLabel() {
                if (!rootEl.value) {
                    rootEl.value = label;
                    root.addClass('inlineLabel');
                }
            }

            function clearInlineLabel(els) {
                if (rootEl.value === label) {
                    rootEl.value = '';
                    root.removeClass('inlineLabel');
                }
            }

            root.bind('focus', function () {
                clearInlineLabel();
            });

            root.bind('blur', function () {
                setInlineLabel();
            });

            setInlineLabel();

        }
    };

})(jQuery);
