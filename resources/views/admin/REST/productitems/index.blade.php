@extends('layouts.main.main')

@section('title', __('pages/admin.productitems'))
@section('description', '')
@section('custom_css')
    <link rel="stylesheet" href="{{ URL::asset('css/auth.css', env('HTTPS')) }}" type="text/css" media="all"/>
@stop
@section('custom_js')
    <!-- plugins -->
    <script src="{{ URL::asset('plugins/js/toggleable.js', env('HTTPS')) }}"></script>
    <!-- main -->
    <script src="{{ URL::asset('js/form_js.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('js/sendform.js', env('HTTPS')) }}"></script>
@stop


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        @if(session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session()->get('error') }}
                            </div>
                        @endif

                        <div class="container-fluid">
                            <div class="row navigation-row">
                                <div class="breadcrumb">
                                    <a href="{{ url($app->getLocale(). '/admin', null, env('HTTPS')) }}">{{__('pages/admin.adminpanel')}}</a>
                                    >
                                    <a href="{{ url($app->getLocale(). '/REST/productitems', null, env('HTTPS')) }}">{{__('pages/admin.productitems')}}</a>
                                </div>
                                <h2>{{__('pages/admin.productitems')}}</h2>
                            </div>

                            <div class="gallery-container">
                                <div class="row navigation-row">
                                    <a href="{{ url($app->getLocale(). '/REST/productitems/create', null, env('HTTPS')) }}"
                                       class="btn">{{__('pages/admin.addproductitem')}}</a>
                                </div>

                                <div class="row navigation-row">
                                    <p>{{__('pages/admin.productitemcategories')}}</p>

                                    @foreach($categories as $category)
                                        <a href="{{ url($app->getLocale(). '/REST/productcategories/'.$category->id.'/edit', null, env('HTTPS')) }}"
                                           class="btn small inverted">{{$category->name}}</a>
                                    @endforeach
                                    <a href="{{ url($app->getLocale(). '/REST/productcategories/create', null, env('HTTPS')) }}"
                                       class="btn small"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>

                                <div class="btmspace-30"></div>

                                <div class="toggleable"> <?php /*closed*/ ?>
                                    <div class="toggleable-settings">
                                        <p>{{__('pages/admin.allproductitems')}}</p>

                                        <a href="#" class="toggleable-slideup"><i
                                                    class="fa fa-minus-square" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                    <div class="row toggleable-content">
                                        <div class="ajax-navigation">
                                            {!! Form::open(array('url' => url($app->getLocale(). '/REST/productitems/index', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_productitem_page')) !!}
                                            {!! Form::hidden('offset', $offset) !!}
                                            {!! Form::hidden('limit', $limit) !!}
                                            {!! Form::hidden('direction', 'prev') !!}
                                            <button type="submit" class="navigation-btn"><i class="fa fa-chevron-left"
                                                                                            aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}

                                            <span>{{__('pages/admin.showing')}} <span id="imgpagemin">{{$offset}}</span> -
                                        <span id="imgpagemax">{{count($products) + $offset}}</span>
                                        </span>

                                            {!! Form::open(array('url' => url($app->getLocale(). '/REST/productitems/index', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_productitem_page', 'id' => 'images_fetch_productitem_form')) !!}
                                            {!! Form::hidden('offset', $offset) !!}
                                            {!! Form::hidden('limit', $limit) !!}
                                            {!! Form::hidden('direction', 'refresh') !!}
                                            <button type="submit" class="navigation-btn">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}

                                            {!! Form::open(array('url' => url($app->getLocale(). '/REST/productitems/index', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_productitem_page')) !!}
                                            {!! Form::hidden('offset', $offset) !!}
                                            {!! Form::hidden('limit', $limit) !!}
                                            {!! Form::hidden('direction', 'next') !!}
                                            <button type="submit" class="navigation-btn"><i class="fa fa-chevron-right"
                                                                                            aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        </div>

                                        @if(isset($products) && count($products) > 0)
                                            @foreach($products as $product)
                                                @if(!is_null($product))
                                                    <div class="col-xs-12 col-md-4 ajax-item">
                                                        <p>{{ $product->name }}</p>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <p>{{__('pages/admin.noproductitemsfound')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
