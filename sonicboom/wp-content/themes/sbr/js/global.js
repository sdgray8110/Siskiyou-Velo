var global = {
    init: function() {
        global.jq();
        global.setVars();
        global.applyFancyboxes();
        global.attachHandlers();
    },

    jq: function() {
        if (typeof(window.$) == 'undefined') {
            window.$ = jQuery;
        }
    },

    setVars: function() {
        var data = {
            window: $(window),
            footer: $('#footer'),
            pageData: global.buildPageData()
        };

        $.extend(global,data);
    },

    buildPageData: function() {
        var pageData = {},
            els = $('.pageData'),
            len = els.length;

        if (len) {
            for (i=0;i<len;i++) {
                var el = $(els[i]),
                    id = el.attr('id'),
                    data = $.parseJSON(el.text());

                pageData[id] = data;
            }
        }

        return pageData;
    },

    attachHandlers: function() {
        $('.faux-select').fauxSelect();
    },

    applyFancyboxes: function() {
        global.footer.find('ul a').fancybox({
            padding: 30,
            openEffect : 'elastic',
            openSpeed  : 150,
            closeEffect : 'elastic',
            closeSpeed  : 150,
            closeClick : true,
            type: 'ajax'
        });
    },

    applyTemplate: function (options) {
        var settings = {
            data: {},
            container: [],
            templateEl: {},
            callback: function() {}
        },
            options = $.extend({}, settings, options),
            template = options.templateEl.tmpl(options.data);

        options.container.html(template);
        options.callback();

    },

    embeddableVideoURL: function(url) {
        return url.replace('http://gdata.youtube.com/feeds/videos/','');
    },

    youtubeDateFmt: function(timestamp) {
        // 2012-05-05T03:11:10.000Z

        var date = timestamp.split('T')[0],
            dateArr = date.split('-');

        return dateArr[1] + '/' + dateArr[2];
    },

    validate: function(data,formEl) {
        var invalid = [];

        for (var key in data) {
            var type = global.validationType(key);

            if (!global.validationRules[type](data[key])) {
                invalid.push(key);
            }
        }

        formEl.find('.validationErrors').remove();

        if (invalid.length) {
            global.validationMessaging(invalid,formEl);
        }

        return (!invalid.length)
    },

    validationType: function(name) {
        var type = 'required';

        if (name.match('email')) {
            type = 'email';
        }

        return type;
    },

    validationMessaging: function(errors,formEl) {
        var messaging = $('<ul class="validationErrors"></ul>'),
            form = formEl.parents('form');

        for (i=0;i<errors.length;i++) {
            var message = '<li>' + global.validationMessages[errors[i]] + '</li>';
            messaging.append(message);
        }

        formEl.prepend(messaging);
        global.revalidateForm(form,formEl);

        form.find('input,textarea').change(function() {
            var formData = form.serializeObject();
            global.validate(formData,formEl);
        });
    },

    revalidateForm: function(form,formEl) {
        if (!global.validationHandlersApplied) {
            global.validationHandlersApplied = true;

            form.find('input,textarea').on('keyup', function() {
                var formData = form.serializeObject();
                global.validate(formData,formEl);
            });

            form.bind('fauxSelectChange', function() {
                var formData = form.serializeObject();
                global.validate(formData,formEl);
            });
        }
    },

    validationRules: {
        required: function(value) {
            return value != '';
        },
        email: function(value) {
            var regex = /^[A-Za-z0-9][\w,=!#|$%^&*+/?{}~-]+(?:\.[A-Za-z0-9][\w,=!#|$%^&*+/?{}~-]+)*@(?:[A-Za-z0-9-]+\.)+[a-zA-Z]{2,9}$/i;
            return regex.test(value);
        }
    },

    validationMessages: {
        'department': 'Please select a department.',
        'name': 'Please enter your name.',
        'email': 'Please enter a valid email address.',
        'subject': 'Please select a subject.',
        'message': 'Please enter a message.'
    },

    setTabIndex: function(fieldset) {
        var els = fieldset.find('input,textarea,.faux-select .value, .submit'),
            ignore = ['hidden'],
            index = 1;

        els.each(function() {
            var el = $(this);

            if ($.inArray(el.attr('type'),ignore) < 0) {
                el.attr('tabindex',index);

                index += 1;
            }
        });
    }
};

$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$(document).ready(function() {
    global.init();
});