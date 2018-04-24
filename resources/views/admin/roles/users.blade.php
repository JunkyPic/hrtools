@extends('base.base')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
@endsection

@section('title')
    Roles
@endsection

@section('content')
    <div class="spacing-top">
    </div>
    @include('includes.message')

    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col" colspan="{{ $roles->count() }}" class="text-center">Roles</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            @if($user->id == $current_user->id)
                <tr class="table-primary">
            @else
                <tr class="table-light">
            @endif
                <th scope="row">{{ $user->username }}</th>
                <td>{{ $user->email }}</td>
                @foreach($roles as $role)
                    @if($user->id == 1)
                        <td>
                            @role($role->name)
                            <label class="checkbox-inline">
                                <input checked type="checkbox" disabled>{{ $role->name }}
                            </label>
                            @else
                                <label class="checkbox-inline">
                                    <input type="checkbox" disabled >{{ $role->name }}
                                </label>
                            @endrole
                        </td>
                    @else
                        <td>
                            @if($user->hasRole($role->name))
                            <label class="checkbox-inline">
                                <input checked type="checkbox" data-uid="{{ $user->id }}" onclick="toggleRole($(this), '{{ route('assignRole') }}', '{{ route('revokeRole') }}')" value="{{ $role->name }}">{{ $role->name }}
                            </label>
                            @else
                                <label class="checkbox-inline">
                                    <input type="checkbox" data-uid="{{ $user->id }}" onclick="toggleRole($(this), '{{ route('assignRole') }}', '{{ route('revokeRole') }}')" value="{{ $role->name }}">{{ $role->name }}
                                </label>
                            @endif
                        </td>
                    @endif
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row spacing-top">
        <div class="col-lg-12 pagination pagination-centered justify-content-center">
            {{ $users->links() }}
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">

        function toggleRole(self, route_assign_role, route_revoke_role) {
            var route = '';
            if($(self).is(":checked")) {
                route = route_assign_role;
            } else {
                route = route_revoke_role;
            }

            $.ajax({
                type: 'POST',
                url: route,
                data: {
                    uid: self.data("uid"),
                    role_name: self.val()
                },
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data){
                    $('#message_display').show();
                    $('#message_output').html(data.message);
                }
            });
        }
    </script>
@endsection