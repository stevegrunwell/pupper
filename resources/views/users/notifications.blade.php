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

        <div class="col container">
            <h1 class="sr-only">{{ __('Notifications') }}</h1>
            <div class="card">
                @foreach($notifications as $notification)
                    @includeIf('notifications.show', ['notification' => $notification->data])
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
