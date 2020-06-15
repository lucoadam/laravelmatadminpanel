<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME','Admin Panel') }}</title>
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('material') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('material') }}/img/favicon.png">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
        <!--     Fonts and icons     -->
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <!-- CSS Files -->
        <link href="{{ asset('material-dashboard-pro')}}/assets/css/material-dashboard.min.css?v=2.1.1" rel="stylesheet">
        <!-- Documentation extras -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/docsearch.js/2/docsearch.min.css">
        <link rel="stylesheet" href="{{ asset('material-dashboard-pro')}}/assets/demo/docs.min.css">
        <style>
            .navbar-absolute-logo {
                padding-left: 45px;
            }

            .navbar-absolute-logo img {
                position: absolute;
                left: 15px;
                margin-top: -6px;
            }

            body {
                background: white;
            }
        </style>
        <!-- CSS Just for demo purpose, don't include it in your project -->
        <link href="{{ asset('material-dashboard-pro')}}/assets/demo/demo.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/material-components-web/4.0.0/material-components-web.min.css" rel="stylesheet">
        @yield('after-style')
        {{--        <link href="https://cdn.datatables.net/1.10.21/css/dataTables.material.min.css" rel="stylesheet">--}}
{{--    <!--     Fonts and icons     -->--}}
{{--    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />--}}
{{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">--}}
{{--    <!-- CSS Files -->--}}
{{--    <link href="{{ asset('material') }}/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />--}}
{{--    <!-- CSS Just for demo purpose, don't include it in your project -->--}}
{{--    <link href="{{ asset('material') }}/demo/demo.css" rel="stylesheet" />--}}
    </head>
    <body class="{{ $class ?? '' }}">
        @auth()
            @if(isset($welcome)&&$welcome)
                @include('layouts.page_templates.guest')
            @else
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                @include('layouts.page_templates.auth')
            @endif
        @endauth
        @guest()
            @include('layouts.page_templates.guest')
        @endguest
{{--        <div class="fixed-plugin">--}}
{{--          <div class="dropdown show-dropdown">--}}
{{--            <a href="#" data-toggle="dropdown">--}}
{{--              <i class="fa fa-cog fa-2x"> </i>--}}
{{--            </a>--}}
{{--            <ul class="dropdown-menu">--}}
{{--              <li class="header-title"> Sidebar Filters</li>--}}
{{--              <li class="adjustments-line">--}}
{{--                <a href="javascript:void(0)" class="switch-trigger active-color">--}}
{{--                  <div class="badge-colors ml-auto mr-auto">--}}
{{--                    <span class="badge filter badge-purple " data-color="purple"></span>--}}
{{--                    <span class="badge filter badge-azure" data-color="azure"></span>--}}
{{--                    <span class="badge filter badge-green" data-color="green"></span>--}}
{{--                    <span class="badge filter badge-warning active" data-color="orange"></span>--}}
{{--                    <span class="badge filter badge-danger" data-color="danger"></span>--}}
{{--                    <span class="badge filter badge-rose" data-color="rose"></span>--}}
{{--                  </div>--}}
{{--                  <div class="clearfix"></div>--}}
{{--                </a>--}}
{{--              </li>--}}
{{--              <li class="header-title">Images</li>--}}
{{--              <li class="active">--}}
{{--                <a class="img-holder switch-trigger" href="javascript:void(0)">--}}
{{--                  <img src="{{ asset('material') }}/img/sidebar-1.jpg" alt="">--}}
{{--                </a>--}}
{{--              </li>--}}
{{--              <li>--}}
{{--                <a class="img-holder switch-trigger" href="javascript:void(0)">--}}
{{--                  <img src="{{ asset('material') }}/img/sidebar-2.jpg" alt="">--}}
{{--                </a>--}}
{{--              </li>--}}
{{--              <li>--}}
{{--                <a class="img-holder switch-trigger" href="javascript:void(0)">--}}
{{--                  <img src="{{ asset('material') }}/img/sidebar-3.jpg" alt="">--}}
{{--                </a>--}}
{{--              </li>--}}
{{--              <li>--}}
{{--                <a class="img-holder switch-trigger" href="javascript:void(0)">--}}
{{--                  <img src="{{ asset('material') }}/img/sidebar-4.jpg" alt="">--}}
{{--                </a>--}}
{{--              </li>--}}
{{--              <li class="button-container">--}}
{{--                <a href="https://www.creative-tim.com/product/material-dashboard-laravel" target="_blank" class="btn btn-primary btn-block">Free Download</a>--}}
{{--              </li>--}}
{{--              <!-- <li class="header-title">Want more components?</li>--}}
{{--                  <li class="button-container">--}}
{{--                      <a href="https://www.creative-tim.com/product/material-dashboard-pro" target="_blank" class="btn btn-warning btn-block">--}}
{{--                        Get the pro version--}}
{{--                      </a>--}}
{{--                  </li> -->--}}
{{--              <li class="button-container">--}}
{{--                <a href="https://material-dashboard-laravel.creative-tim.com/docs/getting-started/laravel-setup.html" target="_blank" class="btn btn-default btn-block">--}}
{{--                  View Documentation--}}
{{--                </a>--}}
{{--              </li>--}}
{{--              <li class="button-container">--}}
{{--                <a href="https://www.creative-tim.com/product/material-dashboard-pro-laravel" target="_blank" class="btn btn-danger btn-block btn-round">--}}
{{--                  Upgrade to PRO--}}
{{--                </a>--}}
{{--              </li>--}}
{{--              <li class="button-container github-star">--}}
{{--                <a class="github-button" href="https://github.com/creativetimofficial/material-dashboard-laravel" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star ntkme/github-buttons on GitHub">Star</a>--}}
{{--              </li>--}}
{{--              <li class="header-title">Thank you for 95 shares!</li>--}}
{{--              <li class="button-container text-center">--}}
{{--                <button id="twitter" class="btn btn-round btn-twitter"><i class="fa fa-twitter"></i> &middot; 45</button>--}}
{{--                <button id="facebook" class="btn btn-round btn-facebook"><i class="fa fa-facebook-f"></i> &middot; 50</button>--}}
{{--                <br>--}}
{{--                <br>--}}
{{--              </li>--}}
{{--            </ul>--}}
{{--          </div>--}}
{{--        </div>--}}
        <!--   Core JS Files   -->
{{--        <script src="{{ asset('material') }}/js/core/jquery.min.js"></script>--}}
{{--        <script src="{{ asset('material') }}/js/core/popper.min.js"></script>--}}
{{--        <script src="{{ asset('material') }}/js/core/bootstrap-material-design.min.js"></script>--}}
{{--        <script src="{{ asset('material') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>--}}
{{--        <!-- Plugin for the momentJs  -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/moment.min.js"></script>--}}
{{--        <!--  Plugin for Sweet Alert -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/sweetalert2.js"></script>--}}
{{--        <!-- Forms Validations Plugin -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/jquery.validate.min.js"></script>--}}
{{--        <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/jquery.bootstrap-wizard.js"></script>--}}
{{--        <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/bootstrap-selectpicker.js"></script>--}}
{{--        <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/bootstrap-datetimepicker.min.js"></script>--}}
{{--        <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>--}}
{{--        <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/bootstrap-tagsinput.js"></script>--}}
{{--        <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/jasny-bootstrap.min.js"></script>--}}
{{--        <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/fullcalendar.min.js"></script>--}}
{{--        <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/jquery-jvectormap.js"></script>--}}
{{--        <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/nouislider.min.js"></script>--}}
{{--        <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->--}}
{{--        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>--}}
{{--        <!-- Library for adding dinamically elements -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/arrive.min.js"></script>--}}
{{--        <!--  Google Maps Plugin    -->--}}
{{--        <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE'"></script>--}}
{{--        <!-- Chartist JS -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/chartist.min.js"></script>--}}
{{--        <!--  Notifications Plugin    -->--}}
{{--        <script src="{{ asset('material') }}/js/plugins/bootstrap-notify.js"></script>--}}
{{--        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->--}}
{{--        <script src="{{ asset('material') }}/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>--}}
{{--        <!-- Material Dashboard DEMO methods, don't include it in your project! -->--}}
{{--        <script src="{{ asset('material') }}/demo/demo.js"></script>--}}
{{--        <script src="{{ asset('material') }}/js/settings.js"></script>--}}
        <!--   Core JS Files   -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/core/jquery.min.js"></script>
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/core/popper.min.js"></script>
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/core/bootstrap-material-design.min.js"></script>
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
        <!-- Plugin for the momentJs  -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/moment.min.js"></script>
        <!--  Plugin for Sweet Alert -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/sweetalert2.js"></script>
        <!-- Forms Validations Plugin -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/jquery.validate.min.js"></script>
        <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/jquery.bootstrap-wizard.js"></script>
        <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/bootstrap-selectpicker.js"></script>
        <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
        <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/jquery.dataTables.min.js"></script>
        <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/bootstrap-tagsinput.js"></script>
        <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/jasny-bootstrap.min.js"></script>
        <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/fullcalendar.min.js"></script>
        <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/jquery-jvectormap.js"></script><style>
            #ofBar {
                background: #de2e2e;
                text-align: left;
                min-height: 60px;
                z-index: 999999999;
                font-size: 16px;
                color: #fff;
                padding: 18px 5%;
                font-weight: 400;
                display: block;
                position: relative;
                top: 0px;
                width: 100%;
                box-shadow: 0 6px 13px -4px rgba(0, 0, 0, 0.25);
            }
            #ofBar b {
                font-size: 15px !important;
            }
            #count-down {
                display: initial;
                padding-left: 10px;
                font-weight: bold;
            }
            #close-bar {
                font-size: 22px;
                color: #3e3947;
                margin-right: 0;
                position: absolute;
                right: 5%;
                background: white;
                opacity: 0.5;
                padding: 0px;
                height: 25px;
                line-height: 21px;
                width: 25px;
                border-radius: 50%;
                text-align: center;
                top: 18px;
                cursor: pointer;
                z-index: 9999999999;
                font-weight: 200;
            }
            #close-bar:hover{
                opacity: 1;
            }
            #btn-bar {
                background-color: #fff;
                color: #40312d;
                border-radius: 4px;
                padding: 10px 20px;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 12px;
                opacity: .95;
                margin-left: 15px;
                top: 0px;
                position: relative;
                cursor: pointer;
                text-align: center;
                box-shadow: 0 5px 10px -3px rgba(0,0,0,.23), 0 6px 10px -5px rgba(0,0,0,.25);
            }
            #btn-bar:hover{
                opacity: 0.9;
            }
            #btn-bar{
                opacity: 1;
            }

            #btn-bar span {
                color: red;
            }
            .right-side{
                float: right;
                margin-right: 60px;
                top: -6px;
                position: relative;
                display: block;
            }

            #oldPriceBar {
                text-decoration: line-through;
                font-size: 16px;
                color: #fff;
                font-weight: 400;
                top: 2px;
                position: relative;
            }
            #newPrice{
                color: #fff;
                font-size: 19px;
                font-weight: 700;
                top: 2px;
                position: relative;
                margin-left: 7px;
            }

            #fromText {
                font-size: 15px;
                color: #fff;
                font-weight: 400;
                margin-right: 3px;
                top: 0px;
                position: relative;
            }

            @media(max-width: 991px){
                .right-side{
                    float:none;
                    margin-right: 0px;
                    margin-top: 5px;
                    top: 0px
                }
                #ofBar {
                    padding: 50px 20px 20px;
                    text-align: center;
                }
                #btn-bar{
                    display: block;
                    margin-top: 10px;
                    margin-left: 0;
                }
            }
            @media (max-width: 768px) {
                #count-down {
                    display: block;
                    font-size: 25px;
                }
            }

            @media  only screen and (max-device-width: 720px) {
                #dataTable thead tr,#dataTable tbody tr{
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }
            }
        </style>


        <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/nouislider.min.js"></script>
        <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <!-- Library for adding dinamically elements -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/arrive.min.js"></script>
        <!--  Google Maps Plugin    -->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB2Yno10-YTnLjjn_Vtk0V8cdcY5lC4plU"></script>
        <!-- Place this tag in your head or just before your close body tag. -->
        <script async="" defer="" src="https://buttons.github.io/buttons.js"></script>
        <!-- Chartist JS -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/chartist.min.js"></script>
        <!--  Notifications Plugin    -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/plugins/bootstrap-notify.js"></script>
        <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/js/material-dashboard.min.js?v=2.1.1" type="text/javascript">

        </script>
        <!-- Sharrre libray -->
        <script src="{{ asset('material-dashboard-pro')}}/assets/demo/jquery.sharrre.js"></script>
        <script>
            $(document).ready(function() {


                $('#facebook').sharrre({
                    share: {
                        facebook: true
                    },
                    enableHover: false,
                    enableTracking: false,
                    enableCounter: false,
                    click: function(api, options) {
                        api.simulateClick();
                        api.openPopup('facebook');
                    },
                    template: '<i class="fab fa-facebook-f"></i> Facebook',
                    url: 'https://demos.creative-tim.com/material-dashboard-pro/examples/dashboard.html'
                });

                $('#google').sharrre({
                    share: {
                        googlePlus: true
                    },
                    enableCounter: false,
                    enableHover: false,
                    enableTracking: true,
                    click: function(api, options) {
                        api.simulateClick();
                        api.openPopup('googlePlus');
                    },
                    template: '<i class="fab fa-google-plus"></i> Google',
                    url: 'https://demos.creative-tim.com/material-dashboard-pro/examples/dashboard.html'
                });

                $('#twitter').sharrre({
                    share: {
                        twitter: true
                    },
                    enableHover: false,
                    enableTracking: false,
                    enableCounter: false,
                    buttons: {
                        twitter: {
                            via: 'CreativeTim'
                        }
                    },
                    click: function(api, options) {
                        api.simulateClick();
                        api.openPopup('twitter');
                    },
                    template: '<i class="fab fa-twitter"></i> Twitter',
                    url: 'https://demos.creative-tim.com/material-dashboard-pro/examples/dashboard.html'
                });


                // Facebook Pixel Code Don't Delete
                ! function(f, b, e, v, n, t, s) {
                    if (f.fbq) return;
                    n = f.fbq = function() {
                        n.callMethod ?
                            n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq) f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window,
                    document, 'script', '//connect.facebook.net/en_US/fbevents.js');

                try {
                    fbq('init', '111649226022273');
                    fbq('track', "PageView");

                } catch (err) {
                    console.log('Facebook Track Error:', err);
                }

            });
        </script>
        <script>
            // Facebook Pixel Code Don't Delete
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window,
                document, 'script', '//connect.facebook.net/en_US/fbevents.js');

            try {
                fbq('init', '111649226022273');
                fbq('track', "PageView");

            } catch (err) {
                console.log('Facebook Track Error:', err);
            }
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=111649226022273&ev=PageView&noscript=1" />
        </noscript>
        <script>
            $(document).ready(function() {
                if ($('.card-header.card-chart').length != 0) {
                    md.initDashboardPageCharts();
                }
                if ($('#websiteViewsChart').length != 0) {
                    md.initDocumentationCharts();
                }
                if ($('.datetimepicker').length != 0) {
                    md.initFormExtendedDatetimepickers();
                }
                if ($('#fullCalendar').length != 0) {
                    md.initFullCalendar();
                }

                if ($('.slider').length != 0) {
                    md.initSliders();
                }

                //  Activate the tooltips
                $('[data-toggle="tooltip"]').tooltip();

                // Activate Popovers
                $('[data-toggle="popover"]').popover();

                // Vector map
                if ($('#worldMap').length != 0) {
                    md.initVectorMap();
                }

                if ($('#RegisterValidation').length != 0) {

                    setFormValidation('#RegisterValidation');

                    function setFormValidation(id) {
                        $(id).validate({
                            highlight: function(element) {
                                $(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
                                $(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
                            },
                            success: function(element) {
                                $(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
                                $(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
                            },
                            errorPlacement: function(error, element) {
                                $(element).closest('.form-group').append(error);
                            },
                        });
                    }
                }

            });

            // FileInput
            $('.form-file-simple .inputFileVisible').click(function() {
                $(this).siblings('.inputFileHidden').trigger('click');
            });

            $('.form-file-simple .inputFileHidden').change(function() {
                var filename = $(this).val().replace(/C:\\fakepath\\/i, '');
                $(this).siblings('.inputFileVisible').val(filename);
            });

            $('.form-file-multiple .inputFileVisible, .form-file-multiple .input-group-btn').click(function() {
                $(this).parent().parent().find('.inputFileHidden').trigger('click');
                $(this).parent().parent().addClass('is-focused');
            });

            $('.form-file-multiple .inputFileHidden').change(function() {
                var names = '';
                for (var i = 0; i < $(this).get(0).files.length; ++i) {
                    if (i < $(this).get(0).files.length - 1) {
                        names += $(this).get(0).files.item(i).name + ',';
                    } else {
                        names += $(this).get(0).files.item(i).name;
                    }
                }
                $(this).siblings('.input-group').find('.inputFileVisible').val(names);
            });

            $('.form-file-multiple .btn').on('focus', function() {
                $(this).parent().siblings().trigger('focus');
            });

            $('.form-file-multiple .btn').on('focusout', function() {
                $(this).parent().siblings().trigger('focusout');
            });
        </script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.material.min.js"></script>
        @stack('js')
        @yield('after-script')
    </body>

</html>
