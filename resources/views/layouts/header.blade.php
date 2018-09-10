<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Scrape comment Amazon</title>

    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="{{asset('js/vendor.js')}}"></script>
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
        @yield('main-content')
    </div>
</div>
</body>
@section('scripts')
    @yield('additional-script')
@show
</html>
