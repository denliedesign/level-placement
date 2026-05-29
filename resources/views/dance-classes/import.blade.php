@extends('layouts.app')

@section('content')
    <main class="container my-5 d-flex flex-column align-items-center gap-3">
        <div class="d-flex gap-3 flex-wrap justify-content-center">
            <a href="{{ route('levels.import.form') }}" class="btn-brand-secondary">Level Placements</a>
            <a href="{{ route('dance-classes.import.form') }}" class="btn-brand-primary">Dance Classes</a>
        </div>

        <div class="text-center">
            <h1 class="fw-bold">Import Dance Classes</h1>
            <p class="text-muted mb-0">
                Expected columns: day, time, style, level.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('dance-classes.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
                <input type="file" name="file" class="form-control" required>
                <button type="submit" class="btn-brand-primary">Import</button>
            </div>
        </form>
    </main>
@endsection
