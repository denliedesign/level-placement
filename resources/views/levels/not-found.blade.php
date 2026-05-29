@extends('layouts.app')
@section('content')
    <div style="max-width: 500px; margin: 50px auto; text-align: center; font-family: sans-serif;">
        <h2 style="color: #c00;">Email Not Found</h2>
        <p style="font-size: 1.1em;">
            We could not find dancer results for the email on this account.
            Please log out and try another email address your family may have used with the studio.
        </p>
        <p class="text-muted">
            If registration says the email has already been taken, use Login instead. If you forgot the password for that email, use Forgot your password? on the login page.
        </p>
        <div style="margin-top: 30px;">
            <a class="btn-brand-danger" href="{{ route('logout') }}"
               onclick="event.preventDefault();
                                                                                     document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

@endsection
