// JSON Calendar Constructor //
(function($) {
    var settings = {
        jsonURL : 'docroot/js/nordstromProducts.json'
    };

	$.fn.nordstrom = function(options) {
        var root = $(this),
            options = $.extend({}, settings, options);

        nordstromAjax(options.jsonURL);

        function nordstromAjax(url) {
            $.getJSON(url,function(data) {
                var products = data.Fashions;

                buildProducts(products);
            });
        }

        function buildProducts(products) {
            var len = products.length;

            if (root.children.length) {
                root.empty();
            }

            for (i=0;i<len;i++) {

                var product = {
                    img : buildImage(products[i]),
                    sticker : buildSticker(products[i]),
                    title : buildTitle(products[i]),
                    price : buildPrice(products[i])
                };

                buildProductGrid(product);
            }
        }

        function buildImage(products) {
            return '<a class="thumb" href="http://shop.nordstrom.com/S/'+products.Id+'"><img src="http://g.nordstromimage.com/imagegallery/store/product/Medium'+products.PhotoPath+'" alt="" /></a>';
        }

        function buildSticker(products) {
            var sticker;
            products.New === true ? sticker = '<p class="sticker">New</p>' : false;

            return sticker;
        }

        function buildTitle(products) {
            return '<h5><a href="http://shop.nordstrom.com/S/'+products.Id+'">'+products.Title+'</a></h5>';
        }

        function buildPrice(products) {
            var priceData = products.PriceDisplay;

            priceData.SalePrice == null ? price = '<p class="price">'+priceData.RegularPrice+'</p>' : price = '<p class="price"><span class="onSale">On Sale</span>: '+priceData.SalePrice+'</p>';

            return price;
        }

        function buildProductGrid(product) {
            product.sticker ? sticker = product.sticker : sticker = '';

            root.append('<li>'+product.img+sticker+product.title+product.price+'</li>')
        }

    };
})(jQuery);

$('#productGrid').nordstrom();

$('.filters a').click(function(e) {
    e.preventDefault();

    var root = $(this),
        json = root.attr('rel');

    if (root.hasClass('clear')) {
        root.hide();
    } else {
        $('.filters .clear').show();
    }
        

    $('#productGrid').nordstrom({
        jsonURL : json
    });
});

