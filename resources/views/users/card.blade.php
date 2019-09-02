<article class="card">
    <div class="card-body">
        <h3 class="card-title h5">{{ $user->display_name }}</h1>
        <p class="card-subtitle mb-3">{!! username($user) !!}</p>

        @include('users.user-counts', ['user' => $user])
    </div>
</article>
