(function($) {

    window.Content = Backbone.Model.extend({});

    window.Contents = Backbone.Collection.extend({
        model: Content,
        url: 'js/data/content.json.js'
    });

    window.library = new Contents();

})(jQuery);