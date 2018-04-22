<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ URL::asset('css/cosmo.min.css') }}" rel="stylesheet">
    @if(isset($title))
        <title>{{$title}}</title>
    @else
        <title>Invalid</title>
    @endif
</head>
<body>
<div class="container">
    @if(isset($token_not_present))
        <div class="jumbotron text-center">
            <h1 class="display-3 text-center">Invalid token</h1>
            <p class="lead">Whoops, it looks like the token is invalid, not present or it has expired. </p>
        </div>
    @elseif(isset($error))
        <div class="jumbotron text-center">
            <h1 class="display-6 text-center">{{ $error }}</h1>
            @if(isset($link))
                <p class="lead">Follow <a href="{{$link}}">this</a> link to return to it</p>
            @endif
        </div>
    @endif
</div>
</body>
</html>