@extends('base.base')

@section('title')
    Add Question
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top text-center">
            @if(session('message'))
            <div class="alert alert-dismissible alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>{{ session('message') }}</strong>
            </div>
            @endif
        </div>
    </div>


    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('questionAdd') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label text-md-right">{{ __('Title') }}</label>

                    <div class="col-md-10">
                        <input id="title" type="text"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title"
                               value="{{ old('title') }}" required autofocus>
                        @if ($errors->has('title'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="body" class="col-md-2 col-form-label text-md-right">{{ __('Body') }}</label>

                    <div class="col-md-10">
                        <textarea id="body" rows="10"
                                  class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" name="body"
                                  required>{{ old('body') }}</textarea>
                        @if ($errors->has('body'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="images" class="col-md-2 col-form-label text-md-right">{{ __('Images') }}</label>
                    <div class="col-md-10">

                        <input type="file" id="images" class="form-control" name="images[]" multiple>
                        @if ($errors->any())
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first() }}</strong>
                            </span>
                        @endif
                        <small id="fileHelp" class="form-text text-muted">May upload more than 1 image at a time. Not
                            required.
                        </small>

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