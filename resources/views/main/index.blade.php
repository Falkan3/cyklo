@extends('layouts.main.main', ['config_footer_map' => 1])

@section('title', __('pages/index.homepage'))
@section('description', '')
@section('custom_css_libs')
    <!-- libs -->
    <link rel="stylesheet" href="{{ URL::asset('libs/animate/css/animate_custom.css', env('HTTPS')) }}" type="text/css"
          media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('libs/slick/css/slick.css', env('HTTPS')) }}" type="text/css" media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('libs/slick/css/slick-theme.css', env('HTTPS')) }}" type="text/css"
          media="all"/>
@stop
@section('custom_css')

@stop
@section('custom_js')
    <!-- libs -->
    <script type="text/javascript" src="{{ URL::asset('libs/wow/js/wow.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/slick/js/slick.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/jquery_extensions/js/jquery.ba-throttle-debounce.min.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/table_sticky_header/js/jquery.stickyheader.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/doughnut_chart/js/doughnut-chart.js', env('HTTPS')) }}"></script>
    <!-- plugins -->
    <script src="{{ URL::asset('plugins/js/flippers.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('plugins/js/iterator.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('plugins/js/square.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('plugins/js/accordion.js', env('HTTPS')) }}"></script>
    <!-- main -->
    <script src="{{ URL::asset('js/utility.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('js/pages/index_script.js', env('HTTPS')) }}"></script>
@stop

@section('content')
    <section class="carousel-section">
        <div class="container-fluid">
            <div class="row">
                <div class="carousel">
                    <div class="item slide slide1 bged">
                        <div class="slide_caption"><p>Test 1</p></div>
                    </div>

                    <div class="item slide slide2 bged">
                        <div class="slide_caption"><p>Test 2</p></div>
                    </div>

                    <div class="item slide slide3 bged">
                        <div class="slide_caption"><p>Test 3</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page no-padding">
        <div class="container-fluid">
            <div class="row">
                <div class="header-section-container">
                    <div class="half-screen first">
                        <div class="slideInRightResize animated wow aligner all" data-wow-duration="1.5s">
                            <p class="uppercase">Cyklo</p>
                        </div>
                    </div>

                    <div class="half-screen second">
                        <div class="slideInUp animated wow aligner all" data-wow-duration="1.5s" data-wow-delay="1.5s">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi iaculis ligula ut metus
                                lobortis, id egestas massa tempus. Nulla eleifend molestie ex, sit amet porta augue
                                tristique non. Fusce dapibus ante et ipsum pellentesque commodo.</p>
                            <p>Nullam porta egestas
                                felis, ac tincidunt ipsum pulvinar ut. Maecenas enim mi, congue eget aliquam id,
                                ultrices vel nisi. Nam vel interdum turpis. Duis blandit tempor posuere. Praesent
                                tincidunt nulla eu nisl volutpat posuere.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page">
        <div class="container">
            <div class="row">
                <div class="half-screen-container">
                    <div class="half-screen first">
                        <div class="aligner all">
                            <?php /*<div class="underlay cogs"></div>*/ ?>
                            <a href="#" class="stretch"></a>
                            <p>{{__('pages/index.repairshop')}} <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
                        </div>
                    </div>

                    <div class="half-screen second">
                        <div class="aligner all">
                            <?php /*<div class="underlay bike"></div>*/ ?>
                            <a href="{{url($app->getLocale(). '/store', null, env('HTTPS'))}}" class="stretch"></a>
                            <p>{{__('pages/index.store')}} <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page no-padding-top">
        <div class="container cards">
            <div class="row">
                <div class="col-xs-12 bged bgcolor1 card5 aligner all">
                    <a href="#" class="stretch"></a>
                    <p>{{__('pages/index.onsale')}}</p>
                </div>
                <div class="col-xs-12 bged card3 aligner all">
                    <a href="#" class="stretch"></a>
                    <p>{{__('pages/index.recommended')}}</p>
                </div>
                <div class="col-xs-12 bged card1 aligner all">
                    <a href="#" class="stretch"></a>
                    <p>{{__('pages/index.bestsellers')}}</p>
                </div>
                <div class="col-xs-12 bged card2 aligner all">
                    <a href="#" class="stretch"></a>
                    <p>{{__('pages/index.new')}}</p>
                </div>
            </div>
        </div>
    </section>
@stop