@extends('base.base')

@section('title')
    Add Question
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

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

                <div class="form-group row">
                    <label for="images" class="col-md-2 col-form-label text-md-right">{{ __('Tags') }}</label>
                    <div class="col-md-10">
                        <input type="text" class="form-control" id="search-input" placeholder="Search tags">
                        <small class="form-text text-muted">Search for tags here and they'll be auto-completed, this is not required but it is recommended
                        </small>
                    </div>
                </div>

                <div class="form-group row" id="tags_area">
                    <label for="current_tags" class="col-md-2 col-form-label text-md-right">{{ __('Current tags') }}</label>
                    <div class="col-md-10" id="current_tags">
                    </div>
                </div>
                <div id="hidden_tags">
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

    <input type="hidden" value="{{ route('searchTags') }}" id="tags_api_route">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-typeahead.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/question-tags.js') }}"></script>
@endsection