var results = {
    setVars: function() {
        var data = {
            resultsContainer: $('#resultsContent'),
            dropdown: $('#race_years_select'),
            year: global.pageData.ajaxHelpers.year
        };

        $.extend(results,data);
    },

    init: function() {
        results.setVars();
        results.attachHandlers();
    },

    attachHandlers: function() {
        results.dropdown.bind('fauxSelectChange', function(e) {
            var year = e.selectedValue;

            if (year != results.year) {
                results.year = year;
                results.getResults({'year': year});
            }
        });
    },

    getResults: function(year) {
        var url = global.pageData.ajaxHelpers.templateDir + global.pageData.ajaxHelpers.handler;

        $.ajax({
            type: 'GET',
            url: url,
            data: year,
            success: function(data) {
                results.resultsContainer.fadeOut(250, function() {
                    results.resultsContainer.html(data);
                    results.resultsContainer.fadeIn(250);
                })
            }

        });
    }
};

$(document).ready(function() {
    results.init();
});
