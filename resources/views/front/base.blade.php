<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('meta')

    @if(isset($theme))
        @yield('css')
    @else
        <link href="{{ URL::asset('css/cosmo.min.css') }}" rel="stylesheet">
    @endif
    <link href="{{ URL::asset('css/test-css.css') }}" rel="stylesheet">
    <title>@yield('title')</title>
</head>
<body>

<div class="container">
    @yield('header')
    <div class="padding-20"></div>
    @yield('content')
    @yield('footer')

</div>

<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/jquery-countdown.min.js') }}"></script>
@yield('scripts')
</body>
</html>
