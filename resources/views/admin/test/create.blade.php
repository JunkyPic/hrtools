@extends('base.base')

@section('title')
    Add Test
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('testPostCreate') }}">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label text-md-right">{{ __('Name') }}</label>

                    <div class="col-md-10">
                        <input id="name" type="text"
                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
                               value="{{ old('name') }}" required autofocus>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="information"
                           class="col-sm-2 col-form-label text-md-right">{{ __('Information') }}</label>
                    <div class="col-md-10">
                        <input id="information" type="text"
                               class="form-control{{ $errors->has('information') ? ' is-invalid' : '' }}"
                               name="information"
                               value="{{ old('information') }}" autofocus>
                        @if ($errors->has('information'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('information') }}</strong>
                            </span>
                        @endif
                        <small class="text-muted"> Something to easily identify the test. This is strictly for
                            information purposes and will not be shown to candidates.
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="instructions" class="col-md-2 col-form-label text-md-right">{{ __('Instructions') }}</label>

                    <div class="col-md-10">
                        <textarea id="instructions" rows="10"
                                  class="form-control{{ $errors->has('instructions') ? ' is-invalid' : '' }}" name="instructions"
                                  required></textarea>
                        @if ($errors->has('instructions'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('instructions') }}</strong>
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
