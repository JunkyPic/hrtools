@extends('front.base')

@section('title')
    {{$test->name}}
@endsection

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('content')
    <div class="row text-center">
        <div class="col-lg-12">
            <h1>{{$test->name}}</h1>
            <hr>
        </div>
    </div>
    <form method="POST" action="{{ route('postEndTest') }}" id="form-submit">
        <input type="hidden" name="test_id" value="{{ $test->id }}">
        @csrf
        @foreach($test->chapters as $chapter)
            <div class="row padding-30 margin-top-10 text-center">
                <div class="col-lg-12">
                    <h2>{{ $chapter->chapter }}</h2>
                </div>
            </div>
            @foreach($chapter->questions as $question)
                <div class="row question-border padding-30 margin-top-10">
                    <div class="col-lg-12">
                        <h3>{{ $question->title }}</h3>
                    </div>
                    <div class="col-lg-12">
                        <p>{!! $question->body  !!}</p>
                    </div>
                    @if($question->images->count() >= 1)
                        @foreach($question->images as $image)
                            <div class="col-lg-12">
                                <a href="{{ url(Config::get('image.display_path') . $image->alias)}}" target="_blank"><img class="img-fluid "
                                     src="{{ url(Config::get('image.display_path') . $image->alias)}}"></a>
                            </div>
                        @endforeach
                    @endif
                    <div class="col-lg-12 spacing-top-30 text-center">
                        <hr>
                        <label for="qa_{{ $question->id }}"><strong>Answer</strong></label>
                        <textarea name="answers[{{ $question->id }}]" class="form-control" style="min-width: 100%" rows="10" id="qa_{{ $question->id }}"></textarea>
                    </div>
                </div>
            @endforeach
        @endforeach
            <div class="row justify-content-center">
                <div class="col-md-12 spacing-top">
                    <div class="form-group row mb-0">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-success btn-lg btn-block waves-effect waves-light" data-toggle="modal" data-target="#submitModal">
                                {{ __('Submit test') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Are you sure you want to finish the test?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Once the test is submitted you won't be able to return to it</p>
                            <p>Make sure you've answered all the questions you can answer</p>
                        </div>
                        <div class="modal-footer">
                                <a class="btn btn-success" onclick="$('#form-submit').submit()">
                                    {{ __('Yes, I\'m sure') }}
                                </a>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <input type="hidden" name="test_token" value="{{ $test_token }}">
        <input type="hidden" id="route" value="{{ route('testValidateDuration', ['t' => $test_token]) }}">
    </form>

    <div class="container-fluid">
        <footer class="fixed-bottom bg-light">
            <p class="text-center"><span id="timer-countdown"></span></p>
        </footer>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
		function validate(route) {
			$.ajax({
				url: route,
				cache: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function(data){
					if(data.hasOwnProperty('status') && data.status === 'TEST_FINISHED') {
						$('#form-submit').submit();
						$('#form-submit').remove();
					}
				}
			});
		}

		var route = $('#route').val();

		setInterval(function(){validate(route);}, 10000);

		if(null ===  window.localStorage.getItem("start_time_{{$test_token}}")) {
			window.localStorage.setItem("start_time_{{$test_token}}", "{{$start_time}}");
			var start_time = "{{$start_time}}";
		} else {
			var start_time = window.localStorage.getItem("start_time_{{$test_token}}");
		}

		start_time = new Date(start_time * 1000);

		var x = setInterval(function() {
			var now = new Date().getTime();

			var distance = start_time - now;
			var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			var seconds = Math.floor((distance % (1000 * 60)) / 1000);

			if(minutes <= 10) {

            }

			if(hours === 0) {
				document.getElementById("timer-countdown").innerHTML =  "Time left " + minutes + "m " + seconds + "s ";
			} else {
				document.getElementById("timer-countdown").innerHTML =  "Time left " + hours + "h " + minutes + "m " + seconds + "s ";
			}

			if (distance < 0) {
				clearInterval(x);
				document.getElementById("timer-countdown").innerHTML = "EXPIRED";
			}
		}, 1000);

    </script>
@endsection
