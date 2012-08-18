$('#buildOrder .categorySelection').live('change', function() {
    var el = $(this),
        value = el.val();

    if (value != 'Select a category below...') {
        loadProducts(value,el);
    } else {
        el.next().empty();
    }

    toggleHidden('hide');
});

$('#buildOrder fieldset select').live('change', function() {
    var el = $(this),
        value = el.val().split('|'),
        formEls = buildProductRow(value);

        if (value != 'Select a part below...') {
            el.next().html(formEls);
            toggleHidden('show');
            updateTotals();
        } else {
            el.next().empty();
            toggleHidden('hide')
        }
});

$('#buildOrder .quantity').preventKeyCode({
    entryType: 'numeric'
});

$('#buildOrder .quantity').live('keyup', function() {
    var el = $(this),
        parent = el.parents('fieldset'),
        qty = parseInt(el.val()),
        unitPrice = parseFloat(parent.find('select').val().split('|')[2]),
        newPrice = parseFloat(qty * unitPrice).toFixed(2);

    newPrice == 'NaN' ? newPrice = '0.00' : newPrice = newPrice;

    if (newPrice == '0.00') {
        toggleHidden('hide');
    } else {
        toggleHidden('show');
    }

    parent.find('.total').html('$' + newPrice);
    updateTotals();
});

$('#addAnother').click(function(e) {
    e.preventDefault();
    addAnotherAjax();
    toggleHidden('hide');
});

$('#saveOrder').click(function(e) {
    e.preventDefault();
    saveOrder();
});

function saveOrder() {
    var query = serializeData().join(':');

    console.log('products=' + query);
}

function serializeData() {
    var items = $('#buildOrder fieldset'),
        len = items.length,
        productString = [];

    for (i=0;i<len;i++) {
        var item = items.eq(i),
            arr = [];

        arr[0] = item.find('.partNumber').text();
        arr[1] = item.find('.quantity').val();

        productString.push(arr.join('|'));
    }

    return productString;
}

function buildProductRow(value) {
    var array = [];
    array[0] = '<span class="partNumber">'+value[1]+'</span>';
    array[1] = '<span class="price">$'+value[2]+'</span>';
    array[2] = '<span><input maxlength="3" type="text" class="quantity" value="1" /></span>';
    array[3] = '<span class="total">$'+value[2]+'</span>';

    return array.join('');
}

function loadProducts(category,el) {
    $.ajax({
        type: 'POST',
        data: 'cat=' + category,
        url: 'includes/loadProduct.php',
        success: function(data) {
            el.next().html(data + '<div class="productRow"></div>');
        }
    });
}

function toggleHidden(state) {
    var addAnother = $('#addAnother, #finalTotalContainer, #saveOrder');
    if (state == 'show') {
        addAnother.fadeIn();
    } else {
        addAnother.fadeOut();
    }
}

function addAnotherAjax() {
    $.ajax({
        type: 'GET',
        url: 'includes/categorySelection.php',
        success: function(data) {
            $('#buildOrder').append(data);
        }
    });
}

function updateTotals() {
    var totals = $('#buildOrder').find('.total'),
        price = 0,
        len = totals.length;

    for (i=0;i<len;i++) {
        var itemStr = totals.eq(i).text().replace(/\$/g,''),
            itemPrice = parseFloat(itemStr);

        price = parseFloat(price) + itemPrice;
    }

    $('#finalTotal').text('$' + price.toFixed(2));
}