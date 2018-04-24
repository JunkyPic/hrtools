@if(Auth::check())
<div class="navbar navbar-expand-lg fixed-top navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand">HR tools</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="themes" aria-expanded="false">Questions<span class="caret"></span></a>
                    <div class="dropdown-menu" aria-labelledby="questions">
                        <a class="dropdown-item" href="{{ route('questionsAll') }}">All</a>
                        <a class="dropdown-item" href="{{ route('questionCreate') }}">Add</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="themes" aria-expanded="false">Chapters<span class="caret"></span></a>
                    <div class="dropdown-menu" aria-labelledby="chapters">
                        <a class="dropdown-item" href="{{ route('chapterAll') }}">All</a>
                        <a class="dropdown-item" href="{{ route('chapterGetCreate') }}">Add</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="themes" aria-expanded="false">Tests<span class="caret"></span></a>
                    <div class="dropdown-menu" aria-labelledby="chapters">
                        <a class="dropdown-item" href="{{ route('testAll') }}">All</a>
                        <a class="dropdown-item" href="{{ route('testGetCreate') }}">Add</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('testTaken') }}">Test results</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="themes" aria-expanded="false">Tags<span class="caret"></span></a>
                    <div class="dropdown-menu" aria-labelledby="tags">
                        <a class="dropdown-item" href="{{ route('tagsAll') }}">All</a>
                        <a class="dropdown-item" href="{{ route('tagCreate') }}">Add</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="themes" aria-expanded="false">Invites<span class="caret"></span></a>
                    <div class="dropdown-menu" aria-labelledby="invites">
                        <a class="dropdown-item" href="{{ route('getIssueInvite') }}">Invite user</a>
                        <a class="dropdown-item" href="{{ route('getUserInvites') }}">User invites</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('candidateGetCreateInvite') }}">Invite candidate</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="themes" aria-expanded="false">Profile<span class="caret"></span></a>
                    <div class="dropdown-menu" aria-labelledby="profile">
                        <a class="dropdown-item" href="{{ route('userProfile') }}">My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="themes" aria-expanded="false">Editable areas<span class="caret"></span></a>
                    <div class="dropdown-menu" aria-labelledby="profile">
                        <a class="dropdown-item" href="{{ route('getEditAreaTestList') }}">Prestart test</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endif


