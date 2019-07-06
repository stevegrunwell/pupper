@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <article class="card">
                <div class="card-body">
                    <h1 class="card-title h5">{{ $user->display_name }}</h1>
                    <p class="card-subtitle text-muted">{{ $user->username }}</p>
                    <p>{{ __('Joined :date', ['date' => $user->created_at->format('F Y')]) }}</p>

                    @unless (! auth()->check() || $user->id === auth()->user()->id)
                        <form method="post" action="{{ route('users.follow', ['user' => $user]) }}">
                            @if (auth()->user()->follows($user))
                                <button type="submit" class="btn btn-secondary">{{ __('Unfollow') }}</button>
                                @method('DELETE')
                            @else
                                <button type="submit" class="btn btn-primary">{{ __('Follow') }}</button>
                            @endif
                            @csrf
                        </form>
                    @endunless
                </div>
            </article>
        </div>

        <div class="col-md-8">
            <div class="card">
                @foreach ($posts as $post)
                    @include('posts.timeline', ['post' => $post])
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
