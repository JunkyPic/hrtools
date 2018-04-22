<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ URL::asset('css/cosmo.min.css') }}" rel="stylesheet">
    <title>Pre Start</title>
</head>
<body>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10 offset-md-2">
            <p>Hello {{ $candidate_name }},</p>
            <p>You are about to take the {{ $test_name }} test.</p>
            <p>Allowed time: {{ $test_total_time }} minutes.</p>
            <p>Please read the following instructions carefully and make sure you understand them</p>
            <p>Once you've read and understood them, check the box bellow to continue</p>
            <p>Once the start button is pressed the test will start and the countdown will begin.</p>
            <p>Make sure you're ready before pressing the start button.</p>
            <p>Make sure you're ready before pressing the start button.</p>
            <p>Also make sure that javascript is enabled in your browser before starting the test, else the test won't continue.</p>
            <p></p>
            <p>Good luck!</p>
        </div>

        <div class="col-md-10 offset-md-2">
            <div class="custom-control custom-checkbox" id="instructions-hide">
                <input type="checkbox" class="custom-control-input" id="instructions" onclick="check()">
                <label class="custom-control-label" for="instructions">I have read and understood the instructions</label>
            </div>
            <div class="col-md-12" style="display: none" id="start">
                <a href="{{ route('postStartTest', ['t' => $t]) }}" class="btn btn-primary btn-lg">
                    {{ __('Start') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.cookie = "jsen=set";
    function check() {
        if(document.getElementById("instructions").checked) {
            var x = document.getElementById("start");
            if (x.style.display === "none") {
                x.style.display = "block";
            }
            document.getElementById("instructions-hide").style.display = "none";
        }
    }
</script>
</body>
</html>