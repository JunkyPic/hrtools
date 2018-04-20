@extends('base.base')

@section('title')
    Show questions
@endsection

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top text-center">
            @if(session('message'))
                <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>{{ session('message') }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 spacing-top">
            <input type="text" class="form-control" id="search-input" placeholder="Search...">
        </div>
    </div>

    @if($questions->count() == 0)
        <div class="col-lg-12 ">
            <h2 class="display-5 text-muted  text-center">No questions found, <a href="{{ route('questionCreate') }}">add
                    some</a></h2>
        </div>
    @else
        @foreach($questions as $question)
            <div class="question-border margin-top-10 padding-10">
                <div class="spacing-top row">
                    <div class="col-lg-12">
                        <h3><a href="{{ route('questionEdit', ['id' => $question->id]) }}">{{ $question->title }}</a>
                        </h3>
                    </div>
                    <div class="col-lg-12">
                        {{ $question->body }}
                    </div>
                </div>
                <div class="spacing-top row">
                    @foreach($question->images as $image)
                        <div class="col-lg-4">
                            <img class="img-fluid img-thumbnail"
                                 src="{{ url(Config::get('image.display_path') . $image->alias)}}">
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
        <div class="row spacing-top">
            <div class="col-lg-12 pagination pagination-centered justify-content-center">
                {{ $questions->links() }}
            </div>
        </div>
    @endif
@endsection
<input type="hidden" value="{{ route('searchQuestions') }}" id="api-route">

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-typeahead.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/question-search.js') }}"></script>

@endsection