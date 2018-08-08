//Author: Adam Kocić [Falkan3]

var G_Form_Controller = (function () {
    var Global_vars_form = {
        'elements': {
            'buttons' : [],
            'data_containers' : [],
            'readmore_click' : [],
            'readmore' : [],
            'image_selector' : null
        }
    };

    return {
        initElements: function () {
            //buttons
            G_Form_Controller.initButtons();
            //data-containers
            G_Form_Controller.initDataContainers();
            //readmore
            Global_vars_form.elements.readmore_click = $('a.read-more-click');
            Global_vars_form.elements.readmore = $('div.read-more');
            G_Form_Controller.ReadMoreInit();
            //
        },
        //data container
        initDataContainers: function() {
            Global_vars_form.elements.data_containers = $('input[data-container="true"]');
            G_Form_Controller.clickDefaultButton();
        },
        //data container click init
        clickDefaultButton: function() {
            Global_vars_form.elements.data_containers.each(function () {
                var $this = $(this);
                //check if value is set, and click corresponding buttons accordingly
                if ($this.val() !== undefined && $this.val() !== null) {
                    var btnToClick;
                    if ($this.attr('data-multiselect') === 'true') {
                        var value = $this.val().split(',');
                        for (var i = 0; i < value.length; i++) {
                            btnToClick = $.grep(Global_vars_form.elements.buttons, function (e) {
                                var $e = $(e);
                                return ($e.attr('data-index') === $this.attr('data-index')) && ($e.attr('data-val') === value[i]);
                            });
                            G_Form_Controller.ButtonClick($(btnToClick), false);
                        }
                    } else {
                        btnToClick = $.grep(Global_vars_form.elements.buttons, function (e) {
                            var $e = $(e);
                            return ($e.attr('data-index') === $this.attr('data-index')) && ($e.attr('data-val') === $this.val());
                        });
                        G_Form_Controller.ButtonClick($(btnToClick), false);
                    }
                }
            });
        },
        //buttons
        initButtons: function() {
            Global_vars_form.elements.buttons = $('button[type="button"][data-index]');
            Global_vars_form.elements.buttons.on('click', function () {
                G_Form_Controller.ButtonClick($(this));
            });
        },
        ButtonClick: function (btn, change_val) {
            if (change_val === undefined)
                change_val = true;

            var searchindex = btn.attr('data-index');
            var btnval = btn.attr('data-val');
            Global_vars_form.elements.data_containers.each(function () {
                var $this = $(this);
                if ($this.attr('data-index') === searchindex) {
                    if ($this.attr('data-multiselect') === 'true') {
                        if (change_val === true) {
                            var value = $this.val().split(',');
                            var is_found = false;
                            for (var i = 0; i < value.length; i++) {
                                if (value[i] === btnval) {
                                    value.splice(i, 1);
                                    is_found = true;
                                    break;
                                }
                            }
                            if (is_found === false) {
                                value.push(btnval);
                            }
                            var new_val = value.filter(function (el) {
                                return el.length !== 0
                            });
                            $this.val(new_val);
                        }
                        btn.toggleClass('active');
                    } else {
                        if (change_val === true) {
                            $this.val(btnval);
                        }
                        //Deactivate all buttons belonging to this data container
                        var btnToDeactivate = $.grep(Global_vars_form.elements.buttons, function (e) {
                            return $(e).attr('data-index') == searchindex;
                        });
                        $(btnToDeactivate).removeClass('active');
                        btn.addClass('active');
                    }
                    $this.removeClass('wrong-input');
                    $this.prev('span.errormsg').remove();
                }
            });
        },
        ReadMoreInit: function () {
            Global_vars_form.elements.readmore_click.on('click', function(e) {
                Global_vars_form.elements.readmore.hide();
                $(this).next('div.read-more').slideToggle();
            });
            Global_vars_form.elements.readmore.on('click', function(e) {
                $(this).hide();
            });
        },
        ImageSelectorInit: function () {
            var img_sel_parent = $('.image-selector');
            Global_vars_form.elements.image_selector = {
                'parent': img_sel_parent,
                'closebtn': img_sel_parent.find('a.close-btn'),
                'showbtn': $('.image-selector-show a'),
                'images': []
            };
            Global_vars_form.elements.image_selector.closebtn.on('click', function() {
                Global_vars_form.elements.image_selector.parent.fadeOut();
            });
            Global_vars_form.elements.image_selector.showbtn.on('click', function() {
                Global_vars_form.elements.image_selector.parent.fadeIn();
            });
        }
    };
})();

//

$(document).ready(function (e) {
    G_Form_Controller.initElements();
});

$(window).on('scroll', function () {

});

$(window).on('resize', function () {

});

$(window).on("load", function () {

});

var validationForms = $('form');
var all_input_text = $('input[type="text"], input[type="tel"]');
var validateForm;
var name_fields;
var email_fields;
var telephone_fields;
var pesel_fields;
var city_fields;
var postcode_fields;
var street_fields;
var streetno_fields;
var number_fields;
var agreement_fields;
var hidden_fields;

