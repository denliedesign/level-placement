@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4 text-dark">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
    <div class="py-5">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body text-dark text-center">
                    {{ __("You're logged in!") }}
                    <div class="text-center"><a href="/levels">View Level Placement</a></div>
                </div>
            </div>
        </div>
    </div>
@endsection
