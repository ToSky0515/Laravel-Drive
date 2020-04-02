<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    
    <link href="{{ asset('css/font/flaticon.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script>
        window.LD = @json([
            'csftToken' => csrf_token(),
            'user'  => $user,
        ]);
    </script>

</head>
<body>

    <div class="laraadmin rootouter">
        <div id="root"></div>
    </div>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>

    <!-- Scripts -->
    <script src="{{ asset('js/config.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
</body>
</html>