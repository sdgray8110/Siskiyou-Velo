<style>
* {font-family:arial, helvetica, san-serif; font-size:13px;}
select {display:block; width:245px;}
label, input, dt, dd {clear:both;display:block; float:left; width:120px; margin:5px 5px 0 0; font-weight:700}
label, dt {text-align:right;}
input, dd {clear:none; font-weight:300;}
dl {clear:both;}
</style>

<form id="vamCalc">
    <select id="climbs">
        <option value="">Select a climb</option>
    </select>

    <label for="distance">Distance: </label>
    <input id="distance" type="text" value="" />

    <label for="elevation">Elevation Gain: </label>
    <input id="elevation" type="text" value="" />

    <label for="vam">VAM: </label>
    <input id="vam" type="text" value="1500"/>

    <input type="submit" id="submit" value="Calculate" />
</form>

<dl>
    <dt>Grade:</dt>
    <dd id="gradeDisplay"></dd>

    <dt>Time:</dt>
    <dd id="timeDisplay"></dd>

    <dt>Speed:</dt>
    <dd id="speedDisplay"></dd>
</dl>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
<script type="text/javascript">
var climbData = {
    "Cady" : {
        "distance" : 1.6,
        "elevation" : 623
    },
    "Dry Creek Rd" : {
        "distance" : 1.2,
        "elevation" : 416
    },
    "West Griffin Creek Rd" : {
        "distance" : 2.2,
        "elevation" : 667
    },
    "Anderson Butte" : {
        "distance" : 3.7,
        "elevation" : 1806
    },
    "Old Military (Taylor to 1st Summit)" : {
        "distance" : .9,
        "elevation" : 268
    },
    "Cherry Lane": {
        "distance" : 2.2,
        "elevation" : 480
    },
    "Griffin Lane": {
        "distance" : 2.3,
        "elevation" : 1140
    },
    "Griffin Creek Rd": {
        "distance" : 1.9,
        "elevation" : 440
    },
    "Kane Creek Rd": {
        "distance" : 2.0,
        "elevation" : 587
    },
    "Dead Indian Memorial Rd": {
        "distance" : 11.9,
        "elevation" : 3275
    },
    "Etna Mountain (West)": {
        "distance" : 4.0,
        "elevation" : 2066
    }
};

var vamData = {
    conversions: {
        meters:  .3048,
        miles: 5280,
        km:  1.609344
    },

    init: function() {
        vamData.handleClicks();
        vamData.handleSelect();
        populateSelect.init($('#climbs'),climbData);
    },

    handleClicks: function() {
        $('#submit').click(function(e) {
            e.preventDefault();

            var data = vamData.pageData();
            vamData.displayData(data);
        });
    },

    handleSelect: function() {
        $('#climbs').live('change', function() {
            var val = $(this).val();

            if (val) {
                vamData.repopulateInputs(climbData[val]);
            }
        });
    },

    repopulateInputs: function(data) {
        $('#distance').val(data.distance);
        $('#elevation').val(data.elevation);

        $('#submit').click();
    },

    pageData: function() {
        return {
            distance: parseFloat($('#distance').val()),
            elevation: parseFloat($('#elevation').val()),
            vam: parseFloat($('#vam').val())
        }
    },

    displayData: function(data) {
        var newData = vamData.newData(data);

        $('#timeDisplay').text(vamData.hourConversion(newData.hours).join(':'));
        $('#speedDisplay').text(newData.speed.toFixed(2) + 'mph');
        $('#gradeDisplay').text(newData.grade.toFixed(2) + '%');
    },

    newData: function(data) {
        var newData = {};

        newData.hours = data.elevation / (data.vam / vamData.conversions.meters);
        newData.speed = data.distance / newData.hours;
        newData.grade = data.elevation / (data.distance * vamData.conversions.miles) * 100;

        return newData;
    },

    hourConversion: function(hours) {
        var time = [];

        time[0] = parseInt(hours);
        time[1] = parseInt((hours - time[0]) * 60);
        time[2] = parseInt((((hours - time[0]) * 60) - time[1]) * 60);

        return vamData.timeStrings(time);
    },

    timeStrings: function(arr) {
        for (i=0;i<arr.length;i++) {
            var num = arr[i].toString();
            num.length == 1 ? arr[i] = '0' + num : '';
        }

        return arr;
    }
};

var populateSelect = {
    init: function(el, obj) {

        el.append(populateSelect.buildOptions(obj));
    },

    buildOptions: function(data) {
        var keys = populateSelect.keys(data),
            options = [];

        for (i=0;i<keys.length;i++) {
            options.push('<option value="'+keys[i]+'">'+keys[i]+'</option>')
        }
        return options.join('');
    },

    keys: function(obj,exclude) {
        return $.map(obj, function(value, key) {
            if (key != exclude) {
                return key;
            }
        });
    }
};

vamData.init();
</script>