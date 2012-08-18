function in_array(e, c, a) {
    key = "";
    if (a == true) {
        for (key in c) {
            var d = c[key].split("|")[0],
                b = c[key].split("|")[1];
            if (d == e) {
                return b
            }
        }
    } else {
        for (key in c) {
            if (c[key] == e) {
                return true
            }
        }
    }
    return false
}

function arrayReplace(d, b, c) {
    for (var a = 0; a < b.length; a++) {
        d = d.replace(b[a], c[a])
    }
    return d
}

(function (b) {
    var a = {
        keys: null,
        filterKeys: null,
        entryType: null,
        comboKeys: "67,86, 88, 65",
        callback: function () {}
    };
    b.fn.preventKeyCode = function (c) {
        c = b.extend({}, a, c);
        var d = b(this);
        d.live("keydown", function (l) {
            if (c.entryType === "numeric") {
                c.keys = "37, 39, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 8, 9, 46, 96, 97, 98, 99, 100, 101, 102, 103, 104, 105"
            }
            var k = c.keys.split(","),
                g = c.comboKeys.split(","),
                j = l.keyCode,
                m = l.ctrlKey && in_array(j, g),
                f = l.metaKey && in_array(j, g),
                h = l.shiftKey && in_array(j, k);
            if (m === true || f === true) {
                return true
            } else {
                if (c.entryType == "mixed") {
                    filterChars = c.filterKeys.split(",");
                    if (h === true) {
                        return false
                    }
                    if (in_array(j, k) == true && in_array(j, filterChars) != true) {} else {
                        if (in_array(j, filterChars) == true) {
                            l.preventDefault();
                            c.callback(j, l)
                        } else {
                            return false
                        }
                    }
                } else {
                    if (c.entryType == "numeric") {
                        if (in_array(j, k) !== true || h === true) {
                            return false;
                            c.callback(j, l)
                        }
                    } else {
                        if (in_array(j, k) == true && c.entryType != "limited") {
                            l.preventDefault();
                            c.callback(j, l)
                        } else {
                            if (in_array(j, k) !== true && c.entryType == "limited") {
                                return false;
                                c.callback(j, l)
                            }
                        }
                    }
                }
            }
        })
    }
})(jQuery);