(function($) {

    window.ContentRouter = Backbone.Router.extend({
        routes: {
            '': 'home',
            'blank': 'blank'
        },

        initialize: function() {
            this.libraryView = new LibraryView({
                collection: window.library
            });
        },

        home: function() {
            var $content = $('#content');
            $content.empty();
            $content.append(this.libraryView.render().el);
        },

        blank: function() {
            var $content = $('#content');
            $content.empty();
            $content.text('fooeybar')
        }
    });

    $(function() {
        window.App = new ContentRouter();
        Backbone.history.start();
    });

})(jQuery);