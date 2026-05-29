@extends('layouts.app')

@section('content')
    <main class="container my-5">
        <div class="text-center mb-4">
            <h1 class="fw-bold">Class Matches</h1>
            <p class="text-muted">These classes match the filters you selected.</p>
        </div>

        @if($classes->isNotEmpty())
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Style</th>
                        <th>Level</th>
                        <th>Day</th>
                        <th>Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td><strong>{{ $class->dance_style }}</strong></td>
                            <td>{{ $class->level }}</td>
                            <td>{{ $class->day_of_week }}</td>
                            <td>{{ $class->time }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center">No classes matched those filters. Try widening your search.</p>
        @endif

        <div class="d-flex justify-content-center mt-4">
            <a href="{{ route('dance-classes.scheduler') }}" class="btn-brand-secondary">Back to Scheduler</a>
        </div>
    </main>
@endsection
