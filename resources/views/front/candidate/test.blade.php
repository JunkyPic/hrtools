<!doctype html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ URL::asset('css/cosmo.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/test-css.css') }}" rel="stylesheet">
    <title>{{$test->name}}</title>
</head>
<body>
<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>

<div class="container padding-40">
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
                        <textarea name="answers[{{ $question->id }}]" class="form-control" style="min-width: 100%" rows="10" id="qa_{{ $question->id }}">test</textarea>
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
</div>
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
</script>
</body>
</html>