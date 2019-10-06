<article class="card-body border-bottom border-default">
    <h2 class="card-title h6">
        <a href="{{ route('posts.show', ['post' => data_get($notification, 'post.id')]) }}" class="text-body">
            {{ __(':name mentioned you in a Bark:', ['name' => data_get($notification, 'post.user.displayName')]) }}
        </a>
    </h2>
    <div class="card">
        <blockquote class="card-body mb-0">{{ data_get($notification, 'post.content') }}</blockquote>
    </div>
</article>
