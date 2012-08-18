// Inline Label //
(function($) {
	$.fn.inlineLabel = function() {
        var root = $(this),
            elId = root.attr('id'),
            label = $('label[for="'+elId+'"]').text();

        root.live('focus',function(){
            clearInlineLabel();
        });

        root.live('blur',function(){
            setInlineLabel();
        });

        setInlineLabel();

        function setInlineLabel() {
            if (!root.val) {
                root.attr('value',label);
            }
        }

        function clearInlineLabel() {
            if (root.val === label) {
                root.attr('value','');
            }
        }
	};
})(jQuery);