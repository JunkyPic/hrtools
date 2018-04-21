@extends('base.base')

@section('title')
    Show questions
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row">
        <div class="col-lg-12 spacing-top">
            <form method="get" action="{{ route('questionsAll') }}" class="form-inline">
                <label class="mr-sm-2" for="tags">Tags</label>
                <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="tags" name="tag">
                    <option selected value="NA">Choose...</option>
                    @foreach($tags as $t)
                        @if($tag == $t->tag)
                            <option value="{{ $t->tag }}" selected>{{ $t->tag }}</option>
                        @else
                            <option value="{{ $t->tag }}">{{ $t->tag }}</option>
                        @endif
                    @endforeach
                </select>
                <label class="mr-sm-2" for="inlineFormCustomSelect">Order</label>
                <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="inlineFormCustomSelect" name="order">
                    <option selected value="NA" @if($order == 'NAN') selected @endif>Choose...</option>
                    <option value="DESC" @if($order == 'DESC') selected @endif>Newest First</option>
                    <option value="ASC" @if($order == 'ASC') selected @endif>Oldest First</option>
                </select>

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
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
                        @if(strlen($question->body) > 200)
                            {{ substr($question->body, 0, 200) }}...
                        @else
                            {{ $question->body }}
                        @endif
                    </div>
                </div>
                <div class="spacing-top row">
                    @foreach($question->tags as $tag)
                        <div class="col-md-1 text-center">
                            <a href="{{ route('questionsTaggedWith', ['tag' => $tag->tag]) }}"><span class="badge badge-primary">{{ $tag->tag }}</span></a>
                        </div>
                    @endforeach
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
                @if(\Illuminate\Support\Facades\Input::get('tag'))
                    {{ $questions->appends(['tag' => \Illuminate\Support\Facades\Input::get('tag'), 'order' => $order])->links() }}
                @else
                    {{ $questions->appends(['order' => $order])->links() }}
                @endif
            </div>
        </div>
    @endif
@endsection
<input type="hidden" value="{{ route('searchQuestions') }}" id="api-route">

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-typeahead.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/question-search.js') }}"></script>
@endsection