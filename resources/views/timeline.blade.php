@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            @include('users.card', ['user' => auth()->user()])
        </div>

        <div class="col-md-8">
            <div class="card">
                @foreach ($posts as $post)
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

                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
