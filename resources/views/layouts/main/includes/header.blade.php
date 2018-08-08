<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{url($app->getLocale() . '/', null, env('HTTPS'))}}"><img src="{{asset('images/logo.png', env('HTTPS'))}}" alt="logo"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="nav navbar-nav">
                <li><a href="{{url($app->getLocale() . '/', null, env('HTTPS'))}}"><i class="fa fa-home" aria-hidden="true"></i> {{__('pages/index.homepage')}}</a></li>
                <li><a href="#"><i class="fa fa-cog" aria-hidden="true"></i> {{__('pages/index.repairshop')}}</a></li>
                <li><a href="{{url($app->getLocale(). '/store', null, env('HTTPS'))}}"><i class="fa fa-shopping-cart" aria-hidden="true"></i> {{__('pages/index.store')}}</a></li>
                <li><a href="{{url($app->getLocale(). '/catalog', null, env('HTTPS'))}}"><i class="fa fa-th-list" aria-hidden="true"></i> {{__('pages/index.catalog')}}</a></li>
                <li><a href="#"><i class="fa fa-phone" aria-hidden="true"></i> {{__('pages/index.contact')}}</a></li>
            </ul>
            <?php /*
            <ul class="nav navbar-nav navbar-left">
                <li><a href="#">Link</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="#">Separated link</a></li>
                    </ul>
                </li>
            </ul>
            */ ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown flag-icons">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <img src="{{ asset('images/els/country_flag_icons/' . $app->getLocale() . '.png', env('HTTPS')) }}" alt="{{ $app->getLocale() }}" /> <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        @foreach(config('app.locales') as $key => $locale)
                            @if($app->getLocale() !== $key)
                                <li>
                                    <a href="{{ url($app->getLocale() . '/helper/switch_lang/' . $key, null, env('HTTPS')) }}">
                                        <img src="{{ asset('images/els/country_flag_icons/' . $key . '.png', env('HTTPS')) }}" alt="{{ $locale }}" /> {{ $locale }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </li>
                @if (Auth::guest())
                    <li><a href="{{ url($app->getLocale() . '/login', null, env('HTTPS')) }}"><i class="fa fa-sign-in" aria-hidden="true"></i> {{__('system.login')}}</a></li>
                    <li><a href="{{ url($app->getLocale() . '/register', null, env('HTTPS')) }}"><i class="fa fa-bookmark" aria-hidden="true"></i> {{__('system.register')}}</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            @if (Auth::user()->isAdmin())
                                <li><a href="{{ url($app->getLocale() . '/admin', null, env('HTTPS')) }}"><i class="fa fa-home" aria-hidden="true"></i> {{__('pages/admin.adminpanel')}}</a></li>
                            @else
                                <li><a href="{{ url($app->getLocale() . '/home', null, env('HTTPS')) }}"><i class="fa fa-home" aria-hidden="true"></i> {{__('system.home')}}</a></li>
                            @endif
                            <li>
                                <a href="{{ url($app->getLocale() . '/logout', null, env('HTTPS')) }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out" aria-hidden="true"></i> {{__('system.logout')}}
                                </a>

                                <form id="logout-form" action="{{ url($app->getLocale() . '/logout', null, env('HTTPS')) }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<noscript>
    <div class="noscript-warning">
        <p>{{__('system.noscript')}}</p>
    </div>
</noscript>

@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
@if(session()->has('error'))
    <div class="alert alert-danger">
        @if(is_array(session()->get('error')))
            @foreach(session()->get('error') as $error)
                <p>{{ $error }}</p>
            @endforeach
        @else
            {{ session()->get('error') }}
        @endif
    </div>
@endif