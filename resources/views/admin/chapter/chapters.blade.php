@extends('base.base')

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
                    <div class="col-lg-12">
                        <h3><a href="{{ route('chapterGetEdit', ['id' => $chapter->id]) }}">{{ $chapter->chapter }}</a>
                        </h3>
                    </div>
                </div>
                @if(null !== $chapter->information)
                <div class="spacing-top row">
                    <div class="col-md-12">
                        {{ $chapter->information }}
                    </div>
                </div>
                @endif
            </div>
        @endforeach
        <div class="row spacing-top">
            <div class="col-lg-12 pagination pagination-centered justify-content-center">
                {{ $chapters->links() }}
            </div>
        </div>
    @endif
@endsection
