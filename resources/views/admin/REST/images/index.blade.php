@extends('layouts.main.main')

@section('title', __('pages/admin.imgindex'))
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
                                    <a href="{{ url($app->getLocale(). '/REST/images', null, env('HTTPS')) }}">{{__('pages/admin.imgindex')}}</a>
                                </div>
                                <h2>{{__('pages/admin.imgindex')}}</h2>
                            </div>

                            <div class="gallery-container">
                                <div class="row navigation-row">
                                    <a href="{{ url($app->getLocale(). '/REST/images/create', null, env('HTTPS')) }}"
                                       class="btn">{{__('pages/admin.uploadimg')}}</a>
                                </div>

                                <div class="row navigation-row">
                                    <p>{{__('pages/admin.imgcategories')}}</p>

                                    @foreach($categories as $category)
                                        <a href="{{ url($app->getLocale(). '/REST/imagecategories/'.$category->id.'/edit', null, env('HTTPS')) }}"
                                           class="btn small inverted">{{$category->name}}</a>
                                    @endforeach
                                    <a href="{{ url($app->getLocale(). '/REST/imagecategories/create', null, env('HTTPS')) }}"
                                       class="btn small"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                </div>

                                <div class="btmspace-30"></div>

                                <div class="toggleable"> <?php /*closed*/ ?>
                                    <div class="toggleable-settings">
                                        <p>{{__('pages/admin.allimages')}}</p>

                                        <a href="#" class="toggleable-slideup"><i
                                                    class="fa fa-minus-square" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                    <div class="row toggleable-content">
                                        <div class="ajax-navigation">
                                            {!! Form::open(array('url' => url($app->getLocale(). '/REST/images/index', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_image_page')) !!}
                                            {!! Form::hidden('offset', $offset) !!}
                                            {!! Form::hidden('limit', $limit) !!}
                                            {!! Form::hidden('direction', 'prev') !!}
                                            <button type="submit" class="navigation-btn">
                                                <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}

                                            <span>{{__('pages/admin.showing')}} <span id="imgpagemin">{{$offset}}</span> -
                                        <span id="imgpagemax">{{($count_images + $offset)}}</span>
                                        </span>

                                            {!! Form::open(array('url' => url($app->getLocale(). '/REST/images/index', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_image_page', 'id' => 'images_fetch_refresh_form')) !!}
                                            {!! Form::hidden('offset', $offset) !!}
                                            {!! Form::hidden('limit', $limit) !!}
                                            {!! Form::hidden('direction', 'refresh') !!}
                                            <button type="submit" class="navigation-btn">
                                                <i class="fa fa-refresh" aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}

                                            {!! Form::open(array('url' => url($app->getLocale(). '/REST/images/index', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_image_page')) !!}
                                            {!! Form::hidden('offset', $offset) !!}
                                            {!! Form::hidden('limit', $limit) !!}
                                            {!! Form::hidden('direction', 'next') !!}
                                            <button type="submit" class="navigation-btn">
                                                <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                            </button>
                                            {!! Form::close() !!}
                                        </div>

                                        @if(isset($images) && $count_images > 0)
                                            @foreach($images as $image)
                                                @if(!is_null($image))
                                                    <div class="col-xs-12 col-md-4 ajax-item">
                                                        <div class="hover-menu">
                                                            <a href="{{url($app->getLocale(). '/REST/images/' . $image->id, null, env('HTTPS'))}}"
                                                               class="hover-menu-item"><i class="fa fa-search"
                                                                                          aria-hidden="true"></i></a>
                                                            <a href="{{url($app->getLocale(). '/REST/images/' . $image->id . '/edit', null, env('HTTPS'))}}"
                                                               class="hover-menu-item"><i class="fa fa-pencil"
                                                                                          aria-hidden="true"></i></a>
                                                            {!! Form::open(array('url' => url($app->getLocale(). '/REST/images/' . $image->id, null, env('HTTPS')), 'method' => 'delete', 'id' => 'destroy-form', 'class' => 'contact-form', 'data-ajax' => 'true', 'data-ajax-id' => 'image')) !!}
                                                            <button type="submit" class="hover-menu-item"><i
                                                                        class="fa fa-times" aria-hidden="true"></i>
                                                            </button>
                                                            {!! Form::close() !!}
                                                            <p class="hover-menu-item">{{substr($image->title,0,15)}}@if(strlen($image->title)>15){{'...'}}@endif</p>
                                                        </div>
                                                        <a href="{{ url($app->getLocale(). '/REST/images/' . $image->id, null, env('HTTPS')) }}"
                                                           class="stretch"></a>
                                                        <img src="{{$image['image_data']}}"
                                                             alt="{{$image->title}}"/>
                                                        <?php /* <img src="{{ url($app->getLocale(). '/helper/image/' . $image->id, null, env('HTTPS')) }}"
                                                             alt="{{$image->title}}"/> */ ?>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <p>{{__('pages/admin.noimgsfound')}}</p>
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
