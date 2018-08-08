//Flippers are cards that turn on mouseover
var ToggleableGlobalVars = {
    'elements' : {
        'containers' : []
    }
};

var Toggleables_controller = (function () {
    var config = {
        'elements': {
            'containers' : []
        }
    };

    return {
        initElements: function () {
            ToggleableGlobalVars.elements.containers = $('.toggleable');
            ToggleableGlobalVars.elements.containers.toggleable();
        },
    };
})();

(function($) {

    $.fn.toggleable = function( options ) {

        // Establish our default settings
        var settings = $.extend({
            caller: this,
            closed: false,
            complete: null
        }, options);

        return this.each( function() {
            if (settings.caller !== null) {
                //$(settings.caller).find('a.toggleable-slideup').on('click', function() {
                $(settings.caller).find('.toggleable-settings').on('click', function() {
                    $(settings.caller).find('.toggleable-content').slideToggle();
                });
            }
            if(settings.closed === true || settings.caller.hasClass('closed')) {
                $(settings.caller).find('.toggleable-content').hide();
            }

            if ($.isFunction(settings.complete)) {
                settings.complete.call(this);
            }
        });
    }

}(jQuery));

/* -------------------------------------------------------------------------------- */

$(document).ready(function (e) {
    Toggleables_controller.initElements();
});

//