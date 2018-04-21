@extends('base.base')

@section('title')
    Add Chapter
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('chapterPostCreate') }}">
                @csrf
                <div class="form-group row">
                    <label for="chapter" class="col-sm-2 col-form-label text-md-right">{{ __('Chapter name') }}</label>

                    <div class="col-md-10">
                        <input id="chapter" type="text"
                               class="form-control{{ $errors->has('chapter') ? ' is-invalid' : '' }}" name="chapter"
                               value="{{ old('chapter') }}" required autofocus>
                        @if ($errors->has('chapter'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('chapter') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="information" class="col-sm-2 col-form-label text-md-right">{{ __('Chapter information') }}</label>

                    <div class="col-md-10">
                        <input id="information" type="text"
                               class="form-control{{ $errors->has('information') ? ' is-invalid' : '' }}" name="information"
                               value="{{ old('information') }}" autofocus>
                        @if ($errors->has('information'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('information') }}</strong>
                            </span>
                        @endif
                        <small class="text-muted"> Something to easily identify the chapter. This is strictly for information purposes and will not be shown to candidates.</small>
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            {{ __('Add') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection