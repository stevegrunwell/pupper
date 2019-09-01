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
                        <follow-button
                            username="{{ $user->username }}"
                            :following="{{ auth()->user()->follows($user) ? 'true' : 'false' }}"
                        />
                    @endunless
                </div>
            </article>
        </div>

        <div class="col-md-8">
            <div class="card">
                <timeline route="{{ route('api.userTimeline', ['user' => $user]) }}" />
            </div>
        </div>
    </div>
</div>
@endsection
