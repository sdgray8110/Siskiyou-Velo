// input change plugin //
(function($) {
	var settings = {
        callback : function() {}
	};

	$.fn.inputChange = function(config) {
		config = $.extend({}, settings, config);

        $(this).live('change',function() {
           config.callback();
        });
	};

})(jQuery);

// add another plugin //
(function($) {
	var settings = {
        url : '',
        rootContainerName : '',
        paramString : '?count=',
        callback : function() {}
	};

	$.fn.addAnother = function(config) {
		config = $.extend({}, settings, config);

        $(this).live('click',function(e) {
           e.preventDefault();

           var container = $(this).parent().attr('class'),
               increment = container.replace(config.rootContainerName, '');

            $.ajax({
                type : 'GET',
                url : config.url + config.paramString + increment,
                success : function(data) {
                    $('.' + container).after(data).remove();
                }
            });
        });
	};
})(jQuery);
