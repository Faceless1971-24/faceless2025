/**
 * jQuery ScrollLock Plugin
 * 
 * Simple jQuery plugin to lock/unlock scroll
 */
; (function ($) {
    'use strict';

    var scrollLocked = false;
    var scrollPosition = 0;

    // Lock scrolling
    $.scrollLock = function (lock) {
        if (arguments.length === 0) {
            lock = !scrollLocked;
        }

        if (lock !== scrollLocked) {
            if (lock) {
                // Lock scrolling
                scrollPosition = $('html').scrollTop() || $('body').scrollTop();
                $('html, body').css({
                    overflow: 'hidden',
                    height: '100%'
                });
                $('html, body').scrollTop(scrollPosition);
                scrollLocked = true;
            } else {
                // Unlock scrolling
                $('html, body').css({
                    overflow: '',
                    height: ''
                });
                $('html, body').scrollTop(scrollPosition);
                scrollLocked = false;
            }
        }

        return scrollLocked;
    };
})(jQuery);