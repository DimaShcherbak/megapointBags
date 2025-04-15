define(['jquery'], function($) {
    'use strict';

    var customScript = {
        init: function() {
            $(document).ready(function() {
                setTimeout(function() {
                    $("div.fadeIn.animated.message-success.success.message").hide();
                }, 2500);
            });
        }
    };

    return customScript;
});
