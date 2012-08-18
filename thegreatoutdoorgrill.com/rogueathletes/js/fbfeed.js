(function ($) {
    var settings = {
        userID: 115104251898013
    };

    $.fn.fbFeed = function (options) {
        root = $(this),
        options = $.extend({}, settings, options);

    if (root.length) {
        loadFeed();
    }

    function loadFeed() {
        var url = 'http://graph.facebook.com/'+options.userID+'/feed?callback?';

        showLoadingGif();

        $.ajax({
            type : 'get',
            url : url,
            dataType : 'jsonp',
            success : function(json) {

                var i = 0;
                $.each(json.data,function(i,post){

                    var postObj = {
                        user : post.from.name,
                        userId : post.from.id,
                        link : buildLink(post.link, post.name),
                        icon : buildIcon(post.icon),
                        message : post.message,
                        time : parseTime(post.created_time),
                        commentCount : commentCount(post),
                        comments : post.comments,
                        thumb : 'http://graph.facebook.com/'+post.from.id+'/picture'
                    };

                    renderPost(postObj, i);

                    i++;
                });

                removeLoadingGif();
                handleClicks();
            }
        });
    }

    function commentCount(post) {
        post.comments && post.comments != undefined ? comments = post.comments.data.length : comments = 0;

        return comments;
    }

    function renderCommentCount(postObj) {
        if (postObj.commentCount > 0) {
            var comments = {};

            postObj.commentCount > 1 ? commentsTitle = 'comments' : commentsTitle = 'comment';
            comments.count = ' - <a class="comments" href="">'+ postObj.commentCount + ' ' + commentsTitle + '</a>';
            comments.comments = renderComments(postObj.comments.data);
        } else {
            var comments = '';
        }

        return comments;
    }

    function renderComments(comments) {
        var commentStr = '';

        $.each(comments,function(i,comment){
            var id = comment.id,
                user = comment.from.name,
                userId = comment.from.id,
                thumb = 'http://graph.facebook.com/'+userId+'/picture',
                message = comment.message,
                time = parseTime(comment.created_time);

            commentStr += '<li><img src="'+thumb+'" alt="'+user+'" /><div><p><a target="_blank" class="username" href="http://www.facebook.com/profile.php?id='+userId+'">' + user + '</a> '+message+'</p><p class="postDetails">'+time+'</p></div></li>';
        });

        return '<ul class="comments">'+commentStr+'</ul>';
    }

    function buildIcon(postIcon) {
        !postIcon ? icon = '' : icon = '<img src="'+postIcon+'" alt="postIcon" /> ';

        return icon;
    }

    function buildLink(href, name) {
        !name ? name = 'View Photo' : name = name;

        if (href) {
            return '<p class="postLink"><a href="'+href+'">'+name+'</a></p>';
        } else {
            return '';
        }
    }

    function buildMessage(postObj) {
        if (postObj.message) {
            return '<p>' + postObj.message + '</p>';
        } else {
            return '';
        }
    }

    function renderPost(postObj, i) {
        var comments = renderCommentCount(postObj);

        if (typeof(comments) == 'object') {
            comments = comments.count + comments.comments;
        }

        var message = buildMessage(postObj),
            content = '<li id="fbPost'+i+'"><img src="'+postObj.thumb+'" alt="'+postObj.user+'" /><div><p><a target="_blank" class="username" href="http://www.facebook.com/profile.php?id='+postObj.userId+'">' + postObj.user + '</a></p>' + message + postObj.link+ '<p class="postDetails">'+postObj.icon+postObj.time + comments+'</p></div></li>';

        root.append(content);
    }

    function parseTime(time) {
        //Format: 2010-08-09T14:58:03+0000

        var date = time.split('T')[0].split('-'),
            year = date[0],
            month = handleZeroes(date[1]),
            day = date[2];

        return month + '/' + day  + '/' + year;
    }

    function handleZeroes(month) {
        if (month.charAt(0) == 0) {
            month = month.charAt(1);
        }
        return month;
    }

    function showLoadingGif() {
        root.before('<img id="fbLoadingGif" src="docroot/img/util/fb_loader.gif" alt="loading" />');
    }

    function removeLoadingGif() {
        $('#fbLoadingGif').remove();
    }

    function handleClicks() {
        var commentLink = root.find('a.comments');

        commentLink.live('click', function(e) {
            e.preventDefault();

            var thisEl = $(this),
                comments = thisEl.parent().next();

            comments.fadeToggle(250);
        });
    }

    };
})(jQuery);