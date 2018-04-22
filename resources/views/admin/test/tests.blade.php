@extends('base.base')

@section('title')
    Tests
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    @if($tests->count() == 0)
        <div class="col-lg-12 ">
            <h2 class="display-5 text-muted  text-center">No tests found, <a href="{{ route('testGetCreate') }}">add
                    some</a></h2>
        </div>
    @else
        @foreach($tests as $test)
            <div class="question-border margin-top-10 padding-10">
                <div class="spacing-top row">
                    <div class="col-lg-12">
                        <h3><a href="{{ route('testGetEdit', ['id' => $test->id]) }}">{{ $test->name }}</a>
                        </h3>
                    </div>
                </div>
                @if(null !== $test->information)
                    <div class="spacing-top row">
                        <div class="col-md-12">
                            <p>{{ $test->information }}</p>
                        </div>
                    </div>
                @endif

                @if($test->chapters->count() >= 1)
                    <div class="row">
                        @foreach($test->chapters as $chapter)
                            <div class="col-md-6 spacing-top-10">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center active">
                                        <a class="text-white"
                                           href="{{ route('chapterGetEdit', ['id' => $chapter->id]) }}">Chapter
                                            - {{ $chapter->chapter }}</a>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-warning">
                                        Questions
                                        <span class="badge badge-warning badge-pill">{{ $chapter->questions->count() }}</span>
                                    </li>
                                    @foreach($chapter->questions as $question)
                                        <li class="list-group-item"><a
                                                    href="{{ route('questionEdit', ['id' => $question->id]) }}">{{ $question->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
        <div class="row spacing-top">
            <div class="col-lg-12 pagination pagination-centered justify-content-center">
                {{ $tests->links() }}
            </div>
        </div>
    @endif
@endsection
