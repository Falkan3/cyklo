@extends('layouts.main.main', ['config_footer_map' => 1])

@section('title', __('pages/store.title'))
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
    <!-- main -->
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
                            <p class="uppercase">{{__('pages/store.title')}}</p>
                        </div>
                    </div>

                    <div class="half-screen second">
                        <div class="slideInUp animated wow aligner all" data-wow-duration="1.5s" data-wow-delay="1.5s">
                            <p>{{__('pages/store.stillbuilding')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page no-padding">
        <div class="container-fluid cards big">
            <div class="row">
                @foreach($categories as $category)
                    <div class="col-xs-12 col-md-6 bged aligner all">
                        <a href="#" class="stretch"></a>
                        <p>{{$category['name']}}</p>
                        <img src="{{$category['image_data']}}" class="card" alt="card_{{$category['name']}}" />
                    </div>
                @endforeach
                <?php /*
                <div class="col-xs-12 col-md-6 bged card1 aligner all">
                    <a href="#" class="stretch"></a>
                    <p>{{__('pages/store.parts')}}</p>
                </div>
                <div class="col-xs-12 col-md-6 bged card2 aligner all">
                    <a href="#" class="stretch"></a>
                    <p>{{__('pages/store.wheels')}}</p>
                </div>
                <div class="col-xs-12 col-md-6 bged card3 aligner all">
                    <a href="#" class="stretch"></a>
                    <p>{{__('pages/store.bikes')}}</p>
                </div>
                <div class="col-xs-12 col-md-6 bged card4 aligner all">
                    <a href="#" class="stretch"></a>
                    <p>{{__('pages/store.accessories')}}</p>
                </div>
                */ ?>
            </div>
        </div>
    </section>
@stop