@extends('base.base')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('title')
    Questions
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
                    <option value="NA">Choose...</option>
                    @foreach($tags as $t)
                        @if($tag == $t->tag)
                            <option value="{{ $t->tag }}" selected>{{ $t->tag }}</option>
                        @else
                            <option value="{{ $t->tag }}">{{ $t->tag }}</option>
                        @endif
                    @endforeach
                </select>

                <label class="mr-sm-2" for="chapter">Chapters</label>
                <select class="custom-select mb-2 mr-sm-2 mb-sm-0" id="chapter" name="chapter">
                    <option value="NA">Choose...</option>
                    @foreach($chapters as $c)
                        @if($chapter == $c->chapter)
                            <option value="{{ $c->chapter }}" selected>{{ $c->chapter }}</option>
                        @else
                            <option value="{{ $c->chapter  }}">{{ $c->chapter }}</option>
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
                <div class="row">
                    <div class="col-lg-12">
                        <h3><a href="{{ route('questionEdit', ['id' => $question->id]) }}">{{ $question->title }}</a>
                        </h3>
                    </div>
                    <div class="col-lg-8">
                        @if(strlen($question->body) > 200)
                            {{ substr($question->body, 0, 200) }}...
                        @else
                            {{ $question->body }}
                        @endif
                    </div>
                    @if($chapters->count() >= 1)
                        <div class="col-lg-2">
                            <label class="mr-sm-2"><small class="text-muted">Associate to chapter</small>
                                <select class="custom-select mb-2 mr-sm-2 mb-sm-0 associate-question" name="order">
                                    <option selected data-qid="NA" value="NA">Choose...</option>
                                    @foreach($chapters as $chapter)
                                        <option data-qid="{{ $question->id }}"
                                                value="{{ $chapter->id }}">{{ $chapter->chapter }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    @endif

                    @if($chapters->count() >= 1)
                        <div class="col-lg-2">
                            <label class="mr-sm-2"><small class="text-muted">Remove from chapter</small>
                                <select class="custom-select mb-2 mr-sm-2 mb-sm-0 remove-question" name="order">
                                    <option selected data-qid="NA" value="NA">Choose...</option>
                                    @foreach($chapters as $chapter)
                                        <option data-qid="{{ $question->id }}"
                                                value="{{ $chapter->id }}">{{ $chapter->chapter }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    @endif
                </div>

                <div class="spacing-top row">
                    <div class="col-md-12">
                    <ul class="list-inline">

                        <li class="list-inline-item">
                            <a href="#" onclick="return false;">
                                <span class="badge badge-warning badge-pill">Tags @if($question->tags->count() >= 1) - {{ $question->tags->count() }} @endif</span>
                            </a>
                        </li>
                        @if($question->tags->count() >= 1)
                            @foreach($question->tags as $tag)
                                <li class="list-inline-item">
                                    <a href="{{ route('questionsTaggedWith', ['tag' => $tag->tag]) }}"><span
                                                class="badge badge-primary">{{ $tag->tag }}</span></a>
                                </li>
                            @endforeach
                        @else
                            <li class="list-inline-item">
                                <span class="badge badge-primary badge-pill">{{ $question->tags->count() }}</span>
                            </li>
                        @endif
                    </ul>
                    </div>
                </div>

                <div class="spacing-top row">
                    <div class="col-md-12">
                    <ul class="list-inline">
                        <li class="list-inline-item">
                            <a href="#" onclick="return false;">
                                <span class="badge badge-warning badge-pill">In chapters @if($question->chapters->count() >= 1) - {{ $question->chapters->count() }} @endif</span>
                            </a>
                        </li>

                    @if($question->chapters->count() >= 1)
                        @foreach($question->chapters as $chapter)
                            <li class="list-inline-item">
                                <a href="{{ route('questionsByChapters', ['chapter' => $chapter->chapter]) }}"><span class="badge badge-primary">{{ $chapter->chapter }}</span></a>
                            </li>
                        @endforeach
                    @else
                        <li class="list-inline-item">
                            <span class="badge badge-primary badge-pill">{{ $question->chapters->count() }}</span>
                        </li>
                    @endif
                    </ul>
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
                {{ $links->links() }}
            </div>
        </div>
    @endif
@endsection
<input type="hidden" value="{{ route('searchQuestions') }}" id="api-route">
<input type="hidden" value="{{ route('questionChapterAssociate') }}" id="question-chapter-associate">

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-typeahead.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/question-search.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/question-chapter-associate.js') }}"></script>
@endsection
