@extends('base.base')

@section('title')
    Edit Test
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('testPostEdit', ['id' => $test->id]) }}">
                @csrf
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label text-md-right">{{ __('Test name') }}</label>
                    <div class="col-md-10">
                        <input id="name" type="text"
                               class="form-control{{ $errors->has('chapter') ? ' is-invalid' : '' }}" name="name"
                               value="{{ $test->name }}" required autofocus>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="information" class="col-sm-2 col-form-label text-md-right">{{ __('Test information') }}</label>
                    <div class="col-md-10">
                        <input id="information" type="text"
                               class="form-control{{ $errors->has('information') ? ' is-invalid' : '' }}" name="information"
                               value="{{ $test->information }}" autofocus>
                        @if ($errors->has('information'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('information') }}</strong>
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
                    <h5 class="modal-title">Are you sure you want to delete this chapter?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>This action cannot be undone</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('testDelete', ['id' => $test->id]) }}">
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