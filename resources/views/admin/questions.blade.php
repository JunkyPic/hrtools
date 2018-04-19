@extends('base.base')

@section('title')
    Show questions
@endsection

@section('content')
    <div class="col-lg-12">
        @if($questions->count() == 0)
            <div class="row">
                <h2 class="display-5 text-center text-muted">No images found</h2>
            </div>
        @else
            <div id="image__delete">
                <div class="row">
                    @foreach($questions as $question)
                        <div class="col-lg-12">
                            {{ $question->title }}
                        </div>
                        @if($question->images())
                            @foreach($question->images as $image)
                                <div class="col-lg-6">
                                    {{ $question->body }}
                                </div>
                                <div class="col-lg-6">
                                    <img class="img-fluid" src="{{ asset('img') . '/' . $image->alias}}">
                                </div>
                            @endforeach

                        @else

                        @endif

                    @endforeach
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 pagination pagination-centered justify-content-center">
                    {{ $questions->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection