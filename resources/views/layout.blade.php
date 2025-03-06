<!DOCTYPE html>
<html lang="en">

<head>
    <title>RENTEASY</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,900|Playfair+Display:400,700,900 " rel="stylesheet">
    <!-- <link rel="stylesheet" href="fonts/icomoon/style.css"> -->
    <link rel="stylesheet" href="{{asset('template/fonts/icomoon/style.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/bootstrap.min.css')}}">

    <link rel="stylesheet" href="{{asset('template/css/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('template/css/owl.theme.default.min.css')}}">

    <link rel="stylesheet" href="{{asset('template/css/jquery.fancybox.min.css')}}">

    <link rel="stylesheet" href="{{asset('template/css/bootstrap-datepicker.css')}}">

    <link rel="stylesheet" href="{{asset('template/fonts/flaticon/font/flaticon.css')}}">

    <link rel="stylesheet" href="{{asset('template/css/aos.css')}}">

    <link rel="stylesheet" href="{{asset('template/css/style.css')}}">

</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    <div class="site-wrap">

        <div class="site-mobile-menu site-navbar-target">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close mt-3">
                    <span class="icon-close2 js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>

        <!-- header -->
        @include('components.header')
        <!-- header -->

        <!-- contenu de la page -->
        @yield('content')
        <!-- contenu de la page -->


        <!-- footer -->
        @include('components.footer')
        <!-- footer -->


    </div> <!-- .site-wrap -->

    <script src="{{asset('template/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('template/js/jquery-migrate-3.0.1.min.js')}}"></script>
    <script src="{{asset('template/js/jquery-ui.js')}}"></script>
    <script src="{{asset('template/js/popper.min.js')}}"></script>
    <script src="{{asset('template/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('template/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('template/js/jquery.stellar.min.js')}}"></script>
    <script src="{{asset('template/js/jquery.countdown.min.js')}}"></script>
    <script src="{{asset('template/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('template/js/jquery.easing.1.3.js')}}"></script>
    <script src="{{asset('template/js/aos.js')}}"></script>
    <script src="{{asset('template/js/jquery.fancybox.min.js')}}"></script>
    <script src="{{asset('template/js/jquery.sticky.js')}}"></script>

    <script src="{{asset('template/js/main.js')}}"></script>

</body>

</html>