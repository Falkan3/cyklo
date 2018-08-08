/*!
 * jquery.drawDoughnutChart.js
 * Version: 0.4.1(Beta)
 * Inspired by Chart.js(http://www.chartjs.org/)
 *
 * Copyright 2014 hiro
 * https://github.com/githiro/drawDoughnutChart
 * Released under the MIT license.
 * 
 */
(function ($, undefined) {
    $.fn.drawDoughnutChart = function (data, options) {
        var $this = this,
            W = $this.width(),
            H = $this.height() ? $this.height() : $this.width(),
            centerX = W / 2,
            centerY = H / 2,
            cos = Math.cos,
            sin = Math.sin,
            PI = Math.PI,
            basePath = null,
            settings = $.extend({
                segmentShowStroke: true,
                segmentStrokeColor: "#0C1013",
                segmentStrokeWidth: 0,//1,
                baseColor: "rgba(0,0,0,0.5)",
                baseOffset: 0,//4,
                edgeOffset: 0,//10,//offset from edge of $this
                percentageInnerCutout: 75,
                animation: true,
                animationSteps: 90,
                animationEasing: "easeInOutExpo",
                animateRotate: true,
                tipOffsetX: -18,//-8,
                tipOffsetY: -60,//-45,
                tipClass: "doughnutTip stylized",
                tipClassFormatted: "",
                summaryClass: "doughnutSummary",
                summaryTitle: "RAZEM:",
                summaryTitleClass: "doughnutSummaryTitle",
                summaryNumberClass: "doughnutSummaryNumber",
                legend: false,
                removeBase: true,
                beforeDraw: function () {
                },
                afterDrawn: function () {
                    if (settings.removeBase) {
                        basePath.fadeOut(300, function () {
                            basePath.remove();
                        });
                    }
                },
                onPathEnter: function (e, data) {
                },
                onPathLeave: function (e, data) {
                }
            }, options),
            animationOptions = {
                linear: function (t) {
                    return t;
                },
                easeInOutExpo: function (t) {
                    var v = t < .5 ? 8 * t * t * t * t : 1 - 8 * (--t) * t * t * t;
                    return (v > 1) ? 1 : v;
                }
            },
            requestAnimFrame = function () {
                return window.requestAnimationFrame ||
                    window.webkitRequestAnimationFrame ||
                    window.mozRequestAnimationFrame ||
                    window.oRequestAnimationFrame ||
                    window.msRequestAnimationFrame ||
                    function (callback) {
                        window.setTimeout(callback, 1000 / 60);
                    };
            }();

        settings.beforeDraw.call($this);

        //format tip class
        settings.tipClassFormatted = formatClasses(settings.tipClass);

        var $svg = $('<svg width="' + W + '" height="' + H + '" viewBox="0 0 ' + W + ' ' + H + '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"></svg>').appendTo($this),
            $paths = [],
            easingFunction = animationOptions[settings.animationEasing],
            doughnutRadius = Min([H / 2, W / 2]) - settings.edgeOffset,
            cutoutRadius = doughnutRadius * (settings.percentageInnerCutout / 100),
            segmentTotal = 0;

        //Draw base doughnut
        var baseDoughnutRadius = doughnutRadius + settings.baseOffset,
            baseCutoutRadius = cutoutRadius - settings.baseOffset;
        basePath = $(document.createElementNS('http://www.w3.org/2000/svg', 'path'))
            .attr({
                "d": getHollowCirclePath(baseDoughnutRadius, baseCutoutRadius),
                "fill": settings.baseColor
            })
            .appendTo($svg);

        //Set up pie segments wrapper
        var $pathGroup = $(document.createElementNS('http://www.w3.org/2000/svg', 'g'));
        $pathGroup.attr({opacity: 0}).appendTo($svg);

        //Set up tooltip
        var $tip = $('<div class="' + settings.tipClass + '" />').appendTo('body').hide(),
            tipW = $tip.width(),
            tipH = $tip.height();

        //Set up center text area
        var summarySize = (cutoutRadius - (doughnutRadius - cutoutRadius)) * 2,
            $summary = $('<div class="' + settings.summaryClass + '" />')
                .appendTo($this)
                .css({
                    width: summarySize + "px",
                    height: summarySize + "px",
                    "margin-left": -(summarySize / 2) + "px",
                    "margin-top": -(summarySize / 2) + "px"
                });
        var $summaryTitle = $('<p class="' + settings.summaryTitleClass + '">' + settings.summaryTitle + '</p>').appendTo($summary);
        var $summaryNumber = $('<p class="' + settings.summaryNumberClass + '"></p>').appendTo($summary).css({opacity: 0});

        for (var i = 0, len = data.length; i < len; i++) {
            //don't count to total number if stated
            if (!data[i].nocount) {
                segmentTotal += data[i].value;
            }
            //only fill with color if applicable (has title
            var fill;
            if (data[i].title && data[i].title.length > 0) {
                data[i].hastitle = true;
                fill = data[i].color;
            } else {
                data[i].hastitle = false;
                fill = "transparent";
            }

            if (data[i].hastitle) {
                //draw path
                $paths[i] = $(document.createElementNS('http://www.w3.org/2000/svg', 'path'))
                    .attr({
                        "stroke-width": settings.segmentStrokeWidth,
                        "stroke": settings.segmentStrokeColor,
                        "fill": fill,
                        "data-order": i
                    })
                    .appendTo($pathGroup)
                    .on("mouseenter", pathMouseEnter)
                    .on("mouseleave", pathMouseLeave)
                    .on("mousemove", pathMouseMove);
            }
        }
        for (var i = 0, len = data.length; i < len; i++) {
            //set percentage
            if(segmentTotal === 0) {
                data[i].percent = '0'.toString();
            } else {
                data[i].percent = ((data[i].value / segmentTotal) * 100).toFixed(1).toString();
            }
        }

        //Animation start
        animationLoop(drawPieSegments);

        //Draw chart legend

        var chart_legend = $this.closest('.cs-chart-container').find('.chart-legend').first();
        if (settings.legend) {
            var chart_legend_body = chart_legend.find('.ct');

            //cleanup
            chart_legend_body.empty();
            chart_legend.show();

            //$this.parent().find('.chart-legend').first().find('.ct');
            // $this.siblings('.chart-legend').first().find('.ct');
            for (var i = 0, len = data.length; i < len; i++) {
                if (data[i].hastitle) {
                    var el = $('<div class="ct-bod"></div>');
                    var sqr = $('<div class="ct-sqr"></div>');
                    sqr.css('background-color', data[i].color);
                    var name = $('<span class="ct-label">' + data[i].title + ": " + data[i].value + " (" + data[i].percent + "%)" + "</span>");

                    //base info
                    el.append(sqr);
                    el.append(name);

                    //val change
                    var valueChange = data[i].valueChange;
                    if (valueChange) {
                        var valueChangeColor = data[i].valueChangeColor;
                        var valueChangeIcon = data[i].valueChangeIcon;

                        el.append(legendValueChange(valueChange, valueChangeColor, valueChangeIcon));
                    }

                    chart_legend_body.append(el);
                }
            }
        } else {
            chart_legend.hide();
        }

        //Functions
        function formatClasses(string) {
            var output = (string.split(' ')).join('.');
            if(output) {
                output = ['.', output].join('');
            }
            return output;
        }

        function getHollowCirclePath(doughnutRadius, cutoutRadius) {
            //Calculate values for the path.
            //We needn't calculate startRadius, segmentAngle and endRadius, because base doughnut doesn't animate.
            var startRadius = -1.570,// -Math.PI/2
                segmentAngle = 6.2831,// 1 * ((99.9999/100) * (PI*2)),
                endRadius = 4.7131,// startRadius + segmentAngle
                startX = centerX + cos(startRadius) * doughnutRadius,
                startY = centerY + sin(startRadius) * doughnutRadius,
                endX2 = centerX + cos(startRadius) * cutoutRadius,
                endY2 = centerY + sin(startRadius) * cutoutRadius,
                endX = centerX + cos(endRadius) * doughnutRadius,
                endY = centerY + sin(endRadius) * doughnutRadius,
                startX2 = centerX + cos(endRadius) * cutoutRadius,
                startY2 = centerY + sin(endRadius) * cutoutRadius;
            var cmd = [
                'M', startX, startY,
                'A', doughnutRadius, doughnutRadius, 0, 1, 1, endX, endY,//Draw outer circle
                'Z',//Close path
                'M', startX2, startY2,//Move pointer
                'A', cutoutRadius, cutoutRadius, 0, 1, 0, endX2, endY2,//Draw inner circle
                'Z'
            ];
            cmd = cmd.join(' ');
            return cmd;
        }

        function pathMouseEnter(e) {
            //hide all other tips
            $(settings.tipClassFormatted).hide();

            var order = $(this).data().order;
            if (data[order].title && data[order].title.length > 0) {
                $tip.text(data[order].title + ": " + data[order].value + " (" + data[order].percent + "%)")
                    .fadeIn(200);
                settings.onPathEnter.apply($(this), [e, data]);
            }
        }

        function pathMouseLeave(e) {
            $tip.hide();
            settings.onPathLeave.apply($(this), [e, data]);
        }

        function pathMouseMove(e) {
            $tip.css({
                top: e.pageY + settings.tipOffsetY,
                left: e.pageX - $tip.width() / 2 + settings.tipOffsetX
            });
        }

        function drawPieSegments(animationDecimal) {
            var startRadius = -PI / 2,//-90 degree
                rotateAnimation = 1;
            if (settings.animation && settings.animateRotate) rotateAnimation = animationDecimal;//count up between0~1

            drawDoughnutText(animationDecimal, segmentTotal);

            $pathGroup.attr("opacity", animationDecimal);

            //If data have only one value, we draw hollow circle(#1).
            if(segmentTotal === 0) {
                if (data.length === 1 && (4.7122 < (startRadius))) {
                    $paths[0].attr("d", getHollowCirclePath(doughnutRadius, cutoutRadius));
                    return;
                }
            } else {
                if (data.length === 1 && (4.7122 < (rotateAnimation * ((data[0].value / segmentTotal) * (PI * 2)) + startRadius))) {
                    $paths[0].attr("d", getHollowCirclePath(doughnutRadius, cutoutRadius));
                    return;
                }
            }

            for (var i = 0, len = data.length; i < len; i++) {
                if (data[i].hastitle) {
                    var segmentAngle = 0;
                    if(segmentTotal > 0) {
                        segmentAngle = rotateAnimation * ((data[i].value / segmentTotal) * (PI * 2))
                    }
                    var endRadius = startRadius + segmentAngle,
                        largeArc = ((endRadius - startRadius) % (PI * 2)) > PI ? 1 : 0,
                        startX = centerX + cos(startRadius) * doughnutRadius,
                        startY = centerY + sin(startRadius) * doughnutRadius,
                        endX2 = centerX + cos(startRadius) * cutoutRadius,
                        endY2 = centerY + sin(startRadius) * cutoutRadius,
                        endX = centerX + cos(endRadius) * doughnutRadius,
                        endY = centerY + sin(endRadius) * doughnutRadius,
                        startX2 = centerX + cos(endRadius) * cutoutRadius,
                        startY2 = centerY + sin(endRadius) * cutoutRadius;
                    var cmd = [
                        'M', startX, startY,//Move pointer
                        'A', doughnutRadius, doughnutRadius, 0, largeArc, 1, endX, endY,//Draw outer arc path
                        'L', startX2, startY2,//Draw line path(this line connects outer and innner arc paths)
                        'A', cutoutRadius, cutoutRadius, 0, largeArc, 0, endX2, endY2,//Draw inner arc path
                        'Z'//Cloth path
                    ];
                    $paths[i].attr("d", cmd.join(' '));
                    startRadius += segmentAngle;
                }
            }
        }

        function drawDoughnutText(animationDecimal, segmentTotal) {
            $summaryNumber
                .css({opacity: animationDecimal})
                .text(Math.round((segmentTotal * animationDecimal * 10) / 10).toString());
        }

        function animateFrame(cnt, drawData) {
            var easeAdjustedAnimationPercent = (settings.animation) ? CapValue(easingFunction(cnt), null, 0) : 1;
            drawData(easeAdjustedAnimationPercent);
        }

        function animationLoop(drawData) {
            var animFrameAmount = (settings.animation) ? 1 / CapValue(settings.animationSteps, Number.MAX_VALUE, 1) : 1,
                cnt = (settings.animation) ? 0 : 1;
            requestAnimFrame(function () {
                cnt += animFrameAmount;
                animateFrame(cnt, drawData);
                if (cnt <= 1) {
                    requestAnimFrame(arguments.callee);
                } else {
                    settings.afterDrawn.call($this);
                }
            });
        }

        function legendValueChange(in_valueChange, in_valueChangeColor, in_valueChangeIcon) {
            var legend_item_text = '', valueChange = in_valueChange, valueChangeColor = in_valueChangeColor, valueChangeIcon = in_valueChangeIcon;

            if (valueChange) {
                /* color */
                if (valueChangeColor) {
                    switch (valueChangeColor) {
                        case 'green':
                            valueChangeColor = 'rgb(0, 201, 8)';
                            break;
                        case 'red':
                            valueChangeColor = 'rgb(207, 32, 0)';
                            break;
                        case 'grey':
                            valueChangeColor = 'rgb(84, 83, 73)';
                            break;
                        default:
                            break;
                    }
                } else {
                    valueChangeColor = 'inherit';
                }
                /* icon */
                if (valueChangeIcon) {
                    switch (valueChangeIcon) {
                        case 'up':
                            valueChangeIcon = '<span class="mr"><i class="fa fa-arrow-up" aria-hidden="true"></i></span>';
                            break;
                        case 'down':
                            valueChangeIcon = '<span class="mr"><i class="fa fa-arrow-down" aria-hidden="true"></i></span>';
                            break;
                        default:
                            break;
                    }
                } else {
                    valueChangeIcon = '';
                }

                legend_item_text
                    += '<span class="ml">'
                    + '<span>(</span>'
                    + '<span style="color: ' + valueChangeColor + '">'
                    + valueChangeIcon
                    + '<span class="mr">' + valueChange + '</span>'
                    + '</span>'
                    + '<span>p.p.)</span>'
                    + '</span>';
            }

            return $(legend_item_text);
        }

        /**
         * @return {number}
         */
        function Max(arr) {
            return Math.max.apply(null, arr);
        }

        /**
         * @return {number}
         */
        function Min(arr) {
            return Math.min.apply(null, arr);
        }

        function isNumber(n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        }

        function CapValue(valueToCap, maxValue, minValue) {
            if (isNumber(maxValue) && valueToCap > maxValue) return maxValue;
            if (isNumber(minValue) && valueToCap < minValue) return minValue;
            return valueToCap;
        }

        return $this;
    };
})(jQuery);