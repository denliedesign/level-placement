@extends('layouts.app')
@section('content')
    <div class="container my-5 d-flex align-items-center justify-content-center">
        @if(session('success'))
            <div>{{ session('success') }}</div>
        @endif
            <br>
        <form action="{{ route('levels.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" required>
            <button type="submit">Import</button>
        </form>
    </div>
@endsection
