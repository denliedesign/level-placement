@extends('layouts.app')

@section('content')
    <div class="container my-5 d-flex justify-content-center align-items-center">
        @if (session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus autocomplete="username">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
            </div>

            <!-- Actions -->
            <div class="d-flex justify-content-between align-items-center">
                @if (Route::has('password.request'))
                    <a class="text-decoration-underline small text-muted" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <button type="submit" class="btn btn-primary">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>
    </div>
@endsection
