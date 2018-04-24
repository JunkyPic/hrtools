@extends('base.base')

@section('title')
    Editable areas
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
                        <h3><a href="{{ route('getEditAreaPrestartTest', ['test_id' => $test->id]) }}">Editable areas for test - {{ $test->name }}</a></h3>
                    </div>
                </div>
                @if(null !== $test->information)
                    <div class="spacing-top row">
                        <div class="col-md-12">
                            <p>{{ $test->information }}</p>
                        </div>
                    </div>
                @endif
                @if($test->editableArea->count() == 0)
                    <div class="spacing-top row">
                        <div class="col-md-12">
                            <p>Editable areas are empty</p>
                        </div>
                    </div>
                @else
                    <div class="spacing-top row">
                        <div class="col-md-12">
                            <p>Editable areas are filled</p>
                        </div>
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
