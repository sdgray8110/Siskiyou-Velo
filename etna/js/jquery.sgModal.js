(function ($) {
    var settings = {
        type: 'ajax',
        outerContainer: 'modalContainer',
        background: 'modalBackground',
        innerContainer: 'modalContent',
        mainImage: 'modalImage',
        width: '',
        height: ''
    };

    $.fn.sgModal = function (options) {
        var options = $.extend({}, settings, options),
            root = $(this),
            overlay = $('#' + options.background);


        root.live('click', function(e){
            e.preventDefault();
            var obj = $.sgModal.overlayObj(options, root);
            $.sgModal(options, root, obj);
        });

        overlay.live('click', function(){
            $.sgModal.closeOverlay(options);
        });     
    };

    $.sgModal = function(options, root, obj) {
        options = $.extend({}, settings, options),

        $.sgModal.createOverlay(options, root, obj);
    };

    $.extend($.sgModal, {
        overlayObj : function(options, root) {
            var obj = {
                container: '<div id="' + options.outerContainer + '"></div>',
                background: '<div id="' + options.background + '"></div>'
            };

            root.attr('rel') == 'image' ? obj.content = '<div id="' + options.innerContainer + '"><img id="'+options.mainImage+'" src="'+root.attr('href')+'" alt= "" /></div>' : obj.content = '';

            return obj;
        },

        createOverlay : function(options, root, obj) {
            $('body').append(obj.container);
            $('#' + options.outerContainer).append(obj.background + obj.content);

            $('#' + options.mainImage).load(function() {
                var image = document.getElementById(options.mainImage);

                $.sgModal.overlayDims(options, image);
            });
        },

        overlayDims: function(options, image) {
            var win = $(window),
                winHeight = win.height(),
                winWidth = win.width(),
                imageWidth = image.width,
                imageHeight = image.height;

            if (imageWidth < (winHeight * .8) && imageHeight < (winHeight *.8)) {
                options.width = imageWidth + 20;
                options.height = imageHeight + 20;

                $.sgModal.fadeInOverlay(options);
            } else {
                var widthRatio = winWidth / imageWidth,
                    heightRatio = winHeight / imageHeight;

                widthRatio > heightRatio ? multiplier = widthRatio : multiplier = heightRatio;
                var newWidth = (imageWidth * multiplier) * .75,
                    newHeight = (imageHeight * multiplier) * .75;

                options.width = newWidth + 20;
                options.height = newHeight + 20;
                
                $('#' + options.mainImage).css({'width' : newWidth, 'height' : newHeight})

                $.sgModal.fadeInOverlay(options);
            }

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
        }
    });
})(jQuery);

$('#openOverlay').sgModal();