@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                @include('users.card', ['user' => auth()->user()])
            </div>

            <div class="col-md-8">
                <Timeline route="{{ route('api.timeline') }}" />
            </div>
        </div>
    </div>
@endsection
