<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ URL::asset('css/cosmo.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/simple-sidebar.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/css.css') }}" rel="stylesheet">
    @yield('meta')
    @yield('css')
    <title>@yield('title')</title>
</head>
<body>
@include('navbar')
<div class="container">
    @yield('content')
</div>
<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>

@yield('scripts')

<!-- Menu Toggle Script -->
<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
</script>
</body>
</html>