var all_telephone_fields = $('input[type="text"].telephone, input[type="tel"].telephone');
var phones_mask = [{"mask": "###-###-###"}, {"mask": "## ###-##-##"}];
all_telephone_fields.inputmask({
    mask: phones_mask,
    greedy: false,
    definitions: {'#': {validator: "[0-9]", cardinality: 1}}
});
var all_pesel_fields = all_input_text.filter('.pesel');
var pesels_mask = [{"mask": "###########"}];
all_pesel_fields.inputmask({
    mask: pesels_mask,
    greedy: false,
    definitions: {'#': {validator: "[0-9]", cardinality: 1}}
});
var all_postcode_fields = all_input_text.filter('.postcode');
var postcodes_mask = [{"mask": "##-###"}];
all_postcode_fields.inputmask({
    mask: postcodes_mask,
    greedy: false,
    definitions: {'#': {validator: "[0-9]", cardinality: 1}}
});

function setValidateFields(input) {
    validateForm = input;
}

function validateFields() {
    if (validateForm.length === 0)
        return false;

    //remove error messages
    validateForm.find('span.errormsg').remove();

    name_fields = validateForm.find('input[type="text"].name');
    email_fields = validateForm.find('input[type="text"].email');
    telephone_fields = validateForm.find('input[type="text"].telephone, input[type="tel"].telephone');
    pesel_fields = validateForm.find('input[type="text"].pesel');
    city_fields = validateForm.find('input[type="text"].city');
    postcode_fields = validateForm.find('input[type="text"].postcode');
    street_fields = validateForm.find('input[type="text"].street');
    streetno_fields = validateForm.find('input[type="text"].streetno');
    number_fields = validateForm.find('input[type="text"].number, input[type="number"].number');
    agreement_fields = validateForm.find('.agreements input[type="checkbox"]');
    hidden_fields = validateForm.find('input[type="hidden"][required], select[required]');

    var error = false;
    var wrong_inputs = [];

    name_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_name($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    email_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_email($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    telephone_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_phone_number($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    agreement_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_agreements($this);
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });

    //optional
    pesel_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_pesel($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    city_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_city($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    postcode_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_postcode($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    street_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_street($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    streetno_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_streetno($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    number_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_number($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });
    hidden_fields.each(function () {
        var $this = $(this);
        var is_valid = validate_hidden($this.val());
        if (is_valid[0] === false) {
            error = true;
            wrong_inputs.push($this);
            $this.before('<span class="errormsg">' + is_valid[1] + '</span>');
        }
    });

    return [error, wrong_inputs];
}

function removeErrorMsg(ele) {
    ele.removeClass('wrong-input');
    ele.prev('span.errormsg').remove();
}
function rightInput(ele) {
    ele.addClass('right-input');
    ele.removeClass('wrong-input');
}
function wrongInput(ele) {
    ele.addClass('wrong-input');
    ele.removeClass('right-input');
}

validationForms.on('click', 'span.errormsg', function() {
    var $this = $(this);
    var closest_input = $this.siblings('input, button, textarea');
    removeErrorMsg(closest_input);
});
var inputs = $('input, textarea, select');
inputs.blur(function () {
    var $this = $(this);
    removeErrorMsg($this);
});
inputs.on('click', function () {
    var $this = $(this);
    removeErrorMsg($this);
});
inputs.filter('input[type="checkbox"]').on('change', function () {
    var $this = $(this);
    removeErrorMsg($this);
});

name_fields = $('input[type="text"].name');
email_fields = $('input[type="text"].email');
//telephone_fields = $('input[type="text"].telephone, input[type="tel"].telephone');
pesel_fields = $('input[type="text"].pesel');
city_fields = $('input[type="text"].city');
postcode_fields = $('input[type="text"].postcode');
street_fields = $('input[type="text"].street');
streetno_fields = $('input[type="text"].streetno');
number_fields = $('input[type="text"].number, input[type="number"].number');
agreement_fields = $('.agreements input[type="checkbox"]');
hidden_fields = $('input[type="hidden"][required], select[required]');

name_fields.blur(function (e) {
    var $this = $(this);
    if (validate_name($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
email_fields.blur(function (e) {
    var $this = $(this);
    if (validate_email($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
all_telephone_fields.blur(function (e) {
    var $this = $(this);
    if (validate_phone_number($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
pesel_fields.blur(function (e) {
    var $this = $(this);
    if (validate_pesel($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
city_fields.blur(function (e) {
    var $this = $(this);
    if (validate_city($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
postcode_fields.blur(function (e) {
    var $this = $(this);
    if (validate_postcode($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
street_fields.blur(function (e) {
    var $this = $(this);
    if (validate_street($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
streetno_fields.blur(function (e) {
    var $this = $(this);
    if (validate_streetno($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
number_fields.blur(function (e) {
    var $this = $(this);
    if (validate_number($this.val())[0] === true) {
        rightInput($this);
    }
    else {
        wrongInput($this);
    }
});
number_fields.on('input', function () {
    if(this.value.length > $(this).attr('maxlength')) {
        this.value = this.value.slice(0,-1);
    }
    this.value = this.value.replace(/[^\d]/g, "");
});
hidden_fields.blur(function (e) {
    var $this = $(this);
    removeErrorMsg($this);
});

function validate_name(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
    }
    else if(input.length > 20) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 20)';
    }

    if(valid !== false) {
        var regex = /^[ĄĆĘŁŃÓŚŹŻąćęłńóśźż\sA-Za-z\'\"&\(\),\.]*$/;
        valid = regex.test(input);

        if(valid === false) {
            msg = 'Pole zawiera niedozwolone znaki.'
        }
    }

    return [valid, msg];
}

function validate_email(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
        return [valid, msg];
    }
    else if(input.length > 20) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 20)';
        return [valid, msg];
    }

    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    valid = regex.test(input);

    if(valid === false) {
        msg = 'Format adresu e-mail jest nieprawidłowy.'
    }

    return [valid, msg];
}

function validate_phone_number(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
        return [valid, msg];
    }
    else if(input.length > 11) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 11)';
        return [valid, msg];
    }

    var regex = /^[0-9]{3}(-|\s)?[0-9]{3}(-|\s)?[0-9]{3}$/;
    var regex2 = /^[0]?([0-9]{2})(-|\s)?[0-9]{3}(-|\s)?[0-9]{2}(-|\s)?[0-9]{2}$/;
    var regex3 = /^[0]{3}(-|\s)?[0]{3}(-|\s)?[0]{3}$/;

    valid = !regex3.test(input) && (regex.test(input) || regex2.test(input));
    if(valid === false) {
        msg = 'Numer telefonu jest nieprawidłowy.';
    }

    return [valid, msg];
}

function validate_agreements(input) {
    var valid = true;
    var msg = '';

    if(!!input.prop("checked") === false) {
        valid = false;
        msg = 'Pole musi być zaznaczone.';
    }

    return [valid, msg];
}

//optional

function validate_pesel(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
        return [valid, msg];
    }
    else if(input.length > 11) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 11)';
        return [valid, msg];
    }

    var regex = /^[0-9]{2}(?!00)[0-9]{2}(?!00)[0-3]{1}[0-9]{1}[0-9]{5}$/;
    valid = regex.test(input);

    if(valid === false) {
        msg = 'Nieprawidłowy format PESEL.';
    }

    return [valid, msg];
}

function validate_city(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
        return [valid, msg];
    }
    else if(input.length > 20) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 20)';
        return [valid, msg];
    }

    var regex = /^[ĄĆĘŁŃÓŚŹŻąćęłńóśźż\sA-Za-z\'\"&\(\),\.]*$/;
    valid = regex.test(input);

    if(valid === false) {
        msg = 'Nazwa miasta zawiera niedozwolone znaki.';
    }

    return [valid, msg];
}

function validate_postcode(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
        return [valid, msg];
    }
    else if(input.length > 20) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 20)';
        return [valid, msg];
    }

    var regex = /^\d{2}-\d{3}$/;
    valid = regex.test(input);

    if(valid === false) {
        msg = 'Nieprawidłowy format kodu pocztowego.';
    }

    return [valid, msg];
}

function validate_street(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
        return [valid, msg];
    }
    else if(input.length > 30) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 30)';
        return [valid, msg];
    }

    var regex = /^[ĄĆĘŁŃÓŚŹŻąćęłńóśźż\s\-0-9A-Za-z_\'\"&\(\),\.]*$/;
    valid = regex.test(input);

    if(valid === false) {
        msg = 'Nazwa ulicy zawiera niedozwolone znaki.';
    }

    return [valid, msg];
}

function validate_streetno(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
        return [valid, msg];
    }
    else if(input.length > 6) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 6)';
        return [valid, msg];
    }

    var regex = /^[\s\\\/\-0-9A-Za-z_&\(\),\.]*$/;
    valid = regex.test(input);

    if(valid === false) {
        msg = 'Numer ulicy zawiera niedozwolone znaki.';
    }

    return [valid, msg];
}

function validate_number(input) {
    var valid = true;
    var msg = '';

    if (input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
        return [valid, msg];
    }
    else if(input.length > 8) {
        valid = false;
        msg = 'Pole jest za długie. (Max: 8)';
        return [valid, msg];
    }

    var regex = /^\d*$/;
    valid = regex.test(input);

    if(valid === false) {
        msg = 'Wartość pola musi być liczbą.';
    }

    return [valid, msg];
}

function validate_hidden(input) {
    var valid = true;
    var msg = '';

    if(input.length === 0) {
        valid = false;
        msg = 'Pole nie może być puste.';
    }

    return [valid, msg];
}

// -----------------------------