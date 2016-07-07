
<div class="media">
    <a class="pull-left" href="{{ route('profile.index', ['username' => $user->username]) }}">
        <img class="media-object" 
        alt="{{ $user->getNameOrUsername() }}" 
        src="{{ $user-> getAvatarUrl() }}" 
        height="160" width="160">
    </a>
    <div class="media-body">
        <h4 class="media-heading"><a href="{{ route('profile.index', ['username' => $user->username]) }}">{{ $user->getNameOrUsername() }}</a></h4>

        <p>
        @if($user->location)       
            Lives in {{ $user->location }}<br />
        @endif   
        @if($user->email)
            E-mail: {{ $user->email }}<br />
        @endif   
            Works as Student at Masaryk University</p>

    </div>
</div>