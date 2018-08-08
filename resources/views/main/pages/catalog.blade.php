@extends('layouts.main.main', ['config_footer_map' => 1])

@section('title', __('pages/catalog.title'))
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
    <!-- main -->
    <link rel="stylesheet" href="{{ URL::asset('css/form_css.css', env('HTTPS')) }}" type="text/css" media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('css/auth.css', env('HTTPS')) }}" type="text/css" media="all"/>
@stop
@section('custom_js')
    <!-- libs -->
    <script type="text/javascript" src="{{ URL::asset('libs/wow/js/wow.js', env('HTTPS')) }}"></script>
    <script type="text/javascript" src="{{ URL::asset('libs/slick/js/slick.min.js', env('HTTPS')) }}"></script>
    <!-- main -->
    <script src="{{ URL::asset('js/form_js.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('js/sendform.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('js/pages/catalog_script.js', env('HTTPS')) }}"></script>
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
                            <p class="uppercase">{{__('pages/catalog.title')}}</p>
                        </div>
                    </div>

                    <div class="half-screen second">
                        <div class="slideInUp animated wow aligner all" data-wow-duration="1.5s" data-wow-delay="1.5s">
                            <p>{{__('pages/catalog.stillbuilding')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="page">
        <div class="container-fluid catalog-list list-view">
            <div class="row">
                <div class="col-xs-12 col-md-3">
                    <p class="header">{{__('pages/catalog.filters')}}</p>

                    {!! Form::open(array('url' => url($app->getLocale(). '/helper/productitems/listproducts', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_product_list_catalog')) !!}
                    <div class="container-fluid filters panel no-style">
                        <div class="row">
                            <div class="col-xs-12 relative">
                                <input id="q_search_name" type="text" class="form-control" name="q_search_name"
                                       value="{{ !empty(old('q_search_name')) ? old('q_search_name') : isset($search_params['q_search_name']) ? $search_params['q_search_name'] : '' }}" />

                                <div class="search-icon">
                                    <a href="#"><i class="fa fa-search" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-12">
                                <div class="checkbox left">
                                    {!! Form::checkbox('q_search_onsale', null, old('q_search_onsale') ? 'checked' : isset($search_params['q_search_onsale']) ? $search_params['q_search_onsale'] : '', ['id' => 'q_search_onsale']) !!}
                                    {!! Html::decode(Form::label('q_search_onsale', '<span></span>'.__('pages/catalog.onsale'), ['class' => 'control-label'])) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-12">
                                <div class="checkbox left">
                                    {!! Form::checkbox('q_search_recommended', null, old('q_search_recommended') ? 'checked' : isset($search_params['q_search_recommended']) ? $search_params['q_search_recommended'] : '', ['id' => 'q_search_recommended']) !!}
                                    {!! Html::decode(Form::label('q_search_recommended', '<span></span>'.__('pages/catalog.recommended'), ['class' => 'control-label'])) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-12">
                                <div class="checkbox left">
                                    {!! Form::checkbox('q_search_bestsellers', null, old('q_search_bestsellers') ? 'checked' : isset($search_params['q_search_bestsellers']) ? $search_params['q_search_bestsellers'] : '', ['id' => 'q_search_bestsellers']) !!}
                                    {!! Html::decode(Form::label('q_search_bestsellers', '<span></span>'.__('pages/catalog.bestsellers'), ['class' => 'control-label'])) !!}
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-12">
                                <div class="checkbox left">
                                    {!! Form::checkbox('q_search_new', null, old('q_search_new') ? 'checked' : isset($search_params['q_search_new']) ? $search_params['q_search_new'] : '', ['id' => 'q_search_new']) !!}
                                    {!! Html::decode(Form::label('q_search_new', '<span></span>'.__('pages/catalog.new'), ['class' => 'control-label'])) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary button">
                        {{__('pages/catalog.filter')}}
                    </button>
                    {!! Form::close() !!}
                </div>
                <div class="col-xs-12 col-md-9">
                    <p class="header">{{__('pages/catalog.items')}}</p>

                    <div class="container-fluid items">
                        <div class="row">
                            <div class="col-xs-12">
                                <p>item</p>
                            </div>
                            <div class="col-xs-12">
                                <p>item</p>
                            </div>
                            <div class="col-xs-12">
                                <p>item</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop