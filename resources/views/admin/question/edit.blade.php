@extends('base.base')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('title')
    Edit Question
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('questionUpdate', ['id' => $question->id]) }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label text-md-right">{{ __('Title') }}</label>

                    <div class="col-md-10">
                        <input id="title" type="text"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title"
                               value="{{ $question->title }}" required autofocus>
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
                                  required>{{ $question->body }}</textarea>
                        @if ($errors->has('body'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
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
                        @foreach($question->tags as $tag)
                            <div class="col-lg-2" onclick="manageTag($(this));" id="tag_id_{{ $tag->id }}" data-tag-id="{{ $tag->id }}"><button type="button" class="close" data-dismiss="alert">&times;</button>
                            <span class="badge badge-primary">{{ $tag->tag }}</span></div>
                        @endforeach
                    </div>
                </div>
                <div class="form-group row">
                    <label for="images" class="col-md-2 col-form-label text-md-right">{{ __('Add Images') }}</label>
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

                <div class="form-group" id="image__delete_interface_all">
                    @if(null !== $question->images() && $question->images()->count() >= 1)
                        <div class="row offset-2">
                            <div class="col-lg-10 text-center text-primary">
                                <small>Click on any image to bring up an edit interface</small>
                            </div>
                        </div>

                        <div class="row offset-2 spacing-top" id="image__delete_interface">
                            <div class="col-lg-5 text-center">
                                <button type="button" class="btn btn-danger btn-lg btn-block" id="image__delete_btn">
                                    Delete
                                </button>
                            </div>
                            <div class="col-lg-5 text-center">
                                <button type="button" class="btn btn-success btn-lg btn-block" id="image__cancel_btn">
                                    Cancel
                                </button>
                            </div>
                        </div>
                        <div class="row offset-2 spacing-top">
                            @foreach($question->images()->get() as $image)
                                <div class="col-md-5 spacing-top-10" id="image_row_{{$image->id}}">
                                    <img id="{{$image->id}}" class="fit-image image__select"
                                         src="{{ url(Config::get('image.display_path') . $image->alias) }}">
                                    <div class="spacing-top-10 text-center">
                                        <a href="{{ url(Config::get('image.display_path') . $image->alias) }}" target="_blank">View image</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            {{ __('Edit') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <div class="form-group row mb-0">
                <div class="col-md-10 offset-md-2">
                    <button type="button" class="btn btn-danger btn-lg btn-block waves-effect waves-light" data-toggle="modal" data-target="#deleteModal">
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="spacing-top">
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to delete this question?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone, all associated images will also be deleted</p>
                    <p>If this question was part of a test it will also be removed from it</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('questionDelete', ['id' => $question->id]) }}">
                        @csrf
                            <button type="submit" class="btn btn-danger">
                                {{ __('Delete') }}
                            </button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{ route('searchTags') }}" id="tags_api_route">
    <input type="hidden" value="{{ $question->id }}" id="qid">
    <input type="hidden" value="{{ route('manageTag') }}" id="manage_tag_route">
@endsection
<input type="hidden" id="image_update_route" value="{{ route('questionUpdateImages') }}">
<input type="hidden" id="question_id" value="{{ $question->id }}">

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/question-image-update.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-typeahead.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/question-edit-tags.js') }}"></script>
@endsection