/*!
 * Quick & Dirty module to handle results data in JSON from Echelon Races
 *
 * REQUISITES:
 * -- jQuery 1.4.4
 *
 * COMMENTS:
 * -- If this is the permanent method for displaying results, we need to abstract the JSON handling and overlay methods and get this into a plugin closure or object literal to get these methods out of the global namespace.
 *
 * Authored by Spencer Gray
 */

$('.resultsOverlay').click(function(e) {
    e.preventDefault();

    var root = $(this),
        linkName = root.attr('rel'),
        header = root.text();

    resultsOverlay(linkName,header);
});

function resultsOverlay(linkName, title) {
    $.ajax({
        type: 'GET',
        dataType: 'json',
        cache: 'false',
        url: '/docroot/js/data/' + linkName + '.json.php',
        success: function(data) {
            var table = results(data);

            buildOverlay(table, title);
        }
    })
}

function buildOverlay(content,title) {
    var overlay =[];
    
    overlay[0] = '<div id="overlayBackground"></div>',
    overlay[1] = '<div id="overlayWrap"><div id="overlay"><h1>'+title+'<a href="" class="closeOverlay">Close</a></h1>'+content+'</div></div>';

    $('body').append(overlay.join(''));
    overlayCloseHandler();
}

function closeOverlay() {
    $('#overlayBackground, #overlayWrap').remove();
}

function overlayCloseHandler() {
    $('body').click(function(e){
        var clicked = $(e.target),
            overlay = clicked.parents('#overlay').length;

        if (!overlay) {
            closeOverlay();
        }
    });

    $('.closeOverlay').live('click', function(e) {
        e.preventDefault();
        closeOverlay();
    });
}

function results(obj) {
	var len = obj.length,
		resultsMarkup = [];
	
	for (i=0;i<len;i++) {
		var fieldName = '<tr><td colspan=10><h3>' + obj[i].field + '</h3></td></tr>',
			results = obj[i].results,
			resultSet = buildResults(results);
	
		resultsMarkup.push(fieldName, resultSet);        
	}
	
	return '<table>' + resultsMarkup.join('') + '</table>';
}

function buildResults(results) {
    var len = results.length,
        row = [];

    for (n=0;n<len;n++) {
		var athlete = buildAthlete(results[n]);
		row.push('<tr class="row'+n+'">'+athlete+'</tr>');
    }
	return row.join('');
}

function buildAthlete(athlete) {
	return '<td>' + athlete.join('</td><td>') + '</td>';
}

