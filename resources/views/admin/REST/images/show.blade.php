@extends('layouts.main.main')

@section('title', __('pages/admin.viewimage'))
@section('description', '')
@section('custom_css')
    <link rel="stylesheet" href="{{ URL::asset('css/auth.css', env('HTTPS')) }}" type="text/css" media="all"/>
@stop
@section('custom_js')
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
                                    >
                                    <a href="{{ url($app->getLocale(). '/REST/images/'.$image->id.'/show', null, env('HTTPS')) }}">{{substr($image->title,0,15)}}@if(strlen($image->title)>15){{'...'}}@endif</a>
                                </div>
                                <h2>{{__('pages/admin.viewimage')}}</h2>
                            </div>

                            <div class="row view-image btmspace-30">
                                <img src="{{ url($app->getLocale(). '/helper/image/' . $image->id, null, env('HTTPS')) }}"
                                     alt="{{$image->title}}"/>
                            </div>

                            <div class="row highlight_border text-breakall">
                                <p><span class="bold">{{__('pages/admin.imgtitle')}}:</span> {{$image->title}}</p>
                                <p><span class="bold">{{__('pages/admin.imgcomment')}}:</span> {{$image->comment}}</p>
                                <p><span class="bold">{{__('pages/admin.imgpublic')}}:</span> @if($image->public){{__('pages/admin.imgpublicyes')}}@else{{__('pages/admin.imgpublicno')}}@endif</p>
                                <p><span class="bold">{{__('pages/admin.imgname')}}:</span> {{$image->name}}</p>
                                <p><span class="bold">{{__('pages/admin.imglocation')}}:</span> {{$image->location}}</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
