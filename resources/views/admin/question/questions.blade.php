@extends('base.base')

@section('title')
    Show questions
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 spacing-top">
            <input type="text" class="form-control" id="search-input" placeholder="Search...">
        </div>
    </div>

    <div class="col-lg-12 spacing-top">
        @if($questions->count() == 0)
            <div class="row">
                <h2 class="display-5 text-center text-muted">No images found</h2>
            </div>
        @else
            @foreach($questions as $question)
                <div class="spacing-top ">
                    <div class="row question-border ">
                        <div class="col-lg-12">
                            <h3><a href="{{ route('questionEdit', ['id' => $question->id]) }}">{{ $question->title }}</a></h3>
                        </div>
                        @if($question->images()->count() == 1)
                            @foreach($question->images as $image)
                                <div class="col-lg-6">
                                    {{ $question->body }}
                                </div>
                                <div class="col-lg-6">
                                    <img class="img-fluid fit-image" src="{{ asset('img') . '/' . $image->alias}}">
                                </div>
                            @endforeach
                        @elseif($question->images()->count() == 2)
                            <div class="col-lg-12">
                                {{ $question->body }}
                            </div>
                            @foreach($question->images as $image)
                                <div class="col-lg-6">
                                    <img class="img-fluid fit-image" src="{{ asset('img') . '/' . $image->alias}}">
                                </div>
                            @endforeach
                        @else
                            <div class="col-lg-12">
                                {{ $question->body }}
                            </div>
                            @foreach($question->images as $image)
                                <div class="col-lg-6">
                                    <img class="img-fluid fit-image" src="{{ url(Config::get('image.display_path') . $image->alias) }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="row spacing-top">
                <div class="col-lg-12 pagination pagination-centered justify-content-center">
                    {{ $questions->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
<input type="hidden" value="{{ route('searchQuestions') }}" id="api-route">

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-typeahead.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/question-search.js') }}"></script>

@endsection