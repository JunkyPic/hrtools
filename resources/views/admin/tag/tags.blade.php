@extends('base.base')

@section('title')
    All tags
@endsection

@section('content')
    @include('includes.message')

    @if(null === $tags || $tags->count() === 0)
        <div class="row justify-content-center spacing-top-10">
            <div class="col-lg-12 ">
                <h2 class="display-5 text-muted text-center">No tags found, <a href="{{ route('tagCreate') }}">add
                        some</a></h2>
            </div>
        </div>
    @else
        @foreach($tags->chunk(6) as $item)
            <div class="row spacing-top">
                @foreach($item as $tag)
                    <div class="col-md-2 text-center">
                        <h3>
                            <a href="{{ route('questionsTaggedWith', ['tag' => $tag->tag]) }}"><span class="badge badge-primary">{{ $tag->tag }} </span></a>
                        </h3>
                        <a href="{{ route('tagEdit', ['id' => $tag->id]) }}">
                            <small class="text-muted">edit</small>
                        </a>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
@endsection