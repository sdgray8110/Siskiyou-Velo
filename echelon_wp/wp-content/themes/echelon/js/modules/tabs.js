/*!
 * Generic tabs plugin
 *
 * COMMENTS:
 * -- Works with AJAX and non-AJAX tabs.
 *
 * REQUISITES:
 * -- jQuery 1.4.4
 * -- hashWatcher.js -- history object maintenance with AJAX events
 * -- ajaxTransition.js - loading gif and transition animations for tabs/ajax
 *
 * Authored by Spencer Gray 
 */

(function ($) {
    var settings = {
        active: 'active',
        method : 'ajax',
        contentContainer : '#mainContent > div',
        initialTab : '',
        currentTab : '',
        ajaxAppend : '.ajax',
        ajaxCallback : function() {}
    };

    $.fn.tabs = function (options) {
        root = $(this),
        tabs = root.find('a'),
        options = $.extend({}, settings, options),
        container = $(options.contentContainer);

        setCurrentTab();
        tabClick();

        if (options.method == 'ajax') {
            $.ajaxTransition.createLoadingGif(container);
        }

         $(window).bind('hashChange',function(){
            var tab = cleanTabName(window.location.hash),
                initTab = cleanTabName(options.initialTab);

            if (tab && tab != options.currentTab) {
                $('#' + tab).click();
            } else if (!tab) {
                $('#' + initTab).click();
            }
         });

        function tabClick() {
            tabs.click(function(e) {
                e.preventDefault();
                
                var tab = $(this);

                if (!tab.hasClass(options.active)) {
                    if (options.method === 'ajax') {
                        var url = tab.attr('href');
                        UrlHashWatcher.ajaxActive=1;

                        loadTab(url);
                    }
                }

                setSelected(tab);
            });
        }

        function setSelected(tab) {
            root.find('.' + options.active).removeClass(options.active);
            tab.addClass(options.active);

            setCurrentTab(tabName(tab.attr('href')));
        }

        function loadTab(url) {
            container.ajaxTransition({
                callback: function() {
                    tabAjax(url);
                }
            });
        }

        function tabAjax(url) {
            $.ajax({
                type: 'get',
                url : url,
                success: function(data) {
                    container.html(data);
                    options.ajaxCallback();
                    $.ajaxTransition.animateIn(container);
                },
                complete: function() {
                    var tab = tabName(url);
                    UrlHashWatcher.ajaxActive=0;

                    if (tab != options.initialTab) {
                        UrlHashWatcher.setNewPageHash(tab);
                    }
                }
            });
        }

        function tabName(url) {
            var name = global.getQueryStringParam('template',url);

            return name ? name + options.ajaxAppend : '';
        }

        function cleanTabName(hash) {
            if (hash) {
                hash = hash.replace('#', '').replace(options.ajaxAppend, '');
            }
            
            return hash;
        }

        function setCurrentTab(tab) {
            if (!tab) {
                tab = tabName(root.find('.' + options.active).attr('href'));
                options.initialTab = tab;
            }

            options.currentTab = tab;
        }
    };
})(jQuery);