$('#sponsorAdmin input').click(function() {
    var data = queryString($(this));

    checkboxAjax(data, $(this));
});

function queryString(root) {
    var id = root.attr('name').replace('sponsor', ''),
        race = root.parents('td').attr('class');
    root.prop('checked') === true ? value = 1 : value = 0;

    return 'id=' + id + '&race=' + race + '&value=' + value;
}

function checkboxAjax(data, root) {
    $.ajax({
        type: 'GET',
        url: '/admin/updateSponsors.php?' + data,
        success: function() {
            backgroundColor(root);
        }
    });
}

function backgroundColor(root) {
    var td = root.parents('td'),
        background = '<div class="background"></div>',
        existingBack = td.find('.background');

    if (!existingBack.length) {
        td.addClass('fade').append(background);
    } else {
        existingBack.show();
    }
    td.find('.background').fadeOut(1500);
}
