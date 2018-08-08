<?php /*
@if(isset($config_footer_map) && $config_footer_map === 1)
    <style>
        body {
            margin-bottom: 700px;
        }
        @media (max-width: 991px) {
            body {
                margin-bottom: 900px;
            }
        }
    </style>
@else
    <style>
        body {
            margin-bottom: 350px;
        }
        @media (max-width: 991px) {
            body {
                margin-bottom: 500px;
            }
        }
        footer {
            height: auto;
        }
    </style>
@endif
*/ ?>

<footer>
    <div class="footer-content">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <ul>
                        <li><i class="fa fa-map-marker" aria-hidden="true"></i> ul. Wójta Radtkego 49A, 81-355 Gdynia, pomorskie</li>
                        <li><i class="fa fa-phone" aria-hidden="true"></i> <a href="tel:58-661-77-97">+48 (58) 661 77 97</a></li>
                        <li><i class="fa fa-envelope" aria-hidden="true"></i> <a href="mailto:cyklosc@go2.pl">cyklosc@go2.pl</a></li>
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="bold">{{__('pages/index.mon-fri')}}</span> 9:00-17:00</li>
                        <li><i class="fa fa-clock-o" aria-hidden="true"></i> <span class="bold">{{__('pages/index.sat')}}</span> 10:00-15:00</li>
                    </ul>
                </div>

                <div class="col-xs-12 col-md-4">
                    <a href="#" class="button facebook">
                        <i class="fa fa-facebook" aria-hidden="true"></i>
                    </a>
                </div>

                <div class="col-xs-12 col-md-4">
                    <ul>
                        <li><i class="fa fa-gavel" aria-hidden="true"></i> <a href="#">{{__('pages/index.tos')}}</a></li>
                        <li><i class="fa fa-user" aria-hidden="true"></i> <a href="#">{{__('pages/index.pp')}}</a></li>
                        <li><i class="fa fa-info" aria-hidden="true"></i> <a href="#">{{__('pages/index.cp')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(isset($config_footer_map) && $config_footer_map === 1)
        <div id="googlemap"></div>
        <script>
            function initMap() {
                var uluru = {lat: 54.521631, lng: 18.532266};
                var map = new google.maps.Map(document.getElementById('googlemap'), {
                    zoom: 17,
                    center: uluru,
                    scrollwheel: false,
                    navigationControl: false,
                    mapTypeControl: false,
                    scaleControl: false,
                    draggable: false,
                });
                var marker = new google.maps.Marker({
                    position: uluru,
                    map: map
                });
                google.maps.event.addDomListener(window, 'resize', function() {
                    setTimeout(function(){ map.setCenter(uluru); }, 500);
                });
            }
        </script>

        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAjP1K4GmoL1ws4ADFu7RGApVXOFEJpmKY&callback=initMap">
        </script>
    @endif
</footer>