//Author: Adam Kocić [Falkan3]
//Flippers are equal width/height cards that turn on mouseover, revealing their backside. Requires css.

var FlipperGlobalVars = {
    'elements' : {
        'flippers' : []
    }
};

var Flippers_controller = (function () {
    var config = {
        'elements': {
        }
    };

    return {
        initElements: function () {
        },
        resize_flippers: function () {
            if (FlipperGlobalVars.elements.flippers) {
                var mod = 1;
                /*
                 if (G_Main_Controller.Viewport().width < 992) {
                 modifier = 1;
                 }
                 */
                $(FlipperGlobalVars.elements.flippers).each(function (e) {
                    $(this).height($(this).width() * mod);
                });
            }
        },
    };
})();

(function($) {

    $.fn.flipper = function( options ) {

        // Establish our default settings
        var settings = $.extend({
            caller: this,
            container: false,
            flippers: null,
            mod: 1,
            complete: null
        }, options);

        return this.each( function() {
            if (settings.container === true) {
                settings.flippers = settings.caller.find('.flip-container');
            }
            else {
                settings.flippers = settings.caller;
            }

            if (settings.flippers) {
                settings.flippers.hover(function (e) {
                    $(this).addClass('hover');
                });
                settings.flippers.mouseleave(function (e) {
                    $(this).delay(400).queue(function() {
                        $(this).removeClass('hover').dequeue();
                    });
                });

                FlipperGlobalVars.elements.flippers.push(settings.flippers);
                settings.flippers.height($(this).width() * settings.mod);
            }

            if ($.isFunction(settings.complete)) {
                settings.complete.call(this);
            }

            Flippers_controller.resize_flippers();
        });
    }

}(jQuery));

/* -------------------------------------------------------------------------------- */

$(document).ready(function (e) {
    Flippers_controller.initElements();
    $('.flipper-main-container').flipper({'container': true});
    Flippers_controller.resize_flippers();
});

$(window).on('resize', function (e) {
    Flippers_controller.resize_flippers();
});

//