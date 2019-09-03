@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12 sidebar">
            <article class="card sticky-sidebar mb-4">
                <div class="card-body">
                    @include('users.profile', ['user' => auth()->user()])
                </div>
            </article>
        </div>

        <div class="col-md-8">
            <article class="card">
                <h1 class="card-header h5">{{ __('Compose new Bark') }}</h1>
                <form class="card-body" method="post" action="{{ route('posts.store') }}">
                    <div class="form-group">
                        <label for="post-content">{{ __('What\'s on your mind?') }}</label>
                        <textarea name="content" id="post-content" class="form-control" rows="4"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">{{ __('Bark') }}</button>
                    @csrf
                </form>
            </article>
        </div>
    </div>
</div>
@endsection
