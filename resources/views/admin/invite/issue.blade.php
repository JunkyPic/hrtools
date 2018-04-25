@extends('base.base')

@section('title')
    Issue Invite
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top text-center">
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <p><strong>This area is for inviting other people to join as reviewers, administrators or other roles. Do not invite potential candidates by using this functionality.</strong></p>
            </div>
        </div>
    </div>

    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <form method="POST" action="{{ route('postIssueInvite') }}">
                @csrf
                <div class="form-group row">
                    <label for="to" class="col-sm-2 col-form-label text-md-right">{{ __('To') }}</label>

                    <div class="col-md-10">
                        <input id="to" type="email"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="to"
                               value="{{ old('to') }}" required autofocus placeholder="person{!! '@' !!}example.com">
                        @if ($errors->has('to'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('to') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="subject" class="col-sm-2 col-form-label text-md-right">{{ __('Subject') }}</label>

                    <div class="col-md-10">
                        <input id="subject" type="text"
                               class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="subject"
                               value="{{ $user->username }}, from Optaros" required autofocus>
                        @if ($errors->has('subject'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('subject') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="body" class="col-md-2 col-form-label text-md-right">{{ __('Body') }}</label>
                    <div class="col-md-10">
                        <textarea id="body" rows="10"
                                  class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" name="body"
                                  required>Hello, I would like to invite you to join HR Tools</textarea>
                        @if ($errors->has('body'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('body') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="validity" class="col-md-2 col-form-label text-md-right">{{ __('Validity') }}</label>
                    <div class="col-md-10">
                        <select name="validity" class="form-control" id="validity">
                            <option value="1800">30 minutes</option>
                            <option value="3600" selected>1 hour</option>
                            <option value="7200">2 hours</option>
                        </select>
                        @if ($errors->has('validity'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('validity') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="roles" class="col-md-2 col-form-label text-md-right">{{ __('Give roles') }}</label>
                    <div class="col-md-10">
                        <ul class="list-inline">
                            @foreach($roles as $role)
                                <li class="list-inline-item">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="roles[{{$role}}]" value="{{ $role }}"> {{ $role }}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                        @if ($errors->has('roles'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('roles') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            {{ __('Send') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
