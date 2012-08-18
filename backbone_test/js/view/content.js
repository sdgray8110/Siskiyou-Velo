(function($) {
    window.ContentView = Backbone.View.extend({
        initialize: function() {
            _.bindAll(this,'render');
            this.model.bind('change',this.render);
            this.template = _.template($('#template').html());
        },

        render: function() {
            var renderedContent = this.template(this.model.toJSON());
            $(this.el).html(renderedContent);
            return this;
        }
    });

    window.LibraryContentView = ContentView.extend({

    });

    window.LibraryView = Backbone.View.extend({
        tagName: 'section',
        className: 'library',

        initialize: function() {
            _.bindAll(this, 'render');
            this.template = _.template($('#library-template').html());
            this.collection.bind('reset', this.render);
        },

        render: function() {
            var $contents,
                collection = this.collection;

            $(this.el).html(this.template({}));

            $contents = this.$('.contents');
            collection.each(function(content) {
                var view = new LibraryContentView({
                   model: content,
                   collection: collection
                });

                $contents.append(view.render().el);
            });

            return this;
        }
    });
})(jQuery);