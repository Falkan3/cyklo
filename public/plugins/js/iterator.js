//Author: Adam Kocić [Falkan3]
//Iterator loops through items and applies active class to the currently selected element

var IteratorGlobalVars = {
    'elements' : [],
    'timer' : null
};

var Iterators_controller = (function () {
    var config = {
        'iterators' : []
    };

    return {
        initElements: function () {
        },
        rotateIterators: function () {
            var i;
            for(i=0; i<IteratorGlobalVars.elements.length; i++) {
                $(IteratorGlobalVars.elements[i]['items']).each(function(){
                    $(this).removeClass('hover');
                });
                $(IteratorGlobalVars.elements[i]['items']).eq(IteratorGlobalVars.elements[i]['activeItemIndex']).addClass('hover');
                IteratorGlobalVars.elements[i]['activeItemIndex']++;
                if (IteratorGlobalVars.elements[i]['activeItemIndex'] > IteratorGlobalVars.elements[i]['maxItemIndex'])
                    IteratorGlobalVars.elements[i]['activeItemIndex'] = 0;
            }
        }
    };
})();

(function($) {

    $.fn.iterator = function( options ) {

        // Establish our default settings
        var settings = $.extend({
            container: this,
            active: false,
            initiator: this.offset().top - this.outerHeight() * 0.8,
            items: $(this).find('.iterator-item'),
            timer: null,
            activeItemIndex: null,
            maxItemIndex: null,
            complete : null
        }, options);

        return this.each( function() {
            if(!settings.prefix)
                settings.prefix = settings.container.index();
            if(!settings.activeItemIndex)
                settings.activeItemIndex = 0;

            if (settings.items) {
                settings.maxItemIndex = settings.items.length - 1;
                var x = 0;
                settings.items.each(function() {
                    $(this).attr('data-container-index', IteratorGlobalVars.elements.length);
                    $(this).attr('data-' + IteratorGlobalVars.elements.length + '-box-index', x++);
                });

                var temp = {
                    'container' : settings.container,
                    'items' : settings.items,
                    'activeItemIndex' : settings.activeItemIndex,
                    'maxItemIndex' : settings.maxItemIndex
                };
                IteratorGlobalVars.elements.push(temp);
                if(IteratorGlobalVars.timer)
                    IteratorGlobalVars.timer.stop();
                IteratorGlobalVars.timer = new G_Main_Controller.Interval(Iterators_controller.rotateIterators, 3000);
                IteratorGlobalVars.timer.start();

                settings.items.hover(function (e) {
                    IteratorGlobalVars.elements[$(this).attr('data-container-index')].items.removeClass('hover');
                    $(this).addClass('hover');
                    IteratorGlobalVars.elements[$(this).attr('data-container-index')].activeItemIndex = $(this).attr('data-' + $(this).attr('data-container-index') + '-box-index');
                    IteratorGlobalVars.timer.stop();
                });
                settings.items.mouseleave(function (e) {
                    IteratorGlobalVars.timer.start();
                });
            }

            if ($.isFunction(settings.complete)) {
                settings.complete.call(this);
            }
        });
    }

}(jQuery));

//

/*
$('.iterator-container-1').iterator({

});
$('.iterator-container-2').iterator({

});
*/

$(window).on("load", function () {
    Iterators_controller.rotateIterators();
});

$(window).scroll(function () {
    /*
    if (this.vars.active == false && $(window).scrollTop() > this.vars.initiator) {
        this.vars.active = true;
        this.initElements();
    }
    */
});