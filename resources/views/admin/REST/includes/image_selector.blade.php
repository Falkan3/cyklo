<div class="image-selector container-fluid">
    <a href="#" class="close-btn"><i class="fa fa-times" aria-hidden="true"></i></a>

    <div class="ajax-navigation">
        {!! Form::open(array('url' => url($app->getLocale(). '/REST/imageselector', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_imageselector_page')) !!}
        {!! Form::hidden('offset', $offset) !!}
        {!! Form::hidden('limit', $limit) !!}
        {!! Form::hidden('direction', 'prev') !!}
        <button type="submit" class="navigation-btn">
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </button>
        {!! Form::close() !!}

        <span>{{__('pages/admin.showing')}} <span id="imgpagemin">{{$offset}}</span> - <span id="imgpagemax">{{($count_images + $offset)}}</span></span>

        {!! Form::open(array('url' => url($app->getLocale(). '/REST/imageselector', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_imageselector_page', 'id' => 'images_fetch_refresh_form')) !!}
        {!! Form::hidden('offset', $offset) !!}
        {!! Form::hidden('limit', $limit) !!}
        {!! Form::hidden('direction', 'refresh') !!}
        <button type="submit" class="navigation-btn">
            <i class="fa fa-refresh" aria-hidden="true"></i>
        </button>
        {!! Form::close() !!}

        {!! Form::open(array('url' => url($app->getLocale(). '/REST/imageselector', null, env('HTTPS')), 'method' => 'get', 'class' => 'contact-form inline-block', 'data-ajax' => 'true', 'data-ajax-id' => 'fetch_imageselector_page')) !!}
        {!! Form::hidden('offset', $offset) !!}
        {!! Form::hidden('limit', $limit) !!}
        {!! Form::hidden('direction', 'next') !!}
        <button type="submit" class="navigation-btn">
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </button>
        {!! Form::close() !!}
    </div>

    <div class="row">
        @foreach($images as $image)
            <div class="col-xs-12 col-sm-4 col-md-15 ajax-item">
                <div class="box-image">
                    <button type="button" data-index="image_selector" data-val="{{ $image->id }}"
                            title="{{ $image->title }}"></button>
                    <img src="{{ $image['image_data'] }}" alt="{{ $image->title }}"/>
                </div>
            </div>
        @endforeach
    </div>
</div>