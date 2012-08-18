(function ($) {
    var settings = {
        userID: '157063537639179'
    };

    $.fn.fbAlbum = function (options) {
        options = $.extend({}, settings, options);

    loadAlbumList(options.userID);

    // Album Image Click
    $('#fbAlbums a.fbAlbumThumb').live('click', function(e) {
        e.preventDefault();
        var root = $(this),
            album = root.attr('rel'),
            name = root.find('img').attr('alt');

            $('#fbAlbums').fadeOut(200, function() {
                loadAlbum(album);
                $('#fbPhotos h2').text(name);
            });
    });

    // Photo Click
    $('#fbPhotos .fbThumbs a').live('click', function(e) {
        e.preventDefault();
        var root = $(this);

        if (root.parents('li').hasClass('selected')) {
        } else {
            fireImages();
        }

        function fireImages() {
            var obj = {
                    img : root.attr('href'),
                    name : root.siblings('var').text(),
                    tags : root.attr('rel'),
                    comments : root.attr('rev')
                },
                tagObj = {
                    imgTag : '<img src="'+obj.img+'" alt="" />',
                    name : '<h4>'+obj.name+'</h4>',
                    tagged : '<dl class="tagged"><dt>In This Photo:</dt><dd>'+obj.tags+'</dd></dl>',
                    comments : '<p><a href="">' + obj.comments + ' comments</a></p>'
                };

            $('#fbPhotos .selected').removeClass('selected');
            root.parents('li').addClass('selected');
            displayBigImg(tagObj, obj);
        }
    });

    // Back Button Click
    $('#fbPhotos a.back').live('click', function(e) {
        e.preventDefault();
        $('#fbPhotos').fadeOut(200, function() {$('#fbAlbums').show();});
        $('#fbThumbStyles, #fbPhotos .fbThumbs, #bigImg').empty();
    });

    function loadAlbumList(id) {
        var url = 'https://graph.facebook.com/'+id+'/albums?callback=?';
        $.getJSON(url,function(json){
                var i = 0;
                $.each(json.data,function(i,album){
                    var id = album.id,
                        thumb = 'https://graph.facebook.com/'+id+'/picture',
                        albumUrl = album.link,
                        name = album.name,
                        count = album.count,
                        type = album.type,
                        details = '<p><a href="'+albumUrl+'">'+name+'</a></p><p>'+count+' photos</p>',
                        albumEl = '<li><a class="fbAlbumThumb" href="'+albumUrl+'" rel="'+id+'"><img src="'+thumb+'" alt="'+name+'"/></a>'+details+'</li>';

                        if (type != 'profile') {
                            $('#fbAlbums .fbThumbs').append(albumEl);
                        }
                    i++;
                });
        });
    }

    function loadAlbum(id){
      var url = 'https://graph.facebook.com/'+id+'/photos?callback=?';

        $.getJSON(url,function(json){
                var i = 0;
                $.each(json.data,function(i,image){
                    var thumb = {
                            src : image.images[1].source,
                            position : image.images[1].height / 135 > 1 ? '0 -' + Math.floor((image.images[1].height - 135) / 2) + 'px'  : '0 0'
                    },
                        full = image.source,
                        tagLength = !image.tags ? 0 : image.tags.data.length,
                        name = !image.name ? '' : image.name,
                        tag = tagLength == 0 ? '' : ' rel="' + parseTags(image.tags.data) + '"',
                        commentLength = !image.comments ? '' : ' rev="' + image.comments.data.length + '"',
                        content = '<li><a class="fbThumbs'+i+'" href="'+full+'" ' + tag + commentLength + '></a><var>'+name+'</var></li>',
                        bigImg = '<img src="'+full+'" alt="" />',
                        style = '.fbThumbs' + i + ' {background:url('+thumb.src+') no-repeat ' + thumb.position + '}';

                    $('#fbThumbStyles').append(style);
                    $('#fbPhotos .fbThumbs').append(content);

                    if (i === 0) {
                        $('#fbPhotos li').addClass('selected');
                        $('#bigImg').html(bigImg);
                    }
                    i++;
                });

                $('#fbPhotos').fadeIn(200);
        });
    }

    function displayBigImg(tagObj, obj) {
        $('#bigImg').fadeOut(200, function() {
            $('#bigImg').html(tagObj.imgTag).append('<div class="imageContent"></div>');

            if (obj.name != '') {
                $('#bigImg .imageContent').append(tagObj.name);
            }

            if (obj.tags != '') {
                $('#bigImg .imageContent').append(writeTags(tagObj.tagged));
            }

            if (obj.comments != '') {
                $('#bigImg .imageContent').append(tagObj.comments);
            }

            $('#bigImg img').load(function() {
                $('#bigImg').fadeIn(200);
            });
        });
    }

    function parseTags(tags) {
        var tagArray = new Array();
        $.each(tags,function(i,tag){
            var name = tag.name;

            tagArray.push(name);
        });

        return tagArray;
    }

    function writeTags(tags) {
        var tagArray = tags.split(','),
            newPeeps = '';
        $.each(tagArray, function(i, tag) {
          newPeeps += '<p>' + tag + '</p>';
        });

        return newPeeps;        
    }

    };
})(jQuery);

$('fbAlbums').fbAlbum();