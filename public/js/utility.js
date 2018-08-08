//Author: Adam Kocić [Falkan3]
/*
   dependency_js: mainscript
*/

var Global_vars_utility = {

};

var G_Utility_Controller = (function () {
    var Local_vars = {
        'elements': {

        }
    };

    return {
        initElementsPrim: function () {

        },
        initElements: function () {

        },
        /**
         * @return {string}
         */
        ColorHexToRGB: function(hex_color) {
            var R = parseInt(hex_color.substring(1, 3), 16);
            var G = parseInt(hex_color.substring(3, 5), 16);
            var B = parseInt(hex_color.substring(5, 7), 16);

            R = (R < 255) ? R : 255;
            G = (G < 255) ? G : 255;
            B = (B < 255) ? B : 255;

            return R + G + B;
        },
        /**
         * @return {string}
         */
        ShadeColor: function (hex_color, percent) {
            var R = parseInt(hex_color.substring(1, 3), 16);
            var G = parseInt(hex_color.substring(3, 5), 16);
            var B = parseInt(hex_color.substring(5, 7), 16);

            R = parseInt(R * (100 + percent) / 100);
            G = parseInt(G * (100 + percent) / 100);
            B = parseInt(B * (100 + percent) / 100);

            R = (R < 255) ? R : 255;
            G = (G < 255) ? G : 255;
            B = (B < 255) ? B : 255;

            var RR = ((R.toString(16).length === 1) ? "0" + R.toString(16) : R.toString(16));
            var GG = ((G.toString(16).length === 1) ? "0" + G.toString(16) : G.toString(16));
            var BB = ((B.toString(16).length === 1) ? "0" + B.toString(16) : B.toString(16));

            return "#" + RR + GG + BB;
        },
        /*
        var colors = {
            green: {from: {r: 232, g: 255, b: 233}, to: {r: 200, g: 255, b: 202}},
            red: {from: {r: 255, g: 239, b: 241}, to: {r: 255, g: 192, b: 200}},
            grey: {from: {r: 221, g: 221, b: 221}, to: {r: 170, g: 170, b: 170}},
        };
        style_color = CalculateColorValue(colors.green, Math.abs((raw_value / 100)));
        element.css('background', 'rgb(' + style_color + ')');
        */
        CalculateColorValue: function (color_json, percentage) {
            var color;
            color = Math.floor(color_json.from.r + (color_json.to.r - color_json.from.r) * percentage) + ',' +
                Math.floor(color_json.from.g + (color_json.to.g - color_json.from.g) * percentage) + ',' +
                Math.floor(color_json.from.b + (color_json.to.b - color_json.from.b) * percentage);

            return color;
        },
    };
})();

/*
G_Main_Controller.initElementsPrim();

$(document).ready(function (e) {
    G_Main_Controller.initElements();
});

Global_vars_lapp_app.window.scroll(function () {

});

Global_vars_lapp_app.window.on('resize', function () {

});

Global_vars_lapp_app.window.on("load", function () {

});
*/

/* ----------------------------  Misc functions ----------------------------  */