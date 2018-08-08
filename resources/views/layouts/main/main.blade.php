<!DOCTYPE html>
<html lang="{{$app->getLocale()}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
@yield('custom_meta')

<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{config('app.name')}}</title>
    <meta name="description" content="@yield('description')">

    <link rel="shortcut icon" href="{{ asset('favicon.ico', env('HTTPS')) }}" type="image/x-icon">

    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#212121"/>
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#212121"/>
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ URL::asset('libs/bootstrap/css/bootstrap.min.css', env('HTTPS')) }}" type="text/css" media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('libs/bootstrap/css/bootstrap-theme.min.css', env('HTTPS')) }}" type="text/css" media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('fonts/fontawesome/fontawesome-4.3.0.min.css', env('HTTPS')) }}" type="text/css"
          media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('libs/malihu_custom_scrollbar/css/jquery.mCustomScrollbar.min.css', env('HTTPS')) }}" type="text/css"
          media="all"/>
    @yield('custom_css_libs')

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,700&amp;subset=latin-ext" rel="stylesheet">

    <link rel="stylesheet" href="{{ URL::asset('css/framework.css', env('HTTPS')) }}" type="text/css" media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('css/layout.css', env('HTTPS')) }}" type="text/css" media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('css/style.css', env('HTTPS')) }}" type="text/css" media="all"/>
@yield('custom_css')

<!-- Scripts -->
    <script src="{{ URL::asset('libs/jquery/js/jquery-3.1.1.min.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('libs/bootstrap/js/bootstrap.min.js', env('HTTPS')) }}"></script>

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<?php
$body_classes = '';
if(isset($config_footer_map) && $config_footer_map === 1) {
    $body_classes .= 'ft_lgt_2';
} else {
    $body_classes .= 'ft_lgt_1';
}
?>

<body class="{{$body_classes}}">
    <div id="hideAll">
        <div class="loader"></div>
    </div>
    <script type="text/javascript">
        document.getElementById("hideAll").style.display = "block";
    </script>

    @include('layouts.main.includes.header')

    <div class="wrapper full-height">
        @yield('content')
    </div>

    @include('layouts.main.includes.footer')

    <a id="backtotop" href="#top"><i class="fa fa-chevron-up"></i></a>
    <div class="loader global_ajax_loader loading_ajax hidden"></div>

    <!-- Scripts -->
    <script type="text/javascript" src="{{ URL::asset('libs/jquery/js/jquery.easing.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/jquery/js/jquery-inputmask.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/jquery/js/jquery.counterup.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/jquery/js/jquery.waypoints.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/jquery/js/jquery.mobilemenu.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/jquery/js/jquery.lazyload.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/jquery_extensions/js/jquery.mousewheel.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/malihu_custom_scrollbar/js/jquery.mCustomScrollbar.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/notify/js/notify.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/mainscript.js', env('HTTPS')) }}"></script>
    @yield('custom_js')

    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
    <script>
        window.addEventListener("load", function () {
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#ffffff",
                        "text": "#212121"
                    },
                    "button": {
                        "background": "transparent",
                        "text": "#ff9c10",
                        "border": "#ff9c10"
                    }
                },
                "position": "bottom-left",
                "content": {
                    "message": "{{__('system.cookieconsent_content')}}",
                    "dismiss": "{{__('system.cookieconsent_button')}}",
                    "link": "{{__('system.cookieconsent_learnmore')}}"
                }
            })
        });
    </script>

</body>
</html>
