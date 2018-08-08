@extends('layouts.main.main')

@section('title', __('pages/admin.imgcategoryedit'))
@section('description', '')
@section('custom_css')
    <link rel="stylesheet" href="{{ URL::asset('css/form_css.css', env('HTTPS')) }}" type="text/css" media="all"/>
    <link rel="stylesheet" href="{{ URL::asset('css/auth.css', env('HTTPS')) }}" type="text/css" media="all"/>
@stop
@section('custom_js')
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
                                    <a href="{{ url($app->getLocale(). '/admin', null, env('HTTPS')) }}">{{__('pages/admin.adminpanel')}}</a>
                                    >
                                    <a href="{{ url($app->getLocale(). '/REST/images', null, env('HTTPS')) }}">{{__('pages/admin.imgindex')}}</a>
                                    >
                                    <a href="{{ url($app->getLocale(). '/REST/imagecategories/'.$imagecategory->id.'/edit', null, env('HTTPS')) }}">{{__('pages/admin.imgcategoryedit')}}</a>
                                </div>
                                <h2>{{__('pages/admin.imgcategoryedit')}}</h2>
                            </div>

                            <div class="auth-container">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            {!! Form::open(array('url' => url($app->getLocale(). '/REST/imagecategories/' . $imagecategory->id, null, env('HTTPS')), 'method' => 'delete', 'id' => 'destroy-form', 'class' => 'contact-form')) !!}
                                            <button type="submit" class="btn btn-primary button sliding_bg"><i
                                                        class="fa fa-times"
                                                        aria-hidden="true"></i> {{ __('pages/admin.deleteimgcategory') }}
                                            </button>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="btmspace-10"></div>

                            {!! Form::open(['url' => url($app->getLocale(). '/REST/imagecategories/'.$imagecategory->id, null, env('HTTPS')), 'method' => 'put', 'class' => 'form-horizontal']) !!}

                            <div class="auth-container">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name"
                                           class="col-md-4 control-label">{{__('pages/admin.imgcategoryname')}}</label>

                                    <div class="col-xs-12">
                                        <input id="name" type="text" class="form-control" name="name"
                                               value="{{ !empty(old('name')) ? old('name') : $imagecategory->name }}"
                                               required>

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                @foreach(config('app.locales') as $key => $locale)
                                    @if($key !== config('app.fallback_locale'))
                                        <div class="highlight_underline btmspace-30 text-center"><p>{{$locale}}</p></div>
                                        <div class="form-group{{ $errors->has('name_'.$key) ? ' has-error' : '' }}">
                                            <label for="name_{{$key}}"
                                                   class="col-md-4 control-label">{{__('pages/admin.imgcategoryname')}}</label>

                                            <div class="col-xs-12">
                                                <input id="name_{{$key}}" type="text" class="form-control"
                                                       name="name_{{$key}}"
                                                       value="{{ !empty(old('name_'.$key)) ? old('name_'.$key) : isset($translations[$key]['name']) ? $translations[$key]['name'] : '' }}">

                                                @if ($errors->has('name_'.$key))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('name_'.$key) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <button type="submit" class="btn btn-primary button sliding_bg">
                                            {{__('pages/admin.saveimgcategory')}}
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
