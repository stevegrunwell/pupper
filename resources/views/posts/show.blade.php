@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            @include('users.card', ['user' => $post->user])
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
