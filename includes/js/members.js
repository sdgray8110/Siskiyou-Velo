var members = {
    content: $('#memberList'),
    memberSearch: $('#memberSearch'),
    noContent: '<li><h2>No matching members found.</h2></li>',

    init: function() {
        $('document').ready(function() {
            members.renderInitialView();
            members.handleKeys();
        });
    },

    renderInitialView: function() {
        $.ajax({
            url: '/wordpress/wp-content/data/memberData.json.php',
            type: 'get',
            dataType: 'JSON',
            success: function(data) {
                members.memberData = data;
                members.applyTemplate(data);
            }
        });
    },

    applyTemplate: function(data) {
        var template = $('#memberData').tmpl(data);
        members.content.html(template);
    },

    handleKeys: function() {
        members.memberSearch.keyup(function() {
           members.filterSearch($(this).val());
        });
    },

    filterSearch: function(val) {
        var newMembersList = [];

        for (i=0;i<members.memberData.length;i++) {
            if (members.memberData[i].name.toLowerCase().match(val.toLowerCase())) {
                newMembersList.push(members.memberData[i]);
            }
        }

        if (newMembersList.length) {
            members.applyTemplate(newMembersList);
        } else {
            members.content.html(members.noContent);
        }
    }
};

members.init();