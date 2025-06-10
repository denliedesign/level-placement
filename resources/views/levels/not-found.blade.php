@extends('layouts.app')
@section('content')
    <div style="max-width: 500px; margin: 50px auto; text-align: center; font-family: sans-serif;">
        <h2 style="color: #c00;">Email Not Found</h2>
        <p style="font-size: 1.1em;">
            Please try logging in or registering using the same email you provided to your dance studio.
        </p>
        <div style="margin-top: 30px;">
            <a class="text-decoration-none" style="margin-right: 15px; text-decoration: none; color: #fff; background: #007BFF; padding: 10px 20px; border-radius: 5px;" href="{{ route('logout') }}"
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
