@extends('base.base')

@section('title')
    Issue Candidate Invite
@endsection

@section('content')
    @if($tests->count() == 0)
        <div class="col-md-12 spacing-top text-center">
            <p>There are no tests to send to the candidates. <a href="{{ route('testGetCreate') }}">Create some and the
                    return here.</a></p>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-12 spacing-top text-center">
                <div class="alert alert-dismissible alert-danger">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <p><strong>This area is for inviting candidates to take tests. Do not invite potential admins,
                            reviewers or content creators by using
                            this functionality.</strong></p>
                </div>
            </div>
        </div>

        @include('includes.message')
        <div class="row justify-content-center">
            <div class="col-md-12 spacing-top">
                <form method="POST" action="{{ route('candidatePostCreateInvite') }}" id="form_submit">
                    @csrf
                    <div class="form-group row">
                        <label for="to" class="col-sm-2 col-form-label text-md-right">{{ __('To') }}</label>

                        <div class="col-md-10">
                            <input id="to" type="email"
                                   class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="to"
                                   value="{{ old('to') }}" required autofocus
                                   placeholder="person{!! '@' !!}example.com">
                            @if ($errors->has('to'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('to') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="to" class="col-sm-2 col-form-label text-md-right">{{ __('Candidate name') }}</label>

                        <div class="col-md-10">
                            <input id="fullname" type="text"
                                   class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                   name="fullname"
                                   value="{{ old('fullname') }}" required autofocus placeholder="John/Jane Doe">
                            @if ($errors->has('fullname'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('fullname') }}</strong>
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
                        <label for="message" class="col-md-2 col-form-label text-md-right">{{ __('Message') }}</label>
                        <div class="col-md-10">
                        <textarea id="message" rows="10"
                                  class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" name="message"
                                  required>Hello, this email contains a link to a technical test from Optars</textarea>
                            <small class="text-muted">The invite link is automagically generated and attached to the
                                email, please don't include any invite links yourself.
                            </small>
                            <small class="text-muted">If you wish to include other links that are not related to the
                                invite link itself, have at it.
                            </small>
                            @if ($errors->has('message'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('message') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="test_id" class="col-md-2 col-form-label text-md-right">{{ __('Test name') }}</label>
                        <div class="col-md-10">
                            <select name="test_id" class="form-control" id="test_id">
                                @foreach($tests as $test)
                                    <option value="{{ $test->id }}">{{ $test->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('test_validity'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('test_validity') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="test_validity"
                               class="col-md-2 col-form-label text-md-right">{{ __('Test duration') }}</label>
                        <div class="col-md-10">
                            <select name="test_validity" class="form-control" id="test_validity">
                                <option value="1800">30 minutes</option>
                                <option value="2100">35 minutes</option>
                                <option value="2400">40 minutes</option>
                                <option value="2700">45 minutes</option>
                                <option value="3000" selected>50 minutes</option>
                                <option value="3300">55 minutes</option>
                                <option value="3600">1 hour</option>
                            </select>
                            @if ($errors->has('test_validity'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('test_validity') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row mb-0 spacing-top-10">
                        <div class="col-md-10 offset-md-2">
                            <button type="button" class="btn btn-primary btn-lg btn-block waves-effect waves-light" data-toggle="modal" data-target="#submitModal">
                                {{ __('Send') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-labelledby="submitModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure you wish to submit the form?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Once the form is submitted an email will be sent to the potential candidate with a link inviting him or her to take the test.</p>
                        <p><span class="text-danger small">Make sure that the name of the candidate is correct, both the email message(if included) AND in the Candidate name field.</span></p>
                        <p><span class="text-danger small">Make sure that the selected test is the intended one.</span></p>
                        <p><span class="text-danger small">Make sure that the test validity is the intended one.</span></p>
                        <p><span class="text-danger small">Make sure that the email invite validity is the intended one.</span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger" onclick="$('#form_submit').submit()">
                            {{ __('Submit') }}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
