@extends('layouts.main.main')

@section('title', __('pages/admin.editimg'))
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
                                    <a href="{{ url($app->getLocale(). '/REST/images/'.$image->id.'/edit', null, env('HTTPS')) }}">{{__('pages/admin.editimg')}}</a>
                                </div>
                                <h2>{{__('pages/admin.editimg')}}</h2>
                            </div>

                            <div class="row view-image small btmspace-30">
                                <img src="{{ url($app->getLocale(). '/helper/image/' . $image->id, null, env('HTTPS')) }}"
                                     alt="{{$image->title}}"/>
                            </div>

                            {!! Form::open(['url' => url($app->getLocale(). '/REST/images/'.$image->id, null, env('HTTPS')), 'method' => 'put', 'files' => true, 'class' => 'form-horizontal']) !!}

                            <div class="auth-container">
                                <div class="form-group{{ $errors->has('imgfile') ? ' has-error' : '' }}">
                                    <label for="imgtitle"
                                           class="col-md-4 control-label">{{__('pages/admin.imgfile')}}</label>

                                    <div class="col-xs-12">
                                        {!! Form::file('imgfile', old('imgfile'), ['required', 'autofocus', 'id' => 'imgfile', 'class' => 'form-control']) !!}

                                        @if ($errors->has('imgfile'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('imgfile') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('imgtitle') ? ' has-error' : '' }}">
                                    <label for="imgtitle"
                                           class="col-md-4 control-label">{{__('pages/admin.imgtitle')}}</label>

                                    <div class="col-xs-12">
                                        <input id="imgtitle" type="text" class="form-control" name="imgtitle"
                                               value="{{ !empty(old('imgtitle')) ? old('imgtitle') : $image->title }}" required>

                                        @if ($errors->has('imgtitle'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('imgtitle') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('imgcomment') ? ' has-error' : '' }}">
                                    <label for="imgcomment"
                                           class="col-md-4 control-label">{{__('pages/admin.imgcomment')}}</label>

                                    <div class="col-xs-12">
                                        <textarea id="imgcomment" class="form-control" name="imgcomment">{{ !empty(old('imgcomment')) ? old('imgcomment') : $image->comment }}</textarea>

                                        @if ($errors->has('imgcomment'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('imgcomment') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('imgcategory') ? ' has-error' : '' }}">
                                    <label for="imgcategory"
                                           class="col-md-4 control-label">{{__('pages/admin.imgcategory')}}</label>

                                    <div class="col-xs-12">
                                        <select id="imgcategory" class="form-control" name="imgcategory">
                                            <option value="">{{ __('pages/admin.null_option') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{$category->id}}" @if($image->category_id === $category->id) selected @endif>{{$category->name}}</option>
                                            @endforeach
                                        </select>

                                        @if ($errors->has('imgcategory'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('imgcategory') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('public') ? ' has-error' : '' }}">
                                    <label for="public"
                                           class="col-md-4 control-label">{{__('pages/admin.imgpublic')}}</label>

                                    <div class="col-xs-12">
                                        <div class="checkbox no-float text-center">
                                            <?php $public = ''; if(!empty(old('public'))){$public = old('public');}else{$public = $image->public;} ?>
                                            {!! Form::checkbox('public', null, $public ? 'checked' : '', ['id' => 'public']) !!}
                                            {!! Html::decode(Form::label('public', '<span class="no-margin"></span>', ['class' => 'control-label no-padding'])) !!}
                                        </div>

                                        @if ($errors->has('public'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('public') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                @foreach(config('app.locales') as $key => $locale)
                                    @if($key !== config('app.fallback_locale'))
                                        <div class="highlight_underline btmspace-30 text-center"><p>{{$locale}}</p></div>
                                        <div class="form-group{{ $errors->has('imgtitle_'.$key) ? ' has-error' : '' }}">
                                            <label for="imgtitle_{{$key}}"
                                                   class="col-md-4 control-label">{{__('pages/admin.imgtitle')}}</label>

                                            <div class="col-xs-12">
                                                <input id="imgtitle_{{$key}}" type="text" class="form-control"
                                                       name="imgtitle_{{$key}}"
                                                       value="{{ !empty(old('imgtitle_'.$key)) ? old('imgtitle_'.$key) : isset($translations[$key]['title']) ? $translations[$key]['title'] : '' }}">

                                                @if ($errors->has('imgtitle_'.$key))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('imgtitle_'.$key) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group{{ $errors->has('imgcomment_'.$key) ? ' has-error' : '' }}">
                                            <label for="imgcomment_{{$key}}"
                                                   class="col-md-4 control-label">{{__('pages/admin.imgcomment')}}</label>

                                            <div class="col-xs-12">
                                                <textarea id="imgcomment_{{$key}}" class="form-control" name="imgcomment_{{$key}}">
                                                    {{ !empty(old('imgcomment_'.$key)) ? old('imgcomment_'.$key) : isset($translations[$key]['comment']) ? $translations[$key]['comment'] : '' }}
                                                </textarea>

                                                @if ($errors->has('imgcomment_'.$key))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('imgcomment_'.$key) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <button type="submit" class="btn btn-primary button sliding_bg">
                                            {{__('pages/admin.editimg')}}
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
