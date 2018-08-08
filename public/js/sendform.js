var Global_vars_sendform = {
    images_fetch_refresh_form: null,
    images_fetch_productitem_form: null,
    ajax_processing: false
};

$(document).ready(function () {
    Global_vars_sendform.ajax_processing = false;

    var form = $("form.contact-form");
    var loading_gif = $(".loading_ajax");

    $(document)
        .ajaxStart(function () {
            loading_gif.removeClass("hidden");
        })
        .ajaxStop(function () {
            loading_gif.addClass("hidden");
        });

    form.submit(function (e) {
        e.preventDefault();

        var current_form = $(this);
        //Check if inputs are valid
        setValidateFields(current_form);
        var response = validateFields();
        if (response[0] === false) {
            var data_ajax = current_form.attr('data-ajax');
            if(data_ajax) {
                if(data_ajax==='true')
                    sendAjax(current_form);
                else if(data_ajax==='notify')
                    sendAjax_notification(current_form);
                else
                    sendPost(current_form);
            }
            else
                sendPost(current_form);
        }
        else {
            if (response[1].length) {
                $(response[1]).each(function (e) {
                    $(this).addClass('wrong-input');
                });

                //sort array by position in DOM
                var test = response[1].sort(function(a,b) {
                    if( a[0] === b[0]) return 0;
                    if( !a[0].compareDocumentPosition) {
                        // support for IE8 and below
                        return a[0].sourceIndex - b.sourceIndex;
                    }
                    if( a[0].compareDocumentPosition(b[0]) & 2) {
                        // b comes before a
                        return 1;
                    }
                    return -1;
                });

                var offsetTop = $(response[1][0]).offset().top - 120;
                if (offsetTop <= 0) {
                    offsetTop = showHiddenElement($(response[1][0]));
                }
                Global_vars_lapp_app.body_html.animate({
                    scrollTop: offsetTop
                }, 600);
            }
        }
    });
});

function showHiddenElement(el) {
    var offsetTop;
    var isInputHidden = el.is('input[type="hidden"]');
    el.show();
    if (isInputHidden) {
        el.attr('type', 'text');
    }
    offsetTop = el.offset().top - 120;
    el.hide();
    if (isInputHidden) {
        el.attr('type', 'hidden');
    }
    return offsetTop;
}

function sendAjax(form) {
    var current_form = form;
    var formdata = current_form.serialize();
    var status_text = '';
    //var formThankYou = current_form.closest('.container').find('.form-thank-you');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With': 'XMLHttpRequest'
        }
    });
    if(!Global_vars_sendform.ajax_processing) {
        Global_vars_sendform.ajax_processing = true;
        $.ajax({
            url: current_form.attr("action"),//form.attr('action'),
            type: current_form.attr("method"),
            data: formdata,
            enctype: 'multipart/form-data',
            dataType: 'json',
            processData: false,
            success: function (data) {
                if (data.success) {
                    for (var index in data.message) {
                        status_text += data.message[index];
                    }
                    current_form.find("input[type='text'], input[type='tel'], input[type='numeric'], textarea").val("");
                    current_form.find("select").val(null);
                    current_form.find("input[type='checkbox']").prop('checked', 'checked');
                    //current_form.slideUp();
                    //formThankYou.html('<p>Dziękujemy za kontakt.</p>');
                    //formThankYou.slideDown();

                    /*IMAGES*/
                    if (current_form.attr('data-ajax-id') === 'image') {
                        if(Global_vars_sendform.images_fetch_refresh_form === undefined || Global_vars_sendform.images_fetch_refresh_form === null)
                            Global_vars_sendform.images_fetch_refresh_form = $('#images_fetch_refresh_form');

                        var container = current_form.parent().parent();
                        container.fadeOut(500, function () {
                            container.remove();
                            Global_vars_sendform.images_fetch_refresh_form.submit();
                        });
                    } else if (current_form.attr('data-ajax-id') === 'fetch_image_page') {
                        var container = current_form.parent().closest('.toggleable-content');
                        var elems = data.mdata.images;
                        var elems_decoded = '';
                        $(elems).each(function(){
                            elems_decoded += this;
                        });
                        container.children('.ajax-item').remove();
                        $(elems_decoded).hide().appendTo(container).fadeIn();
                        $('#imgpagemin').html(data.mdata.offset);
                        $('#imgpagemax').html(data.mdata.offset + data.mdata.count_images);
                        var forms = $('form[data-ajax-id="fetch_image_page"]');
                        forms.find('input[name="offset"]').val(data.mdata.offset);
                        forms.find('input[name="limit"]').val(data.mdata.limit);
                    }

                    /*PRODUCT ITEMS*/
                    else if (current_form.attr('data-ajax-id') === 'images_fetch_productitem_form') {
                        if(Global_vars_sendform.images_fetch_productitem_form === undefined || Global_vars_sendform.images_fetch_productitem_form === null)
                            Global_vars_sendform.images_fetch_productitem_form = $('#images_fetch_productitem_form');

                        var container = current_form.parent().parent();
                        container.fadeOut(500, function () {
                            container.remove();
                            Global_vars_sendform.images_fetch_productitem_form.submit();
                        });
                    } else if (current_form.attr('data-ajax-id') === 'fetch_imageselector_page') {
                        var container = current_form.parent().next('.row');
                        var elems = data.mdata.images;
                        var elems_decoded = '';
                        $(elems).each(function(){
                            elems_decoded += this;
                        });
                        container.children('.ajax-item').remove();
                        $(elems_decoded).hide().appendTo(container).fadeIn();
                        $('#imgpagemin').html(data.mdata.offset);
                        $('#imgpagemax').html(data.mdata.offset + data.mdata.count_images);
                        var forms = $('form[data-ajax-id="fetch_imageselector_page"]');
                        forms.find('input[name="offset"]').val(data.mdata.offset);
                        forms.find('input[name="limit"]').val(data.mdata.limit);

                        G_Form_Controller.initButtons();
                        G_Form_Controller.clickDefaultButton();
                        $(document).on("click", ".ajax-item button", function() {
                            G_Form_Controller.ButtonClick($(this));
                        });
                    } else if (current_form.attr('data-ajax-id') === 'fetch_productitem_page') {

                    } else if (current_form.attr('data-ajax-id') === 'fetch_product_list_catalog') {

                    }
                    $.notify(
                        status_text,
                        {position: "bottom", className: "success"}
                    );
                }
                else {
                    for (var index in data.message) {
                        status_text += data.message[index];
                    }
                    current_form.notify(
                        status_text,
                        {position: "bottom", className: "error"}
                    );
                }

                //status_text.css("display", "block");
                Global_vars_sendform.ajax_processing = false;
            },
            error: function (data) {
                // Error...
                for (var index in data.message) {
                    status_text += "<p>" + data.message[index] + '</p>';
                }
                current_form.notify(
                    status_text,
                    {position: "bottom", className: "error"}
                );

                Global_vars_sendform.ajax_processing = false;
            }
        });
    }
}

function sendPost(form) {
    form[0].submit();
}