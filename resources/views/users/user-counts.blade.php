<dl class="d-flex flex-row justify-content-between mb-0">
    <div>
        <dt>{{ __('Barks') }}</dt>
        <dd class="mb-0"><a href="{{ route('users.show', ['user' => $user]) }}">{{ $user->posts()->count() }}</a></dd>
    </div>
    <div>
        <dt>{{ __('Followers') }}</dt>
         <dd class="mb-0"><a href="{{ route('users.followers', ['user' => $user]) }}">{{ $user->followers()->count() }}</a></dd>
    </div>
    <div>
        <dt>{{ __('Following') }}</dt>
        <dd class="mb-0"><a href="{{ route('users.following', ['user' => $user]) }}">{{ $user->following()->count() }}</a></dd>
    </div>
</dl>
