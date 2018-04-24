@extends('base.base')

@section('title')
    Issue Invite
@endsection

@section('content')
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('postEditAreaPrestartTest', ['test_id' => $test_id]) }}">
                @csrf
                <input type="hidden" name="test_id" value="{{ $test_id }}">
                <div class="form-group row">
                    <label for="header" class="col-md-2 col-form-label text-md-right">{{ __('Header') }}</label>
                    <div class="col-md-10">
                        <textarea id="header" rows="10"
                                  class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" name="header">@if(isset($area)) {{$area->header}} @endif</textarea>
                        @if ($errors->has('header'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('header') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="instructions" class="col-md-2 col-form-label text-md-right">{{ __('Instructions') }}</label>
                    <div class="col-md-10">
                        <textarea id="instructions" rows="10"
                                  class="form-control{{ $errors->has('header') ? ' is-invalid' : '' }}" name="instructions" required>@if(isset($area)) {{$area->instructions}} @endif</textarea>
                        @if ($errors->has('instructions'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('instructions') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="footer" class="col-md-2 col-form-label text-md-right">{{ __('Footer') }}</label>
                    <div class="col-md-10">
                        <textarea id="footer" rows="10"
                                  class="form-control{{ $errors->has('footer') ? ' is-invalid' : '' }}" name="footer">@if(isset($area)) {{$area->footer}} @endif</textarea>
                        @if ($errors->has('footer'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('footer') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
