(function ($) {
    var settings = {
        activeClass: 'active',
        activeValueClass: 'activeValue',
        highlightedClass: 'highlighted'
    };

    $.fn.fauxSelect = function (options) {
        var selects = $(this),
            options = $.extend({}, settings, options),
            document = $(document);

        init();

        function init() {
            selects.each(function() {
                var select = $(this);

                attachEventListeners(select);
            });

            bodyClick();
        }

        function attachEventListeners(select) {
            toggleDropdown(select);
            makeSelection(select);
        }

        function toggleDropdown(el) {
            var value = el.find('.value');

            value.on('focus', function() {
                closeAllDropdowns();

                el.addClass(options.activeClass);
                arrowHandler(el);

                setTimeout(function() {
                    value.addClass(options.activeValueClass);
                },250);
            });

            el.delegate('.' + options.activeValueClass, 'click', function() {
                closeAllDropdowns();
                value.blur();
            });
        }

        function makeSelection(el) {
            var selections = el.find('.dropdown a');

            selections.on('click', function() {
                var root = $(this);

                selectionMade('click',root,el);
            });
        }

        function selectionMade(type,root,el) {

            var text = root.text(),
                val = root.attr('rel'),
                hiddenInput = getHiddenInput(el),
                valueEl = el.find('.value'),
                form = el.parents('form'),
                eventTarget = form.length ? form : el;

            valueEl.text(text);
            hiddenInput.val(val);
            eventTarget.trigger({
                'type': 'fauxSelectChange',
                'selectedValue': val
            });

            if (type == 'click') {
                closeAllDropdowns();
            }
        }

        function arrowHandler(el) {
            var menuItems = el.find('.dropdown a'),
                directions = {
                    40: 'up',
                    38: 'down'
                },
                index = 0,
                initial = true;

            el.off('keydown','**');
            el.on('keydown', function(e) {
                var direction = directions[e.keyCode],
                    tab = e.keyCode == 9,
                    enter = e.keyCode == 13;

                if (direction) {
                    index = setIndex(index,menuItems,direction,initial);
                    initial = false;
                    highlightItem(el,menuItems.eq(index));
                }

                if (tab || enter) {
                    closeAllDropdowns();
                }

                return !direction;
            });
        }

        function setIndex(index,menuItems,direction,initial) {
            var len = menuItems.length - 1;

            if (direction == 'up' && !initial) {
                index = index < len ? index + 1 : 0;
            } else if (direction == 'down') {
                index = index > 0 ? index - 1 : len;
            }

            return index;
        }

        function highlightItem(el,selected) {
            el.find('.' + options.highlightedClass).removeClass(options.highlightedClass);
            selected.addClass(options.highlightedClass);

            selectionMade('key',selected,el);
        }

        function getHiddenInput(el) {
            var id = el.attr('id').replace('_select', '');

            return $('#' + id);
        }

        function closeAllDropdowns() {
            selects.removeClass(options.activeClass);
            selects.find('.' + options.highlightedClass).removeClass(options.highlightedClass);
            selects.find('.' + options.activeValueClass).removeClass(options.activeValueClass);
        }

        function bodyClick() {
            $('body').on('click', function(e) {
                var clicked = $(e.target),
                    isFauxSelect = false;

                $.each(selects, function() {
                    var select = $(this);

                    if (clicked.parents().is(select)) {
                        isFauxSelect = true;
                    }
                });

                if (!isFauxSelect) {
                    closeAllDropdowns();
                }
            });
        }
    };
})(jQuery);
