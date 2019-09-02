<article class="card">
    <div class="card-body media">
        <img src="{{ $user->getAvatarUrl(100) }}" alt="" class="avatar mr-3">
        <div class="media-body">
            <h3 class="card-title h5">{{ $user->display_name }}</h3>
            <p class="card-subtitle mb-2">{!! username($user) !!}</p>

            @unless (! auth()->check() || $user->id === auth()->user()->id)
                <follow-button
                    username="{{ $user->username }}"
                    :following="{{ auth()->user()->follows($user) ? 'true' : 'false' }}"
                />
            @endunless
        </div>
    </div>
</article>
