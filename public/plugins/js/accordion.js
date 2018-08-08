//Author: Adam Kocić [Falkan3]
//Toggle height of item on click

(function($) {

    $.fn.accordion = function( options ) {

        // Establish our default settings
        var settings = $.extend({
            caller: this,
            parents: [],
            children: [],
            complete: null
        }, options);

        return this.each( function() {
            var temp_parents = settings.caller.find('div.accordion-item');
            //AccordionGlobalVars.elements.parents.push($(settings.caller));
            temp_parents.each(function() {
                var $this = $(this);
                settings.parents.push($this);
                settings.children.push($this.find('div.accordion-item-content'));
            });

            $(settings.parents).each(function(e) {
                var $this = $(this);
                var index = $this.data('index') ? $this.data('index') : $this.index();

                $this.removeClass('active');
                settings.children[index].hide();

                $this.find('a').first().on('click', function(e) {
                    for(var i=0; i<settings.children.length; i++) {
                        if(i!==index) {
                            settings.parents[i].removeClass('active');
                            settings.children[i].slideUp().removeClass('active');
                        }
                    }

                    settings.parents[index].toggleClass('active');
                    settings.children[index].slideToggle();
                });
            });

            //AccordionGlobalVars.el.parents.push({'element' : $(settings.caller), 'index' : AccordionGlobalVars.index});

            if ($.isFunction(settings.complete)) {
                settings.complete.call(this);
            }
        });
    }

}(jQuery));

/* -------------------------------------------------------------------------------- */

//

$(function() {
    $('div.accordion-container').accordion();
});