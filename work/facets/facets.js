(function ($) {
    var settings = {
        userID: 157063537639179
    };

    $.fn.facetBuilder = function (options) {
        root = $(this),
        options = $.extend({}, settings, options);

	buildFacets();
    handleClicks();

	function buildFacets() {
		$.ajax({
			type: 'GET',
			url: 'facets.json.js',
			dataType: 'json',
            cache: 'false',
			success: function(data) {
                $.fn.facetBuilder.facets = data;
				parseFacets(data);
			}
		});

	}
	
	function parseFacets(data) {
		var facets = data.facets,
			len = facets.length;
		
		for(i=0;i<len;i++) {
			renderFacets(facets[i]);
		}
	}
								
	function renderFacets(facet) {
	   var facetLength = facet.methods.length,
		   selections = '';
	
		for(x=0;x<facetLength;x++) {
			selections += renderSelections(facet.methods[x]);
		}
		
		var thisFacet = '<h3 class="facet_'+facet.type+'">' + facet.facetName + '</h3>' + '<ul id="facet_'+facet.type+'">'+selections+'</ul>';
		
		root.append(thisFacet);
	}
								
	function renderSelections(selection) {
		selection.checked ? checkedClass = "active" : checkedClass="inactive";
		
		return '<li class="'+checkedClass+'"><a rel="'+selection.facetValue+'">'+selection.facetName+' ('+selection.itemCount+')</a></li>';
	}

    function handleClicks() {
        root.find('a').live('click', function() {
            var clicked = $(this),
                targetEl = clicked.parents('li');

            updateSelection(targetEl);

            serializeActive();
        });
    }

    function updateSelection(targetEl) {
        targetEl.hasClass('active') ? newClass = 'inactive' : newClass = 'active';
        targetEl.removeClass('active').removeClass('inactive').addClass(newClass);
    }

    function serializeActive() {
        var activeEls = root.find('.active'),
            activeLen = activeEls.length,
            query = '';

        for (i=0;i<activeLen;i++) {
            var curEl = activeEls[i],
                value = $('a', curEl).attr('rel');

            query += '&' + value + '=true';
        }

        console.log(query);
    }
		
    };
})(jQuery);

$('#facets').facetBuilder();