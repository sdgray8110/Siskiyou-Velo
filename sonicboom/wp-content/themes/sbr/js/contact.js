var contact = {
    init: function() {
        contact.setVars();
        contact.attachHandlers();
        global.setTabIndex(contact.form.find('fieldset'));
    },

    setVars: function() {
        var data = {
            form: $('#contact_us'),
            submit: $('#contactFormSubmit'),
            inlineLabel: $('.inlineLabel')
        };

        $.extend(contact,data);
    },

    attachHandlers: function() {
        contact.submit.on('click', function(e) {
            e.preventDefault();
            var formData = contact.form.serializeObject(),
                fieldset = contact.form.find('fieldset'),
                validity = global.validate(formData,fieldset);

            if (validity) {
                contact.submitForm(formData,fieldset);
            }

            e.preventDefault();
        });

        contact.inlineLabel.each(function() {
            $(this).inlineLabel();
        });
    },

    submitForm: function(data,fieldset) {
        var url = global.pageData.ajaxHelpers.templateDir + global.pageData.ajaxHelpers.handler;
        contact.submit.off('click')
                      .on('click', function(e) {
                        e.preventDefault();
                      })
                      .fadeTo(.3);

        $.ajax({
            type: 'post',
            data: data,
            url: url,
            success: function(res) {
                contact.form.fadeOut(350, function() {
                    contact.form.html(res);
                    contact.form.fadeIn();
                    global.window[0].scrollTo(0,0);
                });
            }
        });
    }
};

$(document).ready(function() {
   contact.init();
});
