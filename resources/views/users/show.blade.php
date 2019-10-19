@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 sidebar">
                <article class="card sticky-sidebar mb-4">
                    <div class="card-body">
                        @include('users.profile', ['user' => $user])
                    </div>
                </article>
            </div>

            <div class="col">
                @if ($user->posts_count)
                    <timeline route="{{ route('api.userTimeline', ['user' => $user]) }}" />
                @else
                    <section class="pt-5 text-center">
                    <h2 class="h3 text-muted">{{ __('@:username hasn\'t barked yet.', ['username' => $user->username]) }}</h2>
                    <p class="text-muted">{{ __('When they do, their barks will show up here.') }}</p>
                @endif
            </div>
        </div>
    </div>
@endsection
