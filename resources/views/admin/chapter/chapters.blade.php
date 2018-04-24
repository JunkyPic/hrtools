@extends('base.base')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection
@section('title')
    Chapters
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')
    @if($chapters->count() == 0)
        <div class="col-lg-12 ">
            <h2 class="display-5 text-muted  text-center">No chapters found, <a href="{{ route('chapterGetCreate') }}">add
                    some</a></h2>
        </div>
    @else
        @foreach($chapters as $chapter)
            <div class="question-border margin-top-10 padding-10">
                <div class="spacing-top row">
                    <div class="col-lg-6">
                        <div class="col-md-12">
                            <h3><a href="{{ route('chapterGetEdit', ['id' => $chapter->id]) }}">{{ $chapter->chapter }}</a>
                            </h3>
                        </div>
                        @if(null !== $chapter->information)
                            <div class="col-md-12">
                                {{ $chapter->information }}
                            </div>
                        @endif

                        @if($chapter->tests()->count() >=  1)
                            <div class="col-md-10 spacing-top-10">
                                In tests:
                                @foreach($chapter->tests as $test)
                                    <span class="badge-primary">{{ $test->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <div class="col-md-10 spacing-top-10">
                                This chapter not currently in any test
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-6">
                        @if($tests->count() >= 1)
                            <div class="col-lg-12">
                                <label class="mr-sm-2"><small class="text-muted">Associate to test</small>
                                    <select class="custom-select mb-2 mr-sm-2 mb-sm-0 associate-chapter" name="order">
                                        <option selected data-qid="NA" value="NA">Choose...</option>
                                        @foreach($tests as $test)
                                            <option data-cid="{{ $chapter->id }}"
                                                    value="{{ $test->id }}">{{ $test->name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        @endif

                        @if($tests->count() >= 1)
                            <div class="col-lg-12">
                                <label class="mr-sm-2"><small class="text-muted">Remove from test</small>
                                    <select class="custom-select mb-2 mr-sm-2 mb-sm-0 remove-chapter" name="order">
                                        <option selected data-tid="NA" value="NA">Choose...</option>
                                        @foreach($tests as $test)
                                            <option data-cid="{{ $chapter->id }}"
                                                    value="{{ $test->id }}">{{ $test->name }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row spacing-top">
            <div class="col-lg-12 pagination pagination-centered justify-content-center">
                {{ $chapters->links() }}
            </div>
        </div>
    @endif

    <input type="hidden" value="{{ route('chapterTestAssociate') }}" id="chapter-test-associate-route">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/chapter-test-associate.js') }}"></script>
@endsection