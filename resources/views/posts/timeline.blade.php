<article class="card-body border-bottom border-default">
    <header class="post-meta-header">
        <p>
            <span class="h6">{{ $post->user->display_name }}</span>
            <span class="text-muted">{{ $post->user->username }}</span>
            <time class="text-muted" datetime="{{ $post->created_at->toAtomString() }}" title="{{ $post->created_at->toRssString() }}">{{ $post->created_at->diffForHumans() }}</time>
        </p>
    </header>

    {{ $post->content }}
</article>
