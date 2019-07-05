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
                    @include('posts.timeline', ['post' => $post])
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
