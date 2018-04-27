@extends('base.base')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('title')
    Candidate invites
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')
    <div class="row justify-content-center">

        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">To email</th>
                <th scope="col">Full name</th>
                <th scope="col">From</th>
                <th scope="col">Status</th>
                <th scope="col">Actions/Status</th>
            </tr>
            </thead>
            <tbody>

            @foreach($invites as $invite)
                @foreach($invite->candidateTest as $test)
                    <tr class="table-light">
                        <th scope="row">{{$invite->to}}</th>
                        <td>{{$invite->fullname}}</td>
                        <td>{{$invite->from}}</td>
                        <td>
                            @if(NULL !== $test->started_at && NULL === $test->finished_at)
                                Test started but not finished
                            @elseif(NULL !== $test->started_at && NULL !== $test->finished_at)
                                Finished
                                at: {{ \Carbon\Carbon::createFromTimestamp($test->finished_at)->toDateTimeString() }}
                            @elseif(NULL === $test->started_at && NULL === $test->finished_at)
                                Test not started
                            @endif
                        </td>
                        <td>
                            @if($test->is_valid == 0)
                                <span>Invite revoked</span>
                            @else
                                <span><a href="#" onclick="revokeUserInvite({{$test->id}}, $(this)); return false;">Revoke invite</a> </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

    <input type="hidden" value="{{ route('invalidateInvite') }}" id="candidate_invalidate_invite">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/invites-revoke-candidate-invite.js') }}"></script>
@endsection
