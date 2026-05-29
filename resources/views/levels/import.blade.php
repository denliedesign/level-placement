@extends('layouts.app')
@section('content')
    <div class="container my-5 d-flex flex-column align-items-center justify-content-center gap-3">
        <div class="d-flex gap-3 flex-wrap justify-content-center">
            <a href="{{ route('levels.import.form') }}" class="btn-brand-primary">Level Placements</a>
            <a href="{{ route('dance-classes.import.form') }}" class="btn-brand-secondary">Dance Classes</a>
        </div>

        <div class="text-center">
            <h1 class="fw-bold">Import Level Placements</h1>
            <p class="text-muted mb-0">
                Expected columns: first name, last name, email, ballet, jazz, tap, pointe, acro, teacher recommendation, teacher comments.
            </p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="max-width: 720px;">
                <strong>Import could not be completed.</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('levels.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
                <input type="file" name="file" class="form-control" required>
                <button type="submit" class="btn-brand-primary">Import</button>
            </div>
        </form>
    </div>
@endsection
