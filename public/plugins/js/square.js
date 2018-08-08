//Author: Adam Kocić [Falkan3]
//Make objects square in size

var SquaresGlobalVars = {
    'elements' : {
        'squares' : []
    }
};

var Squares_controller = (function () {
    var config = {
        'elements': {
            'squares' : []
        }
    };

    return {
        initElements: function () {
        },

        resize_squares: function () {
            if (SquaresGlobalVars.elements.squares.length) {
                $(SquaresGlobalVars.elements.squares).each(function (e) {
                    $(this).height($(this).width());
                });
            }
        },
    };
})();

(function($) {

    $.fn.square = function( options ) {

        // Establish our default settings
        var settings = $.extend({
            caller: this,
            container: null,
            squares: null
        }, options);

        return this.each( function() {
            if (settings.container === true) {
                settings.squares = settings.caller.find('div');
            }
            else {
                settings.squares = settings.caller;
            }

            if (settings.squares) {
                SquaresGlobalVars.elements.squares.push(settings.squares);
                settings.squares.height($(this).width());
            }

            if ($.isFunction(settings.complete)) {
                settings.complete.call(this);
            }
        });
    }

}(jQuery));

/* -------------------------------------------------------------------------------- */

$(window).on('resize', function (e) {
    if (SquaresGlobalVars.elements.squares.length) {
        Squares_controller.resize_squares();
    }
});

//