@extends('base.base')

@section('title')
    Review
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top text-center">
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-dismissible alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>{{ $error }}</strong>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @if(null !== $candidate->candidate)
                <p>Candidate name: {{ $candidate->candidate->fullname }}</p>
                <p>Candidate email: {{ $candidate->candidate->to }}</p>
                <p>Test started at: {{ \Carbon\Carbon::createFromTimestamp($candidate->started_at)->toDateTimeString()}}</p>
                <p>Test finished
                    at: {{ \Carbon\Carbon::createFromTimestamp($candidate->finished_at)->toDateTimeString()}}</p>
                <p>Test duration: {{ number_format(($candidate->finished_at - $candidate->started_at) / 60, 2) }}
                    minutes</p>
            @else
                <p>Candidate name: {{ $candidate->fullname }}</p>
                <p>Candidate email: {{ $candidate->to }}</p>
                <p>Test started at: {{ \Carbon\Carbon::createFromTimestamp($candidate->started_at)->toDateTimeString()}}</p>
                <p>Test finished
                    at: {{ \Carbon\Carbon::createFromTimestamp($candidate->finished_at)->toDateTimeString()}}</p>
                <p>Test duration: {{ number_format(($candidate->finished_at - $candidate->started_at) / 60, 2) }}
                    minutes</p>
            @endif
        </div>
    </div>

    <form method="POST" action="{{ route('reviewSubmit') }}">
        @csrf
        <input type="hidden" value="{{$test_id}}" name="candidate_test_id">
        <input type="hidden" value="{{$candidate_id}}" name="candidate_id">

        @foreach($candidate->answers as $answer)
            <div class="form-group">
                <div class="row question-border padding-30 margin-top-10">
                    <div class="col-lg-12">
                        <h3>{{ $answer->question_title }}</h3>
                    </div>
                    <div class="col-lg-12">
                        <p>{!! $answer->question_body  !!}</p>
                    </div>
                    @if($answer->images->count() >= 1)
                        @foreach($answer->images as $image)
                            <div class="col-lg-4">
                                <img class="img-fluid img-thumbnail"
                                     src="{{ url($image_display_path . $image->folder . '/' . $image->alias)}}">
                            </div>
                        @endforeach
                    @endif
                    <div class="col-lg-12 padding-30 margin-top-10">
                        @if(null !== $answer->answer)
                            <p>Answer given:</p>
                            <p>{!! nl2br(e( $answer->answer)) !!}</p>
                        @else
                            <p>Question was not answered</p>
                        @endif
                    </div>
                    <div class="col-lg-6 offset-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_correct[{{$answer->id}}]"
                                   id="CORRECT_{{$answer->id}}" value="{{ \App\Models\Review::CORRECT }}">
                            <label class="form-check-label" for="CORRECT_{{$answer->id}}">Correct</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_correct[{{$answer->id}}]"
                                   id="PARTIALLY_CORRECT_{{$answer->id}}" value="{{ \App\Models\Review::PARTIALLY_CORRECT }}">
                            <label class="form-check-label" for="PARTIALLY_CORRECT_{{$answer->id}}">Partially
                                correct</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_correct[{{$answer->id}}]"
                                   id="INCORRECT_{{$answer->id}}" value="{{ \App\Models\Review::INCORRECT }}">
                            <label class="form-check-label" for="INCORRECT_{{$answer->id}}">Incorrect</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="is_correct[{{$answer->id}}]"
                                   id="REQUIRES_ADDITIONAL_REVIEW_{{$answer->id}}" value="{{ \App\Models\Review::REQUIRES_ADDITIONAL_REVIEW }}">
                            <label class="form-check-label" for="REQUIRES_ADDITIONAL_REVIEW_{{$answer->id}}">Requires
                                additional review</label>
                        </div>
                    </div>
                    <div class="col-lg-12 spacing-top-30 text-center">
                        <hr>
                        <label for="note{{$answer->id}}"><strong>Notes</strong></label>
                        <textarea name="notes[{{ $answer->id }}]" class="form-control margin-bottom-20" style="min-width: 100%" rows="5"
                                  id="note{{$answer->id}}"></textarea>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="row justify-content-center">
            <div class="col-md-12 spacing-top">
                <div class="form-group row mb-0">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success btn-lg btn-block waves-effect waves-light"
                                data-toggle="modal" data-target="#submitModal">
                            {{ __('Submit review') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="submitModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure you want to finish the review?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Remember that you can always come back and edit it later on</p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit">
                            {{ __('Yes, I\'m sure') }}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
@endsection