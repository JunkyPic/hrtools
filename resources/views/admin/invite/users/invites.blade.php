@extends('base.base')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('title')
    User invites
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')
    <div class="row justify-content-center hide" id="message_display">
        <div class="col-md-12 text-center">
            <div class="alert alert-info">
                <strong id="message_output"></strong>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12 spacing-top">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">To</th>
                    <th scope="col">From</th>
                    <th scope="col">Validity</th>
                    <th scope="col">Is Valid</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invites as $invite)
                    <tr class="table-light">
                        <th>{{$invite->to}}</th>
                        <td>{{$invite->from}}</td>
                        <td>{{number_format((float)$invite->validity / (60 * 60), 2, '.', '')}} hour(s)</td>
                        <td>{{ $invite->is_valid ? 'Yes' : 'No'}}</td>
                        <td><a href="#" onclick="revokeUserInvite({{ $invite->id }});">Revoke</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <input type="hidden" value="{{ route('revokeInvite') }}" id="user_revoke_invite">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/invites-revoke-user-invite.js') }}"></script>
@endsection
