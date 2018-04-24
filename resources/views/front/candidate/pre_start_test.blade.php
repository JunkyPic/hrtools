@extends('front.base')

@section('title')
    Instructions
@endsection

@section('header')
    @if(isset($editable_area))
        {!! $editable_area->header !!}
    @endif
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 offset-md-2">
        @if(isset($editable_area))
            {!! $editable_area->instructions !!}
        @endif
        <p>Information</p>
        <p>Test name - {{ $test_name }}</p>
        <p>Allowed time - {{ $test_total_time }} minutes</p>
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
@endsection

@section('footer')
    @if(isset($editable_area))
        {!! $editable_area->footer !!}
    @endif
@endsection

@section('scripts')

@endsection
