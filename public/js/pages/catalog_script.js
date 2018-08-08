//Author: Adam Kocić [Falkan3]

var Global_catalog_vars = {
    'elements': {

    }
};

var G_Catalog_Controller = (function () {
    var g_catalog_vars = {
        'elements': {
            searchicon : null
        }
    };

    return {
        initElementsPrio: function () {

        },
        initElements: function () {
            g_catalog_vars.elements.searchicon = $(".search-icon a");
            g_catalog_vars.elements.searchicon.on('click', function() {
                $(this).closest('form').submit();
            });
        },
        
        /* ---- fncs ---- */
        xxx: function () {

        }
    };
})();

$(document).ready(function (e) {
    $('.carousel').slick({
        lazyLoad: 'ondemand',
        autoplay: true,
        autoplaySpeed: 5000,
        dots: true,
        arrows: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        cssEase: 'linear',
        pauseOnHover: false
    });

    G_Catalog_Controller.initElements();
});

$(window).scroll(function () {

});

$(window).on('resize', function () {

});

$(window).on("load", function () {

});

/* ----------------------------  Misc functions ----------------------------  */