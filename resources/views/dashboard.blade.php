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
                    <a href="/levels"><div class="text-center btn btn-lg btn-primary">View Level Placement</div></a>
                </div>
            </div>
        </div>
    </div>
@endsection
