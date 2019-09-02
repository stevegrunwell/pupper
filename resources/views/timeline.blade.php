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

            <div class="col">
                <Timeline route="{{ route('api.timeline') }}" />
            </div>
        </div>
    </div>
@endsection
