<article class="card">
    <div class="card-body">
        <h1 class="card-title h5">{{ $user->display_name }}</h1>
        <p class="card-subtitle text-muted">{{ $user->username }}</p>
        <dl class="d-flex flex-row justify-content-between">
            <div>
                <dt>{{ __('Barks') }}</dt>
                <dd><a href="{{ route('users.show', ['user' => $user]) }}">{{ $user->posts()->count() }}</a></dd>
            </div>
            <div>
                <dt>{{ __('Followers') }}</dt>
                <dd>{{ $user->followers()->count() }}</dd>
            </div>
            <div>
                <dt>{{ __('Following') }}</dt>
                <dd>{{ $user->following()->count() }}</dd>
            </div>
        </dl>
    </div>
</article>
