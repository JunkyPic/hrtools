@extends('base.base')

@section('title')
    Permissions by role
@endsection

@section('content')
    <div class="spacing-top"></div>
    @include('includes.message')

    <div class="row">
    @foreach($roles_and_permissions->getRolesAndPermissions() as $key => $roles_and_permission)
        <div class="col-md-4">
        <table class="table table-hover">
            <thead>
            <tr class="table-primary">
                <td>Role <strong>{{ $key }}</strong> can</td>
            </tr>
            </thead>
            <tbody>
                    @foreach($roles_and_permission as $permission)
                        <tr class="table-light">
                        <td scope="row">{{ $permission }}</td>
                        </tr>
                    @endforeach
            </tbody>
        </table>
        </div>
    @endforeach
    </div>
@endsection
