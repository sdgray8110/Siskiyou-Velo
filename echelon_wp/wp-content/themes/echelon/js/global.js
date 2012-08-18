/* Global JS */

var global = {
    init: function() {
        global.fadeGallery();
        global.fbFeed();
    },

    fadeGallery: function() {
        var sponsors = $('#sponsors');

        if (sponsors.length) {
            sponsors.fadeGallery({
                interval : 5000
            });
        }
    },

    fbFeed: function() {
        $('#fbFeed').fbFeed({
            userID : 187440724616694
        });
    },

    getQueryStringParam: function(c, b) {
        typeof (b) === 'undefined' ? b = window.location.search : '';
        if (!b) {
            return null
        }
        if (b.indexOf('?') == -1) {
            b = '?' + b
        }
        var a = new RegExp('[\\?&]' + c + '=([^&#]*)').exec(b);
        return (!a || a.length < 2) ? '' : a[1]
    }
};

$(document).ready(function() {
    global.init();
});