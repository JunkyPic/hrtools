@extends('base.base')

@section('title')
    Edit Tag
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('tagEditPost', ['id' => $tag->id]) }}">
                @csrf
                <div class="form-group row">
                    <label for="tag" class="col-sm-2 col-form-label text-md-right">{{ __('Tag name') }}</label>
                    <div class="col-md-10">
                        <input id="tag" type="text"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="tag"
                               value="{{ $tag->tag }}" required autofocus>
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
                            {{ __('Edit') }}
                        </button>
                    </div>
                </div>

                <div class="form-group row mb-0 spacing-top-10">
                    <div class="col-md-10 offset-md-2">
                        <button type="button" class="btn btn-danger btn-lg btn-block waves-effect waves-light" data-toggle="modal" data-target="#deleteModal">
                            {{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Are you sure you want to delete this tag?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone</p>
                    <p>If a question was associated with this tag the tag will be removed from the question</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('tagDelete', ['id' => $tag->id]) }}">
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
@endsection