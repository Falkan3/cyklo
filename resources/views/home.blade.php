@extends('layouts.main.main')

@section('title', __('system.home'))
@section('description', '')
@section('custom_css')
    <link rel="stylesheet" href="{{ URL::asset('css/auth.css', env('HTTPS')) }}" type="text/css" media="all"/>
@stop
@section('custom_js')
    <!-- plugins -->
    <script src="{{ URL::asset('plugins/js/flippers.js', env('HTTPS')) }}"></script>
@stop


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
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
                                    <a href="{{ url($app->getLocale(). '/home', null, env('HTTPS')) }}">{{__('pages/home.dashboard')}}</a>
                                </div>
                                <h2>{{__('pages/home.dashboard')}}</h2>
                            </div>

                            <div class="tile-container">
                                <div class="flipper-main-container">
                                    @if(Auth::user()->isAdmin())
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-4 col-md-3">
                                                <div class="flip-container" data-ontouchstart-hover="true">
                                                    <div class="flipper color_1">
                                                        <div class="front underlay cog2">

                                                        </div>
                                                        <div class="back aligner all">
                                                            <a href="{{url($app->getLocale(). '/admin', null, env('HTTPS'))}}"></a>
                                                            <p>{{__('pages/home.adminpanel')}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="flip-container" data-ontouchstart-hover="true">
                                                <div class="flipper color_2">
                                                    <div class="front underlay profile">

                                                    </div>
                                                    <div class="back aligner all">
                                                        <a href="{{url($app->getLocale(). '/profile', null, env('HTTPS'))}}"></a>
                                                        <p>{{__('pages/home.profile')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="flip-container" data-ontouchstart-hover="true">
                                                <div class="flipper color_3">
                                                    <div class="front underlay cart">

                                                    </div>
                                                    <div class="back aligner all">
                                                        <a href="{{url($app->getLocale(). '/cart', null, env('HTTPS'))}}"></a>
                                                        <p>{{__('pages/home.cart')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12 col-sm-4 col-md-3">
                                            <div class="flip-container" data-ontouchstart-hover="true">
                                                <div class="flipper color_4">
                                                    <div class="front underlay order">

                                                    </div>
                                                    <div class="back aligner all">
                                                        <a href="{{url($app->getLocale(). '/orders', null, env('HTTPS'))}}"></a>
                                                        <p>{{__('pages/home.orders')}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
