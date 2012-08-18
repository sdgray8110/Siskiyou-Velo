(function ($) {
    var settings = {
        type: 'ajax',
        outerContainer: 'modalContainer',
        background: 'modalBackground',
        innerContainer: 'modalContent',
        mainImage: 'modalImage',
        width: '',
        height: '',
        modifiedAjax: false,
        ajaxCallback: function(){}
    };

    $.fn.sgModal = function (options) {
        var options = $.extend({}, settings, options),
            targetEl = $(this),
            overlay = $('#' + options.background);


        targetEl.live('click', function(e){
            var root = $(this);
            
            e.preventDefault();
            $.sgModal(options, root);
        });

        overlay.live('click', function(){
            $.sgModal.closeOverlay(options);
        });     
    };

    $.sgModal = function(options, root) {
        options = $.extend({}, settings, options),

        $.sgModal.createOverlay(options, root);
    };

    $.extend($.sgModal, {
        createOverlay : function(options, root) {
            var container = '<div id="' + options.outerContainer + '"></div>',
                background = '<div id="' + options.background + '"></div>',
                content = '<div id="' + options.innerContainer + '"></div>';
            $('body').append(container);
            $('#' + options.outerContainer).append(background + content);

            $.sgModal.populateOverlay(options, root);
        },

        populateOverlay : function(options, root) {
            var rel = root.attr('rel'),
                href = root.attr('href'),
                el = $('#' + options.innerContainer);

            if (rel == 'gallery' || rel == 'image') {

                el.append('<img id="'+options.mainImage+'" src="'+href+'" alt="" />');
                el.find('img').load(function() {
                    var img = $('#' + options.mainImage),
                        dims = $.sgModal.getDims(img);

                    options.width = dims.width + 10,
                    options.height = dims.height + 10;
                    
                    $.sgModal.fadeInOverlay(options);
                });
            } else {
                $.sgModal.ajax(options, href, el);
            }
        },

        ajax : function(options, href, el) {
            $.ajax({
                type : 'GET',
                url : href,
                success : function(data) {
                    if (options.modifiedAjax) {
                        el.html(options.ajaxCallback(data));
                    } else {
                        el.html(data);
                    }
                }
            });

        },

        fadeInOverlay : function(options) {
            $('#' + options.background).fadeTo(200, .5, function() {
                $.sgModal.showOverlay(options);
            });
        },

        showOverlay : function(options) {
            $('#' + options.innerContainer).show(function() {
                $('#' + options.innerContainer).css('left', '0');
                $.sgModal.animateOverlay(options, 'open');
            });
        },

        closeOverlay : function(options) {
            $.sgModal.animateOverlay(options, 'close');
        },

        animateOverlay : function(options, type) {
            var el = $('#' + options.innerContainer),
                container = $('#' + options.outerContainer);

            switch(type) {
                case 'open':
                    anim1 = {width:options.width},
                    anim2 = {height:options.height},
                    anim3 = '';

                break;

                case 'close' :
                    anim1 = {height:0},
                    anim2 = {width:0},
                    anim3 = {opacity:0};
                break;
            }


            el.animate(anim1, 200, function() {
                el.animate(anim2, 200, function() {
                    if (anim3 != '') {
                        container.animate(anim3, 200, function() {
                            container.remove();
                        });
                    } else {
                        $('#'+options.mainImage).fadeIn(100);
                    }
                });
            });
        },

        getDims : function(el) {
            var dims = {
                width : parseInt(el.css('width').replace('px', '')),
                height : parseInt(el.css('height').replace('px', ''))
            };

            return dims;
        }
    });
})(jQuery);

$('#openOverlay').sgModal();