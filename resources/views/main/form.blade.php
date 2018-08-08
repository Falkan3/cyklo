@extends('layouts.main.main')

@section('title', 'index')
@section('description', '')

@section('custom_css')
    <link rel="stylesheet" href="{{ URL::asset('css/form_css.css', env('HTTPS')) }}" type="text/css" media="all"/>
@stop
@section('custom_js')
    <!-- plugins -->
    <script src="{{ URL::asset('plugins/js/square.js', env('HTTPS')) }}"></script>
    <!-- main -->
    <script src="{{ URL::asset('js/form_js.js', env('HTTPS')) }}"></script>
    <script src="{{ URL::asset('js/sendform.js', env('HTTPS')) }}"></script>
@stop

@section('content')
    <section class="page big-form">
        <div class="container">
            <h1 class="text-center">Formularz kontaktowy</h1>

            {!! Form::open(['url' => url('api/submitLead', null, env('HTTPS')), 'method' => 'post', 'class' =>'contact-form', 'novalidate']) !!}

            <div class="row fields">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    {{ Form::label('txt_name', 'Imię', ['class' => 'control-label']) }}
                    {{Form::text('txt_name', null, ['id' => 'txt_name', 'class' => 'name'])}}
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    {{ Form::label('txt_surname', 'Nazwisko', ['class' => 'control-label']) }}
                    {{Form::text('txt_surname', null, ['id' => 'txt_surname', 'class' => 'name'])}}
                </div>
            </div>

            <div class="row fields">
                <div class="col-xs-12 col-sm-6 col-md-6">
                    {{ Form::label('txt_tel', 'Telefon', ['class' => 'control-label']) }}
                    {{Form::text('txt_tel', null, ['id' => 'txt_tel', 'class' => 'telephone'])}}
                </div>

                <div class="col-xs-12 col-sm-6 col-md-6">
                    {{ Form::label('txt_email', 'E-mail', ['class' => 'control-label']) }}
                    {{Form::text('txt_email', null, ['id' => 'txt_email', 'class' => 'email'])}}
                </div>
            </div>

            <div class="row">
                <div class="relative">
                    {!! Form::hidden('test', null, ['required', 'data-container' => 'true', 'data-index' => '0']) !!}
                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="tools" data-index="0" data-val="test"></button>
                        </div>
                        <p>Remont</p></div>
                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="sun" data-index="0" data-val="test2"></button>
                        </div>
                        <p>Wakacje</p></div>

                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="rings" data-index="0" data-val="test3"></button>
                        </div>
                        <p>Ślub</p></div>
                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="taxes" data-index="0" data-val="test4"></button>
                        </div>
                        <p>Rachunki</p></div>

                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="car" data-index="0" data-val="test5"></button>
                        </div>
                        <p>Samochód</p></div>
                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="house" data-index="0" data-val="test6"></button>
                        </div>
                        <p>Mieszkanie</p></div>

                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="mortarboard" data-index="0" data-val="test7"></button>
                        </div>
                        <p>Szkolenie</p></div>
                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="monitor" data-index="0" data-val="test8"></button>
                        </div>
                        <p>Sprzęt</p></div>

                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="wallet" data-index="0" data-val="test9"></button>
                        </div>
                        <p>Spłata kredytu</p></div>
                    <div class="col-xs-12 col-sm-6 col-md-15">
                        <div class="box-image">
                            <button type="button" class="star" data-index="0" data-val="test10"></button>
                        </div>
                        <p>Inne</p></div>
                </div>
            </div>

            <div style="margin-top: 20px;" class="agreements">
                <div>
                    {{Form::checkbox('agree1', '1', 1, ['id' => 'agree1'])}}
                    {!!Html::decode(Form::label('agree1', '<span></span>Zgoda na gromadzenie i przetwarzanie danych osobowych', ['class' => 'control-label']))!!}
                    <a href="#" class="read-more-click anchor">[Więcej]</a>
                    <div class="read-more">
                        <p>
                            Wyrażam zgodę na przetwarzanie przez Spark DigitUP Sp. z o.o.(Administrator Danych)
                            podanych przeze mnie danych osobowych w celu przedstawienia dopasowanej oferty produktów
                            finansowych przygotowanej przez partnerów Administratora Danych oraz w celach
                            marketingowych Administratora Danych, a także na opracowywanie podanych danych osobowych
                            wraz z innymi danymi osobowymi przetwarzanymi przez Administratora, w celu wskazanym
                            powyżej. Wyrażam zgodę na przekazywanie lub udostępnianie podanych danych osobowych
                            partnerom Administratora Danych, w szczególności instytucjom finansowym i
                            ubezpieczeniowym w celu przedstawienia przedstawienia dopasowanej oferty produktów
                            finansowych oraz w celach marketingowych. Informujemy, że Administratorem Danych
                            osobowych jest Spark DigitUP Sp. z o.o. z siedzibą w Krakowie (31-060), Plac Wolnica 13
                            lok. 10, NIP: 6762496391, REGON: 363042916, wpisaną do rejestru przedsiębiorców
                            Krajowego Rejestru Sądowego prowadzonego przez Sąd Rejonowy dla Krakowa-Śródmieście w
                            Krakowie, XIII Wydział Gospodarczy Krajowego Rejestru Sądowego pod numerem KRS
                            0000587711 o kapitale zakładowym w wysokości 5.000 złotych Przetwarzanie danych
                            osobowych będzie miało miejsce zgodnie z ustawą z 29 sierpnia 1997 r. o ochronie danych
                            osobowych. Posiada Pan/Pani prawo dostępu do treści swoich danych oraz ich poprawiania,
                            a także zwrócenia się z żądaniem usunięcia podanych danych osobowych. Zbierane dane będą
                            przetwarzane, w tym przekazywane partnerom administratora, wyłącznie w celach podanych
                            powyżej. Podanie przez Pana/Panią danych osobowych jest całkowicie dobrowolne.
                        </p>
                    </div>
                </div>

                <div>
                    {{Form::checkbox('agree2', '1', 1, ['id' => 'agree2'])}}
                    {!!Html::decode(Form::label('agree2', '<span></span>Zgoda na otrzymywanie informacji handlowych', ['class' => 'control-label']))!!}
                    <a href="#" class="read-more-click anchor">[Więcej]</a>
                    <div class="read-more">
                        <p>
                            Wyrażam zgodę na otrzymywanie od Spark DigitUP Sp. z o.o., informacji handlowej w
                            rozumieniu art. 10 ust. 2 Ustawy z dnia 18 lipca 2002 r. o świadczeniu usług drogą
                            elektroniczną na podany adres poczty elektronicznej.
                        </p>
                    </div>
                </div>

                <div>
                    {{Form::checkbox('agree3', '1', 1, ['id' => 'agree3'])}}
                    {!!Html::decode(Form::label('agree3', '<span></span>Zgoda na używanie telekomunikacyjnych urządzeń końcowych', ['class' => 'control-label']))!!}
                    <a href="#" class="read-more-click anchor">[Więcej]</a>
                    <div class="read-more">
                        <p>
                            Wyrażam zgodę na używanie telekomunikacyjnych urządzeń końcowych i automatycznych
                            systemów wywołujących (w rozumieniu ustawy z dnia 16 lipca 2004 r. – Prawo
                            telekomunikacyjne (Dz. U. z 2014 r. poz. 243 z późn. zm.)); w stosunku do podanego
                            przeze mnie numeru telefonu, dla celów marketingu bezpośredniego przez DigitUp Sp. z
                            o.o. z siedzibą z siedzibą w Krakowie (31-060), Plac Wolnica 13 lok. 10, NIP:
                            6762496391, REGON: 363042916, wpisaną do rejestru przedsiębiorców Krajowego Rejestru
                            Sądowego prowadzonego przez Sąd Rejonowy dla Krakowa-Śródmieście w Krakowie, XIII
                            Wydział Gospodarczy Krajowego Rejestru Sądowego pod numerem KRS 0000587711 o kapitale
                            zakładowym w wysokości 5.000 złotych.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row submit-row">
                <div class="col-xs-12 col-sm-12 col-md-12 no-padding">
                    <input type="submit" value="Złóż wniosek" class="button sliding_bg"/>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 no-padding">
                    <img src="{{asset('images/ajax-loader.gif', env('HTTPS'))}}" alt="loading..."
                         class="loading_ajax hidden"/>
                    <div class="status"></div>
                </div>
            </div>
            {!! Form::close() !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 form-thank-you"></div>
            </div>
        </div>
    </section>
@stop