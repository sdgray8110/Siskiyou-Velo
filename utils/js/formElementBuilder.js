(function(){
    $('#formBuilder #type option[selected="selected"]').attr('selected','');
    $('#formBuilder #type option:first-child').attr('selected','selected');
})();

$('#formBuilder #type').live('change', function(){
    var elementType = $(this).attr('value');

    elementAjax(elementType)
});

$('#formBuilder #textSubmit').live('click', function(e) {
    e.preventDefault();

    var params = $.toJSON($('#formBuilder').serializeObject()),
        params = $.parseJSON(params);

    switchType(params);

    if (params.type == 'select') {
        elementAjax('option');
    }
});

$('#formBuilder #optionSubmit').live('click', function(e) {
    e.preventDefault();

    var params = $.toJSON($('#formBuilder').serializeObject()),
        params = $.parseJSON(params),
        element = buildOption(params);

    appendOption(element);
});

$('#acceptMarkup').live('click', function(e) {
    e.preventDefault();
    var element = $('#selectMarkupText').text();

    insertMarkup(element, 'option');
    elementAjax('text');
    $('#formBuilder #type option[selected="selected"]').attr('selected','');
    $('#formBuilder #type option[value="text"]').attr('selected','selected');
});

function elementAjax(elementType) {
    var url = 'ajax/' + elementType + '.php';
    $.ajax({
        type: 'get',
        url: url,
        success : function(data) {
            successCallback(data, elementType);
        }
    });
}

function successCallback(data, elementType) {
    elementType != 'option' ? ajaxTarget = $('#formAssembly') : ajaxTarget = $('#selectBuilder');
    ajaxTarget.html(data);
}

function switchType(params) {
    switch(params.type) {
        case 'text':
            var element = buildTextInput(params);
            break;
        case 'checkbox':
           var element =  buildCheckbox(params);
            break;
        case 'radio':
            var element = buildRadio(params);
            break;
        case 'select':
            var element = buildSelect(params);
            break;
        case 'textarea':
           var element =  buildTextarea(params);
            break;
        case 'submit':
            var element = buildSubmit(params);
            break;
        case 'button':
           var element =  buildButton(params);
            break;
    }

    insertMarkup(element, params.type);
}

function insertMarkup(element, type) {
    if (type == 'select') {
        selectMarkup(element);
    } else {

        var markup = $('#markup');

        if (!markup.find('textarea').length) {
            var textarea = '<label for="markupText">Markup:</label><textarea id="markupText">'+element+'</textarea>';

            markup.append(textarea);
        } else {
            var textarea = markup.find('textarea'),
                content = textarea.text(),
                lb = '\r' + '\r',
                newContent = content + lb + element;

            textarea.text(newContent);
        }
    }
}

function buildTextInput(params) {
    var label = '<label for="'+params.elementId+'">'+params.elementLabel+'</label>',
        input = '<input type="text" id="'+params.elementId+'" name="'+params.elementId+'" value="" />';

    return label + '\r' +  input;
}

function buildCheckbox(params) {
    var label = '<label for="'+params.elementId+'">'+params.elementLabel+'</label>',
        input = '<input type="checkbox" id="'+params.elementId+'" name="'+params.elementId+'" value="'+params.elementValue+'" />';

    return label + '\r' +  input;
}

function buildRadio(params) {
    var label = '<label for="'+params.elementId+'">'+params.elementLabel+'</label>',
        input = '<input type="radio" id="'+params.elementId+'" name="'+params.elementName+'" value="'+params.elementValue+'" />';

    return label + '\r' +  input;
}

function buildTextarea(params) {
    var label = '<label for="'+params.elementId+'">'+params.elementLabel+'</label>',
        input = '<textarea id="'+params.elementId+'" name="'+params.elementId+'"></textarea>';

    return label + '\r' +  input;
}

function buildSubmit(params) {
    return '<input type="submit" id="'+params.elementId+'" name="'+params.elementId+'" value="'+params.elementValue+'" />';
}

function buildSelect(params) {
    var label = '<label for="'+params.elementId+'">'+params.elementLabel+'</label>',
        select = '<select id="'+params.elementId+'" name="'+params.elementId+'">' + '\r' + '</select>';
    return label + '\r' + select;
}

function buildOption(params) {
    var option = '<option value="'+params.elementLabel+'">'+params.elementValue+'</option>';

    return '\t' +option + '\r';
}

function selectMarkup(element) {
    var markup = $('#selectMarkup'),
        textarea = '<label for="selectMarkupText">Select Markup:</label><textarea id="selectMarkupText">'+element+'</textarea><input type="submit" id="acceptMarkup" name="acceptMarkup" value="Accept Markup" />';

    markup.html(textarea);
}

function appendOption(element) {
    var textarea = $('#selectMarkup').find('textarea'),
        closingTag = '</select>',
        initMarkup = textarea.text().split(closingTag)[0],
        newMarkup = initMarkup + element + closingTag;

    textarea.text(newMarkup);
    clearInput($('#selectBuilder input[type="text"]'));
}

function clearInput(selector) {
    selector.each(function() {
        $(this).attr('value', '');
    });    
}