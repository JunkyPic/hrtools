@extends('base.base')

@section('title')
    Default test message
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('postSetTestsDefaultMessage') }}">
                @csrf
                <div class="form-group row">
                    <label for="default_message"
                    class="col-md-12 col-form-label text-center">{{ __('Tests default message') }}</label>

                    <textarea id="default_message" rows="10"
                              class="form-control{{ $errors->has('default_message') ? ' is-invalid' : '' }}"
                              name="default_message"
                              required>@if(null !== $default_message){{ $default_message->default_message }}@endif</textarea>
                    @if ($errors->has('default_message'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('default_message') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <small>This message will appear once a candidate has finished a test. Each test can have an individual message of its own. This is the default fallback message
                        in case a test doesn't have a message.
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
