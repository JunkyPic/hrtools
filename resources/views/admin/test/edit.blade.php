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
                    <label for="name" class="col-sm-2 col-form-label text-md-right">{{ __('Name') }}</label>
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
                    <label for="information" class="col-sm-2 col-form-label text-md-right">{{ __('Information') }}</label>
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

                <div class="form-group row">
                    <label for="instructions" class="col-md-2 col-form-label text-md-right">{{ __('Instructions') }}</label>

                    <div class="col-md-10">
                        <textarea id="instructions" rows="10"
                                  class="form-control{{ $errors->has('instructions') ? ' is-invalid' : '' }}" name="instructions"
                                  required>{{ $test->instructions }}</textarea>
                        @if ($errors->has('instructions'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('instructions') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="end_test_message" class="col-md-2 col-form-label text-md-right">{{ __('Test end message') }}</label>

                    <div class="col-md-10">
                        <textarea id="end_test_message" rows="10"
                                  class="form-control{{ $errors->has('end_test_message') ? ' is-invalid' : '' }}" name="message">{{ $test->end_test_message }}</textarea>
                        @if ($errors->has('end_test_message'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('end_test_message') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-10 offset-md-2">
                        <small>This message will appear once a candidate has finished a test. If no message is set, the default one will be used.
                            The default message can be set <a href="{{ route('getSetTestsDefaultMessage') }}" target="_blank">here</a>.
                        </small>
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
