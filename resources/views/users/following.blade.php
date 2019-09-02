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
            <h1 class="sr-only">{{ __('People @:username is following', ['username' => $user->username]) }}</h1>
            <div class="row">
                @foreach($users as $account)
                    <div class="col-sm-6 mb-4">
                        @include('users.card', ['user' => $account])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
