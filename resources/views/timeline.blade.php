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
                @if (! auth()->user()->following_count)
                    <section class="jumbotron bg-white border">
                        <h2 class="display-4">{{ __('Get the most out of Pupper.') }}</h2>
                        <p class="lead">{{ __('Follow other users to see their barks appear in your timeline.') }}</p>
                        @foreach($recommendedUsers as $account)
                            <div class="mb-2">
                                @include ('users.card', ['user' => $account])
                            </div>
                        @endforeach
                    </section>
                @endif
                <timeline route="{{ route('api.timeline') }}" />
            </div>
        </div>
    </div>
@endsection
