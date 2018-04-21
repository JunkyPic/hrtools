@extends('base.base')

@section('title')
    Add Tag
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('tagAdd') }}">
                @csrf
                <div class="form-group row">
                    <label for="tag" class="col-sm-2 col-form-label text-md-right">{{ __('Tag name') }}</label>

                    <div class="col-md-10">
                        <input id="tag" type="text"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="tag"
                               value="{{ old('tag') }}" required autofocus>
                        @if ($errors->has('tag'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('tag') }}</strong>
                            </span>
                        @endif
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