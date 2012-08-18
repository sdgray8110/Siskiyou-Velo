/*!
 * IN DEVELOPMENT
 * Picasa JSON plugin
 *
 * REQUISITES:
 * -- jQuery 1.4.4
 *
 * COMMENTS:
 * -- This needs to be abstracted. Ultimate goal should be to parse the JSON and either return an object or markup that can be handled by a more generic image gallery module.
 *
 * Authored by Spencer Gray
 */

(function($) {
    var settings = {
            url: 'http://picasaweb.google.com/data/feed/base/user/112042780804656645178/albumid/5596220564462510289?&kind=photo&thumbsize=64&imgmax=640&hl=en_US&alt=json',
            displayThumbs: 10,
            thumbContainer: $('#picasaThumbs'),
            imgContainer: $('#picasaImg'),
            title: $('#galleryTitle')
        };

	$.fn.picasaGallery = function(defaults) {
        var options = $.extend({}, settings, options);
        options.root = $(this);

         $.picasaGallery(options)
	};

    $.picasaGallery = function(options) {
        $.picasaGallery.options = $.extend({}, settings, options),
        $.picasaGallery.init();
    };

    $.extend($.picasaGallery, {
        init: function() {
            $.ajaxTransition.createLoadingGif($.picasaGallery.options.root);
            $.picasaGallery.options.root.ajaxTransition({
                callback: function() {
                    $.picasaGallery.active = 0;
                    $.picasaGallery.handleClicks();
                    $.picasaGallery.getData();
                }
            });
        },

        handleClicks: function() {
            $.picasaGallery.options.thumbContainer.find('.thumb a').live('click', function(e) {
                e.preventDefault();

                var root = $(this),
                    img = root.find('img'),
                    curImg = $.picasaGallery.options.imgContainer.find('img').attr('src'),
                    src = root.attr('href'),
                    alt = img.attr('alt'),
                    offset = root.attr('rel');

                if (src != curImg && !$.picasaGallery.active) {
                    $.picasaGallery.changeImg(src,alt,offset);
                }
            });

            $.picasaGallery.options.thumbContainer.find('.pager a').live('click', function(e) {
                e.preventDefault();

                var pager = $(this);

                if (!pager.hasClass('disabled')) {
                    var newStart = parseInt($.trim(pager.attr('class').replace('active', '').replace('start','')));

                    $.picasaGallery.showThumbs(newStart);
                }
            });
        },

        getData: function() {
            $.ajax({
                type:'GET',
                dataType:'jsonp',
                url:$.picasaGallery.options.url,
                success: function(data) {
                    $.picasaGallery.response = data.feed;
                    $.picasaGallery.showTitle();
                    $.picasaGallery.showImg(0);
                    $.picasaGallery.showThumbs(0);
                },
                error: function() {
                    // do stuff
                },
                complete: function() {
                    $.ajaxTransition.animateIn($.picasaGallery.options.root)
                }
            });
        },

        showTitle: function() {
            $.picasaGallery.options.title.text($.picasaGallery.response.title.$t);
        },

        showImg: function(i) {
            var img = $.picasaGallery.response.entry[i].content.src,
                alt = $.picasaGallery.response.entry[i].media$group.media$description.$t;

            $.picasaGallery.options.imgContainer.html('<img src="'+img+'" alt="'+alt+'" />')
        },

        showThumbs: function(start) {
            var images = $.picasaGallery.response.entry,
                displayCt = $.picasaGallery.options.displayThumbs,
                thumbs = [];
            
            images.length >= (start + displayCt) ? len = displayCt : len = images.length - start;

            var pagers = pagers = $.picasaGallery.buildPagers(start, images.length, displayCt);

            for (i=start;i<(start + len);i++) {
                var node = images[i],
                    thumb = node.media$group.media$thumbnail[0].url,
                    alt = node.media$group.media$description.$t,
                    img = node.content.src,
                    dims = $.picasaGallery.hOffset(node.media$group.media$content[0].width);

                i == start ? selectedClass = ' class="selected"' : selectedClass = '';

                thumbs.push('<li class="thumb"><a' + selectedClass + ' rel="'+dims+'" href="'+img+'"><img src="'+thumb+'" alt="'+alt+'" /></a></li>')
            }

            thumbs.unshift(pagers[0]);
            thumbs.push(pagers[1]);

            $.picasaGallery.options.thumbContainer.html(thumbs.join(''));
        },

        buildPagers: function(start, len, displayCt) {
            var firstPage = start == 0,
                finalPage = len - start <= displayCt,
                pagers = [];

            firstPage ? leftPagerClass = 'disabled start0' : leftPagerClass = 'active start' + (start - displayCt);
            finalPage ? rightPagerClass = 'disabled start0' + (start) : rightPagerClass = 'active start' + (start + displayCt);

            pagers[0] = '<li class="pager left"><a href="" class="'+leftPagerClass+'">&lt;</a></li>';
            pagers[1] = '<li class="pager right"><a href="" class="'+rightPagerClass+'">&gt;</a></li>';

            return pagers;
        },

        changeImg: function(img, alt, offset) {
            $.picasaGallery.active = 1;
            var curImage = $.picasaGallery.options.imgContainer.find('img'),
                image = $('<img />').attr('src', img).attr('alt', alt).css({'left' : offset + 'px', 'display' : 'none'});

            curImage.after(image);

            image.load(function() {
                curImage.fadeOut(350);
                image.fadeIn(350, function() {
                    curImage.remove();
                    $.picasaGallery.active = 0;
                });
            });
        },

        hOffset: function(width) {
            return parseInt((640 - width) / 2);
        }
    });
})(jQuery);

$('#mainContent > div').picasaGallery();