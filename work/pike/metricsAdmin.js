var metricsAdmin = {
    content: $('#userMetricsAdmin'),
    memberSearch: $('#memberSearch'),

    init: function() {
        $('document').ready(function() {
            metricsAdmin.renderInitialView();
        });
    },

    renderInitialView: function() {
        $.ajax({
            url: 'companies.json.js',
            type: 'get',
            dataType: 'JSON',
            success: function(data) {
                metricsAdmin.adminData = data;
                metricsAdmin.applyTemplate(data);
            }
        });
    },

    applyTemplate: function(data) {
        var template = $('#adminData').tmpl(data);
        metricsAdmin.content.html(template);
    }
};

metricsAdmin.init();