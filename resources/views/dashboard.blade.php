@extends('layouts.app')

@section('header')
    <h2 class="fw-semibold fs-4 text-dark">
        {{ __('Dashboard') }}
    </h2>
@endsection

@section('content')
    @php($isAdmin = auth()->user()?->email === 'customdenlie@gmail.com')

    <div class="py-5">
        <div class="container">
            @if($isAdmin)
                <div class="text-center mb-4">
                    <h1 class="fw-bold">Admin Dashboard</h1>
                    <p class="text-muted mb-0">
                        Manage placement results and the class schedule families see after logging in.
                    </p>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-dark">
                                <h2 class="h4 fw-bold">Level Placements</h2>
                                <p class="text-muted">
                                    Import the placement spreadsheet and review every dancer's placement row.
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('levels.import.form') }}" class="btn-brand-primary">Import Levels</a>
                                    <a href="{{ route('levels.index') }}" class="btn-brand-secondary">View Placements</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body text-dark">
                                <h2 class="h4 fw-bold">Dance Classes</h2>
                                <p class="text-muted">
                                    Import the class schedule used by the scheduler and exact class recommendations.
                                </p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('dance-classes.import.form') }}" class="btn-brand-primary">Import Dance Classes</a>
                                    <a href="{{ route('dance-classes.scheduler') }}" class="btn-brand-secondary">Open Scheduler</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-body text-dark text-center">
                        <p class="mb-3">{{ __("You're logged in!") }}</p>
                        <a href="/levels" class="btn-brand-primary">View Level Placement</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
