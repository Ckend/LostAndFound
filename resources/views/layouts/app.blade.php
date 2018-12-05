<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>华南师范大学 - 失物招领系统</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script>
        (function () {
            var url = location.href;
            // replace www.test.com with your domain
            if ( (url.indexOf('laf.alltoshare.com') != -1) && navigator.userAgent.match(/(iPhone|iPod|Android|ios|iPad)/i) ) {
                location.href = 'https://laf.alltoshare.com/phone';
            }
        })();
    </script>

</head>
<body>
    <div id="app">
        @yield('content')
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
