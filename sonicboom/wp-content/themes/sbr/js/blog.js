var blog = {
    init: function() {
        blog.setVars();
        blog.attachHandlers();
    },

    setVars: function() {
        var data = {
            galleryItems: $('.photoGallery').find('a').add('#sidebarGallery')
        };

        $.extend(blog,data);
    },

    attachHandlers: function() {
        blog.blogMenuHandlers();
        blog.applyFancyboxes();
    },


    applyFancyboxes: function() {
        blog.galleryItems.fancybox({
            padding: 0,
            openEffect : 'elastic',
            openSpeed  : 150,
            closeEffect : 'elastic',
            closeSpeed  : 150,
            closeClick : true
        });
    },

    blogMenuHandlers: function() {
        var monthLink = $('#monthSelection'),
            menu = $('.postsMenu');

        monthLink.on('click', function() {
            menu.fadeIn(300, function() {
                blog.closeMenuHandler(menu);
            });
        });

        $('.postsMenu a').on('click', function() {
            var clicked = $(this),
                month = clicked.text();

            blog.renderPostList(month);
        });
    },

    closeMenuHandler: function(menu) {
        var doc = $(document);

        doc.on('click', function(e) {
            var clicked = $(e.target);

            if (!clicked.parents().is(menu)) {
                menu.fadeOut(300, function() {
                    doc.off('click');
                });
            }
        });
    },

    renderPostList: function(month) {
        blog.activeMonth = month;
        global.applyTemplate({
            data: global.pageData.postmenuData,
            container: $('#monthlyPosts'),
            templateEl: $('#blogMenuTemplate'),
            callback: function() {
                blog.blogMenuHandlers();
            }
        });
    }
};

$(document).ready(function() {
    blog.init();
});
