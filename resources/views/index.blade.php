<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="fav.ico/favicon-16x16.png">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}">
</head>
<body class="hold-transition sidebar-mini skin-blue full-view">
    <div class="wrapper">
        <div id="app"></div>
    </div>
    <script src="{{ asset('/js/app.js') }}"></script>
</body>
</html>
