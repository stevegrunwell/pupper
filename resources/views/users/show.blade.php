@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 sidebar">
            <article class="card sticky-sidebar mb-4">
                <div class="card-body">
                    <div class="user-header d-flex d-lg-block align-items-center">
                        <img src="{{ $user->getAvatarUrl(720) }}" alt="" class="avatar img-fluid rounded col-xs-3">
                        <div>
                            <h1 class="card-title h5">{{ $user->display_name }}</h1>
                            <p class="card-subtitle">{!! username($user) !!}</p>
                        </div>
                    </div>
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

        <div class="col">
            <div class="card">
                <timeline route="{{ route('api.userTimeline', ['user' => $user]) }}" />
            </div>
        </div>
    </div>
</div>
@endsection
