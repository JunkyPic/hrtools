@extends('base.base')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('title')
    Candidates
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    @if($candidate_answers->count() == 0)
        <div class="col-lg-12 ">
            <h2 class="display-5 text-muted  text-center">No test results found</h2>
        </div>
    @else
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Candidate name</th>
                <th scope="col">Candidate Email</th>
                <th scope="col">Test started at</th>
                <th scope="col">Test finished at</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($candidate_answers as $candidate)
                @if($candidate->answers->count() >= 1)
                    <tr class="table-light">
                        <th scope="row">{{ $candidate->to_fullname }}</th>
                        <td>{{ $candidate->to_email }}</td>
                        <td>{{ \Carbon\Carbon::createFromTimestamp($candidate->test_started_at)->toDateTimeString() }}</td>
                        @if(null === $candidate->test_finished_at)
                            <td>Not finished</td>
                        @else
                            <td>{{ \Carbon\Carbon::createFromTimestamp($candidate->test_finished_at)->toDateTimeString() }}</td>
                        @endif
                        <td><a href="{{ route('testReview', ['id' => $candidate->id ]) }}">Review</a></td>
                    </tr>
                @else
                    <tr class="table-light">
                        <th scope="row">{{ $candidate->to_fullname }}</th>
                        <td>{{ $candidate->to_email }}</td>
                        <td>No info</td>
                        <td>No info</td>
                        <td>N/A</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
        <div class="row spacing-top">
            <div class="col-lg-12 pagination pagination-centered justify-content-center">
                {{ $candidate_answers->links() }}
            </div>
        </div>
    @endif
@endsection

@section('scripts')
@endsection