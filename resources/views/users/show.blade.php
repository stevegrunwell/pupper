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
            <div class="card">
                <timeline route="{{ route('api.userTimeline', ['user' => $user]) }}" />
            </div>
        </div>
    </div>
</div>
@endsection
