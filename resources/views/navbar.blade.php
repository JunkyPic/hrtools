@if(Auth::check())
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a href="#menu-toggle" class="navbar-brand" id="menu-toggle">{{ __('Toggle Menu') }}</a>
    <a href="{{ route('logout') }}" class="navbar-brand">{{ __('Logout') }}</a>
</nav>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <ul class="sidebar-nav">
            <li class="sidebar-brand">
                <a href="#">
                    Start Bootstrap
                </a>
            </li>
            <li>
                <a href="{{ route('adminFront') }}">Admin</a>
            </li>
            <li>
                <a href="{{ route('userProfile') }}">My profile</a>
            </li>
        </ul>
    </div>
</div>
@endif
