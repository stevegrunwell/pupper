@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 sidebar">
            <article class="card sticky-sidebar mb-4">
                <div class="card-body">
                    @include('users.profile', ['user' => $post->user])
                </div>
            </article>
        </div>

        <div class="col-md-8">
            <article class="card">
                <div class="card-body">
                    {{ $post->content }}
                </div>
            </article>
        </div>
    </div>
</div>
@endsection
